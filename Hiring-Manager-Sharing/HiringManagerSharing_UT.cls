@isTest
private class HiringManagerSharing_UT {
    static testMethod void UnitTest() {
        //Query 2 users to use, 1 for the initial creation and 1 for the update test later on
        List<User> users = [SELECT Id, Profile.UserLicense.LicenseDefinitionKey 
                            FROM User
                            WHERE Profile.UserLicense.LicenseDefinitionKey = 'SFDC'
                            LIMIT 2];
        
        //Create the positions
        List<Position__c> positions = new List<Position__c>();
        for(Integer i = 0; i < 10; i++) {
            Position__c p = new Position__c();
            	p.Name = 'Test ' + i;
            	p.Hiring_Manager__c = users[0].Id;
            positions.add(p);
        }
        
        insert positions;
		//Query the positions just created and grab their IDs in a set
        List<Position__c> newPositions = [SELECT Id, Hiring_Manager__c 
                                          FROM Position__c 
                                          WHERE Hiring_Manager__c = :users[0].Id];
        
        Set<ID> pIDs = new Set<ID>();
        for(Position__c p : newPositions) {
            pIDs.add(p.Id);
        }
        
        //Query the shares that should've been created and assert that they were
		List<Position__Share> pShares = [SELECT Id, UserOrGroupId, ParentId 
                                         FROM Position__Share 
                                         WHERE UserOrGroupId = :users[0].Id AND ParentId IN :pIDs];
        system.assert(!pShares.isEmpty());
        
        //Create the candidates
        List<Candidate__c> candidates = new List<Candidate__c>();
        for(Integer i = 0; i < 10; i++) {
            Candidate__c c = new Candidate__c();
            	c.First_Name__c = 'Test';
            	c.Last_Name__c = String.valueOf(i);
            candidates.add(c);
        }
        
        insert candidates;
      	
        //Query the newly created candidates, then create the job apps
        List<Candidate__c> newCandidates = [SELECT Id, First_Name__c FROM Candidate__c WHERE First_Name__c = 'Test'];
        List<Job_Application__c> jobApps = new List<Job_Application__c>();
        
        for(Integer i = 0; i < newPositions.size(); i++) {
            Job_Application__c ja = new Job_Application__c();
            	ja.Position__c = newPositions[i].id;
            	ja.Candidate__c = newCandidates[i].id;
            jobApps.add(ja);
        }
        
        insert jobApps;
        
        //grab the IDs of the candidates and query the shares that should've been created and assert they were
        Set<ID> cIDs = new Set<ID>();
        for(Candidate__c c : newCandidates) {
            cIDs.add(c.Id);
        }
        
        List<Candidate__Share> canShares = [SELECT Id, ParentId, UserOrGroupId
                                            FROM Candidate__Share
                                            WHERE ParentId IN :cIDs AND UserOrGroupId = :users[0].Id];
        system.assert(!canShares.isEmpty());
        
        //query the job app shares and assert they were created
        List<Job_Application__c> newJobApps = [SELECT Id, Position__c, Candidate__c
                                               FROM Job_Application__c
                                               WHERE Position__c IN :pIDs AND Candidate__c IN :cIDs];
        
        Set<ID> jaIDs = new Set<ID>();
        for(Job_Application__c ja : newJobApps) {
            jaIDs.add(ja.Id);
        }
        
        List<Job_Application__Share> jaShares = [SELECT Id, ParentId, UserOrGroupId
                                                 FROM Job_Application__Share
                                                 WHERE ParentID IN :jaIDs AND UserOrGroupId = :users[0].Id];
        system.assert(!jaShares.isEmpty());
        
        /* UPDATE POSITION WITH NEW HIRING MANAGER TEST */
        
        for(Position__c p : newPositions) {
            p.Hiring_Manager__c = users[1].Id;
        }
        
        update newPositions;
        
        //query all the new position, candidate and job app shares that should've been created and assert that they were
		List<Position__Share> pShares2 = [SELECT Id, UserOrGroupId, ParentId 
                                         FROM Position__Share 
                                         WHERE UserOrGroupId = :users[1].Id AND ParentId IN :pIDs];
        system.assert(!pShares2.isEmpty());   
        
        List<Candidate__Share> canShares2 = [SELECT Id, ParentId, UserOrGroupId
                                            FROM Candidate__Share
                                            WHERE ParentId IN :cIDs AND UserOrGroupId = :users[1].Id];
        system.assert(!canShares2.isEmpty());
        
        List<Job_Application__Share> jaShares2 = [SELECT Id, ParentId, UserOrGroupId
                                                 FROM Job_Application__Share
                                                 WHERE ParentID IN :jaIDs AND UserOrGroupId = :users[1].Id];
        system.assert(!jaShares2.isEmpty());
        
        //query all the old position, candidate and job app shares and assert that they were deleted
        List<Position__Share> pShares3 = [SELECT Id, UserOrGroupId, ParentId 
                                         FROM Position__Share 
                                         WHERE UserOrGroupId = :users[0].Id AND ParentId IN :pIDs];
        system.assert(pShares3.isEmpty());
        
        List<Candidate__Share> canShares3 = [SELECT Id, ParentId, UserOrGroupId
                                            FROM Candidate__Share
                                            WHERE ParentId IN :cIDs AND UserOrGroupId = :users[0].Id];
        system.assert(canShares3.isEmpty());
        
        List<Job_Application__Share> jaShares3 = [SELECT Id, ParentId, UserOrGroupId
                                                 FROM Job_Application__Share
                                                 WHERE ParentID IN :jaIDs AND UserOrGroupId = :users[0].Id];
        system.assert(jaShares3.isEmpty());
        
        /* PREVENT CANDIDATE FROM BEING UPDATED ON JOB APP TEST */
        
        Integer x = newCandidates.size();
        for(Job_Application__c ja : newJobApps) {
            x--;
            ja.Candidate__c = newCandidates[x].id;
        }
        
        try {
            update newJobApps;
        } catch (Exception e) {
            system.assert(e.getMessage().contains('You cannot change the candidate of a job application.'));
        }
        
        /* PREVENT POSITION FROM BEING UPDATED ON JOB APP TEST */
        
        x = newPositions.size();
        for(Job_Application__c ja : newJobApps) {
            x--;
            ja.Position__c = newPositions[x].id;
        }
        
        try {
            update newJobApps;
        } catch (Exception e) {
            system.assert(e.getMessage().contains('You cannot change the position of a job application.'));
        }
    }
}