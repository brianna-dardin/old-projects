@RestResource(urlMapping='/contacts')
global without sharing class ContactManager {
	@HttpGet
    global static Account[] getAccounts() {
        Account[] accounts = [SELECT Id, Name, Description, Industry,
                              (SELECT Id, Name, Phone, Email
                               FROM Contacts)
                              FROM Account
                              ORDER BY Name ASC
                              LIMIT 20];
        return accounts;
    }
}