/* I initially had this as an after update trigger as well but decided that it didn't make any sense for the Candidate or 
 * Position fields on the Job Application to ever change, so I added logic to prevent that from happening in a before
 * update trigger */
trigger JobApplicationTrigger on Job_Application__c (before update, after insert) {
    if(trigger.isBefore) {
        SharingMethods.JobAppBeforeTrigger(trigger.new);
    } else {
        SharingMethods.JobAppAfterTrigger(trigger.newMap.keySet());
    }
}