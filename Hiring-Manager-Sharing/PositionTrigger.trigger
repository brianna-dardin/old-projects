trigger PositionTrigger on Position__c (after insert, after update) {
    SharingMethods.PositionAfterTrigger(trigger.new);
}