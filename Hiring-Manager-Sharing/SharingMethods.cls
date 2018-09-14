public class SharingMethods {
    public static void PositionAfterTrigger (List<Position__c> positions) {
	    //Create list of position share objects
	    List<Position__Share> pShares = new List<Position__Share>();
	    //Create set of position IDs to use to query Job Application records if after update
	    Set<ID> pIDs = new Set<ID>();
	    //Create a set of user IDs so we can delete the old shares if after update
	    Set<ID> uIDs = new Set<ID>();
	    
	    for(Position__c p : positions) {
	        //Make sure the Hiring Manager field isn't null
	        if(p.Hiring_Manager__c != null) {
	            /* This will fire only if the Position is just being created or if the Hiring Manager field is 
	             * changing on an existing Position object. If either of these is true, then create a new share 
	             * record and add it to the list */
	            if(trigger.isInsert ||
	               p.Hiring_Manager__c != (ID)Trigger.oldMap.get(p.Id).get('Hiring_Manager__c') ) {
	                   Position__Share pShare = new Position__Share();
	                       pShare.ParentId = p.Id;
	                       pShare.UserOrGroupId = p.Hiring_Manager__c;
	                       pShare.AccessLevel = 'edit';
	                       pShare.RowCause = Schema.Position__Share.RowCause.Hiring_Manager__c;
	                   pShares.add(pShare);
	                   
	                   /* If it's an update, grab the ID of the position so we can use it to query the related 
	                    * Job Apps and grab the old user IDs so we can delete the old sharing records */
	                   if(trigger.isUpdate) {
	                       pIDs.add(p.Id);
	                       uIDs.add( (ID)Trigger.oldMap.get(p.Id).get('Hiring_Manager__c') );
	                   }
	               }
	        }
	    }
	    
	    //If pShares has records in it, create them and write any errors to the debug log
	    if(!pShares.isEmpty()) {
	        try {
	            insert pShares;
	        } catch (Exception e) {
	            System.debug(e.getMessage());
	        }
	    }
		
	    /* If it's an update, query for any existing Job Apps and if they exist, pass them to the Job App 
	     * sharing method so that the shares can be recreated for the new hiring manager */
	    if(!pIDs.isEmpty()) {    
	        List<Job_Application__c> jobApps = [SELECT ID, Position__c, Candidate__c, Position__r.Hiring_Manager__c 
	                                            FROM Job_Application__c 
	                                            WHERE Position__c IN :pIDs];
	        
	        if(!jobApps.isEmpty()) {
	            JobAppSharing(jobApps);
	            
	            /* Grab the candidate and job app IDs and put them into sets so we can query the old shares 
	             * and delete them */
	            Set<ID> cIDs = new Set<ID>();
	            Set<ID> jaIDs = new Set<ID>();
	            
	            for(Job_Application__c ja : jobApps) {
	                cIDs.add(ja.Candidate__c);
	                jaIDs.add(ja.Id);
	            }
	            
	            //Query the candidate and job app shares
	            List<Candidate__Share> canShares = [SELECT Id, ParentId, UserOrGroupId
	                                                FROM Candidate__Share
	                                                WHERE ParentId IN :cIDs AND UserOrGroupId IN :uIDs];
	            
	            List<Job_Application__Share> jaShares = [SELECT Id, ParentId, UserOrGroupId
	                                                     FROM Job_Application__Share
	                                                     WHERE ParentId IN :jaIDs AND UserOrGroupId IN :uIDs];
	            
	            //delete the shares, and write any errors to the debug log
	            try {
	                if(!canShares.isEmpty()) {
	                    delete canShares;
	                }
	                
	                if(!jaShares.isEmpty()) {
	                    delete jaShares;
	                }
	            } catch (Exception e) {
	                system.debug(e.getMessage());
	            }
	        }
	        
	        // Query the old Position shares so that they can be deleted 
	        List<Position__Share> oldShares = [SELECT Id, ParentId, UserOrGroupId
	                                           FROM Position__Share
	                                           WHERE ParentId IN :pIDs AND UserOrGroupId IN :uIDs];
	        
	        if(!oldShares.isEmpty()) {
	            try {
	                delete oldShares;
	            } catch (Exception e) {
	                system.debug(e.getMessage());
	            }
	        }
	    }
    }
    
    public static void JobAppAfterTrigger (Set<ID> jaIDs) {
    	/* Query the job apps so we can get access to the hiring manager field on the Position as well as have all the needed fields
         * for creating the shares */
        List<Job_Application__c> jobApps = [SELECT Id, Candidate__c, Position__c, Position__r.Hiring_Manager__c 
                                            FROM Job_Application__c 
                                            WHERE Id IN :jaIDs];
        
        /* Check to see whether the Position on the Job App has the Hiring Manager defined, if so add the 
         * Job App to the shareList */
        List<Job_Application__c> shareList = new List<Job_Application__c>();
        for(Job_Application__c ja : jobApps) {
            if(ja.Position__r.Hiring_Manager__c != null) {
                shareList.add(ja);
            }
        }
        
        //If the shareList has Job Apps, pass the list to the sharing method
        if(!shareList.isEmpty()) {
            JobAppSharing(shareList);
        }
    }
    
    public static void JobAppBeforeTrigger(List<Job_Application__c> apps) {
    	for(Job_Application__c ja : apps) {
            if(ja.Candidate__c != (ID)Trigger.oldMap.get(ja.Id).get('Candidate__c') ) {
                ja.addError('You cannot change the candidate of a job application.');
            }
            
            if(ja.Position__c != (ID)Trigger.oldMap.get(ja.Id).get('Position__c') ) {
                ja.addError('You cannot change the position of a job application.');
            }
        }
    }
    
    public static void JobAppSharing(List<Job_Application__c> apps) {
        //Create the share lists before looping through the Job Apps
        List<Job_Application__Share> jaShares = new List<Job_Application__Share>();
    	List<Candidate__Share> canShares = new List<Candidate__Share>();
        
        /* For each Job App, create a share for the Hiring Manager that grants Edit access to the Job App and 
         * only Read access to the Candidate */
        for(Job_Application__c ja : apps) {
            Job_Application__Share jaShare = new Job_Application__Share();
            	jaShare.ParentId = ja.Id;
            	jaShare.UserOrGroupId = ja.Position__r.Hiring_Manager__c;
            	jaShare.AccessLevel = 'edit';
            	jaShare.RowCause = Schema.Job_Application__Share.RowCause.Hiring_Manager__c;
            jaShares.add(jaShare);
            
            Candidate__Share canShare = new Candidate__Share();
            	canShare.ParentId = ja.Candidate__c;
            	canShare.UserOrGroupId = ja.Position__r.Hiring_Manager__c;
            	canShare.AccessLevel = 'read';
            	canShare.RowCause = Schema.Candidate__Share.RowCause.Hiring_Manager__c;
            canShares.add(canShare);
        }
        
        //Create the share records and write any errors to the debug log
        try {
            insert canShares;
            insert jaShares;
        } catch (Exception e) {
            System.debug(e.getMessage());
        }
    }
}