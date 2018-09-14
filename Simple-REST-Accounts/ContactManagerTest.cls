@isTest
private class ContactManagerTest {
    @isTest static void test() {
        List<Account> accounts = new List<Account>();
        for(Integer i = 0; i < 20; i++) {
            accounts.add(new Account(Name = 'Test ' + i));
        }
        insert accounts;
        
        List<Contact> contacts = new List<Contact>();
        for(Account a : accounts) {
            Contact c = new Contact();
            	c.LastName = 'Test';
            	c.AccountId = a.Id;
            contacts.add(c);
        }
        insert contacts;
        
        RestRequest request = new RestRequest();
        request.requestUri = 'https://na16.salesforce.com/services/apexrest/contacts';
        request.httpMethod = 'GET';
        RestContext.request = request;
        
        Account[] restAccts = ContactManager.getAccounts();
        system.assertEquals(20, restAccts.size());
    }
}