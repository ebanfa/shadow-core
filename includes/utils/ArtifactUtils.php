<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


class ArtifactUtils {

    public static $pages = array(

    );
    public static $artifacts = array(
        'dashboard' => array('name' => 'Dashboard', 'description' => 'Dashboard', 'artifact_type' => 'page'),
        'accountstatement' => array('name' => 'AccountStatement', 'description' => 'Account Statement', 'artifact_type' => 'page'),
        'notificationitems' => array('name' => 'NotificationItems', 'description' => 'Notification Items', 'artifact_type' => 'page'),
        'currency' => array('name' => 'Currency', 'description' => 'Currency', 'artifact_type' => 'entity', 'data_type' => 'sb_currency'),
        'locationtype' => array('name' => 'LocationType', 'description' => 'Location Type', 'artifact_type' => 'entity', 'data_type' => 'sb_loctype'),
        'location' => array('name' => 'Location', 'description' => 'Location', 'artifact_type' => 'entity', 'data_type' => 'sb_location'),
        'business' => array('name' => 'Business', 'description' => 'Business', 'artifact_type' => 'entity', 'data_type' => 'sb_business'),
        'businessunit' => array('name' => 'BusinessUnit', 'description' => 'Business Unit', 'artifact_type' => 'entity', 'data_type' => 'sb_businessunit'),
        'partycategory' => array('name' => 'PartyCategory', 'description' => 'Party Category', 'artifact_type' => 'entity', 'data_type' => 'sb_partycat'),
        'partytype' => array('name' => 'PartyType', 'description' => 'Party Type', 'artifact_type' => 'entity', 'data_type' => 'sb_partytype'),
        'roletype' => array('name' => 'RoleType', 'description' => 'Role Type', 'artifact_type' => 'entity', 'data_type' => 'sb_roletype'),
        'party' => array('name' => 'Party', 'description' => 'Party', 'artifact_type' => 'entity', 'data_type' => 'sb_party'),
        'partyrole' => array('name' => 'PartyRole', 'description' => 'Party Role', 'artifact_type' => 'entity', 'data_type' => 'sb_partyrole'),
        'relationshiptype' => array('name' => 'RelationshipType', 'description' => 'Relationship Type', 'artifact_type' => 'entity', 'data_type' => 'sb_reltype'),
        'relationshipstatus' => array('name' => 'RelationshipStatus', 'description' => 'Relationship Status', 'artifact_type' => 'entity', 'data_type' => 'sb_relstatus'),
        'partyrelationship' => array('name' => 'PartyRelationship', 'description' => 'Party Relationship', 'artifact_type' => 'entity', 'data_type' => 'sb_partyrel'),
        'partygroup' => array('name' => 'PartyGroup', 'description' => 'Party Group', 'artifact_type' => 'entity', 'data_type' => 'sb_partygroup'),
        'person' => array('name' => 'Person', 'description' => 'Person', 'artifact_type' => 'entity', 'data_type' => 'sb_person'),
        'partyprofile' => array('name' => 'PartyProfile', 'description' => 'Party Profile', 'artifact_type' => 'entity', 'data_type' => 'sb_partyprofile'),
        'billingaccount' => array('name' => 'BillingAccount', 'description' => 'Billing Account', 'artifact_type' => 'entity', 'data_type' => 'sb_billaccount'),
        'conversation' => array('name' => 'Conversation', 'description' => 'Conversation', 'artifact_type' => 'entity', 'data_type' => 'sb_conversation'),
        'conversationuser' => array('name' => 'ConversationUser', 'description' => 'Conversation User', 'artifact_type' => 'entity', 'data_type' => 'sb_conuser'),
        'message' => array('name' => 'Message', 'description' => 'Message', 'artifact_type' => 'entity', 'data_type' => 'sb_message'),
        'messagefiles' => array('name' => 'MessageFiles', 'description' => 'Message Files', 'artifact_type' => 'entity', 'data_type' => 'sb_messagesfiles'),
        'notificationtype' => array('name' => 'NotificationType', 'description' => 'Notification Type', 'artifact_type' => 'entity', 'data_type' => 'sb_notifytype'),
        'notificationstatus' => array('name' => 'NotificationStatus', 'description' => 'Notification Status', 'artifact_type' => 'entity', 'data_type' => 'sb_notifystatus'),
        'notificationlevel' => array('name' => 'NotificationLevel', 'description' => 'Notification Level', 'artifact_type' => 'entity', 'data_type' => 'sb_notifylevel'),
        'notification' => array('name' => 'Notification', 'description' => 'Notification', 'artifact_type' => 'entity', 'data_type' => 'sb_notification'),
        'contactus' => array('name' => 'ContactUs', 'description' => 'Contact Us', 'artifact_type' => 'entity', 'data_type' => 'sb_contactus'),
        'documenttype' => array('name' => 'DocumentType', 'description' => 'Document Type', 'artifact_type' => 'entity', 'data_type' => 'sb_doctype'),
        'urgency' => array('name' => 'Urgency', 'description' => 'Urgency', 'artifact_type' => 'entity', 'data_type' => 'sb_docurgency'),
        'noofpages' => array('name' => 'NoOfPages', 'description' => 'Number Of Pages', 'artifact_type' => 'entity', 'data_type' => 'sb_noofpages'),
        'subjectarea' => array('name' => 'SubjectArea', 'description' => 'Subject Area', 'artifact_type' => 'entity', 'data_type' => 'sb_subarea'),
        'academiclevel' => array('name' => 'AcademicLevel', 'description' => 'Academic Level', 'artifact_type' => 'entity', 'data_type' => 'sb_alevel'),
        'writingstyle' => array('name' => 'WritingStyle', 'description' => 'Writing Style', 'artifact_type' => 'entity', 'data_type' => 'sb_wstyle'),
        'contentorder' => array('name' => 'ContentOrder', 'description' => 'Content Order', 'artifact_type' => 'entity', 'data_type' => 'sb_corder'),
    );
    
}

?>