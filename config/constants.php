<?php
//Module wise status
if (!defined('PRODUCT_CATEGORY_ACTIVE')) define('PRODUCT_CATEGORY_ACTIVE', '1');
if (!defined('PRODUCT_CATEGORY_INACTIVE')) define('PRODUCT_CATEGORY_INACTIVE', '0');

//Module wise status
if (!defined('SPECIFICATION_CATEGORY_ACTIVE')) define('SPECIFICATION_CATEGORY_ACTIVE', '1');
if (!defined('SPECIFICATION_CATEGORY_INACTIVE')) define('SPECIFICATION_CATEGORY_INACTIVE', '0');

//Default status options
if (!defined('STATUS_ACTIVE')) define('STATUS_ACTIVE', '1');
if (!defined('STATUS_INACTIVE')) define('STATUS_INACTIVE', '0');

//Upload sources options
if (!defined('SOURCE_USER_UPLOAD')) define('SOURCE_USER_UPLOAD', 'user_upload');
if (!defined('SOURCE_USER_ENTRY')) define('SOURCE_USER_ENTRY', 'user_entry');
if (!defined('SOURCE_AI')) define('SOURCE_AI', 'textbrew_ai');


//Messages
if (!defined('PRODUCT_CREATE_SUCCESS')) define('PRODUCT_CREATE_SUCCESS', value: 'Product details successfully created.');
if (!defined('PRODUCT_UPDATE_SUCCESS')) define('PRODUCT_UPDATE_SUCCESS', 'Product successfully updated.');
if (!defined('PRODUCT_DELETE_SUCCESS')) define('PRODUCT_DELETE_SUCCESS', 'Product successfully deleted.');


//Common messages for error,warning,error, success messages
if (!defined('COMMON_CREATE_SUCCESS')) define('COMMON_CREATE_SUCCESS', '{module-name} successfully created.');
if (!defined('COMMON_UPDATE_SUCCESS')) define('COMMON_UPDATE_SUCCESS', '{module-name} successfully updated.');
if (!defined('COMMON_DELETE_SUCCESS')) define('COMMON_DELETE_SUCCESS', '{module-name} successfully deleted.');
if (!defined('COMMON_STATUS_SUCCESS')) define('COMMON_STATUS_SUCCESS', '{module-name} status updated successfully.');
if (!defined('COMMON_DELETE_FAILURE')) define('COMMON_DELETE_FAILURE', 'Failed to delete {module-name}. Please try again or contact support if the issue persists.');
if (!defined('COMMON_NOTE_CREATE_SUCCESS')) define('COMMON_NOTE_CREATE_SUCCESS', '{module-name} successfully created.');
if (!defined('COMMON_NOTE_UPDATE_SUCCESS')) define('COMMON_NOTE_UPDATE_SUCCESS', '{module-name} successfully updated.');
if (!defined('COMMON_NOTE_DELETE_SUCCESS')) define('COMMON_NOTE_DELETE_SUCCESS', '{module-name} successfully deleted.');
if (!defined('COMMON_RESTORE_SUCCESS')) define('COMMON_RESTORE_SUCCESS', 'Document restored successfully.');
if (!defined('COMMON_PERMANENT_DELETE_SUCCESS')) define('COMMON_PERMANENT_DELETE_SUCCESS', 'Document permanently deleted.');
if (!defined('COMMON_RESTORE_ERROR'))define('COMMON_RESTORE_ERROR', 'Error restoring Document.');
if (!defined('COMMON_PERMANENT_DELETE_ERROR')) define('COMMON_PERMANENT_DELETE_ERROR', 'Error permanently deleting Document.');

// Confirmation Message Constants
if (!defined('COMMON_DELETE_CONFIRM')) define('COMMON_DELETE_CONFIRM', 'Are you sure you want to delete this Document?');
if (!defined('COMMON_PERMANENT_DELETE_CONFIRM')) define('COMMON_PERMANENT_DELETE_CONFIRM', 'Are you sure you want to permanently delete this Document? This action cannot be undone.');
if (!defined('COMMON_RESTORE_CONFIRM')) define('COMMON_RESTORE_CONFIRM', 'Are you sure you want to restore this Document?');


//Page size setting
if (!defined('PER_PAGE')) define('PER_PAGE', 25);
if (!defined('MAX_DESC')) define('MAX_DESC', 256);



//Default status options for Product
if (!defined('STATUS_PENDING')) define('STATUS_PENDING', '0');
if (!defined('STATUS_INPROGRESS')) define('STATUS_INPROGRESS', '1');
if (!defined('STATUS_DONE')) define( 'STATUS_DONE', '2');
if (!defined('STATUS_ERROR')) define('STATUS_ERROR', '3');

if (!defined('API_QUEUE_STATUS')) {
        define('API_QUEUE_STATUS', [
            STATUS_PENDING => 'queued',
            STATUS_INPROGRESS => 'processing',
            STATUS_DONE => 'completed',
            STATUS_ERROR => 'failed'
        ]);
}


//Product Status AI
if (!defined('PRODUCT_STATUS_CHECK')) define('PRODUCT_STATUS_CHECK', 0);
if (!defined('PRODUCT_STATUS_COLLECT')) define('PRODUCT_STATUS_COLLECT', 1);
if (!defined('PRODUCT_STATUS_WRITING')) define('PRODUCT_STATUS_WRITING', 2);
if (!defined('PRODUCT_STATUS_DONE')) define('PRODUCT_STATUS_DONE', 3);
if (!defined('PRODUCT_STATUS_ERROR')) define('PRODUCT_STATUS_ERROR', 4);
if (!defined('PRODUCT_STATUS_RETRYING')) define('PRODUCT_STATUS_RETRYING', 5);

if (!defined('DEFAULT_LANGUAGE')) define('DEFAULT_LANGUAGE', "Dutch (Netherlands)");


//Module Title
if (!defined('AICONTENT_TITLE')) define('AICONTENT_TITLE', 'Products');
if (!defined('AIPRODUCTDETAILS_TITLE')) define('AIPRODUCTDETAILS_TITLE', 'Products Details');
if (!defined('AICONTENTMANAGER_TITLE')) define('AICONTENTMANAGER_TITLE', 'AI Content');
if (!defined('USP_COLLECTION_TITLE')) define('USP_COLLECTION_TITLE', 'USP');
if (!defined('USP_MANAGER_TITLE')) define('USP_MANAGER_TITLE', 'USP Manager');
if (!defined('AI_EXPORT_TITLE')) define('AI_EXPORT_TITLE', 'AI Content Export');
if (!defined('TITLE_FORMAT_TITLE')) define('TITLE_FORMAT_TITLE', 'Title Format');
if (!defined('SETTING_TITLE')) define('SETTING_TITLE', 'Settings');
if (!defined('KEYWORDS_TITLE')) define('KEYWORDS_TITLE', 'Keywords');
if (!defined('LEAVE_TITLE')) define('LEAVE_TITLE', 'Leave');
if (!defined('NOTE_TITLE')) define('NOTE_TITLE', 'Note');

if (!defined('TASK_MANAGER_TITLE')) define('TASK_MANAGER_TITLE', 'Task Manager');
if (!defined('TODO_TITLE')) define('TODO_TITLE', 'Todo');
if (!defined('DEPARTMENT_TITLE')) define('DEPARTMENT_TITLE', 'Department');
if (!defined('TYPE_OF_WORK_TITLE')) define('TYPE_OF_WORK_TITLE', 'Type Of Work');
if (!defined('DOC_CATEGORY_TITLE')) define('DOC_CATEGORY_TITLE', 'Document Category');
if (!defined('DOCUMENT_TITLE')) define('DOCUMENT_TITLE', 'Document');
if (!defined('DOCUMENT_SUB_TITLE')) define('DOCUMENT_SUB_TITLE', 'Sub Document');
if (!defined('DOCUMENT_DELETED_TITLE')) define('DOCUMENT_DELETED_TITLE', 'Deleted My Drive Document');
if (!defined('ADMIN_EMAIL')) define('ADMIN_EMAIL', 'tim4@spamok.nl');
if (!defined('DEFAULT_API_BASED_COLLECTION_NAME')) define('DEFAULT_API_BASED_COLLECTION_NAME', 'System Collection');
if (!defined('SEARCH_BY_EAN')) define('SEARCH_BY_EAN', 'byean');
if (!defined('SEARCH_BY_EAN_TEXT')) define('SEARCH_BY_EAN_TEXT', 'Search By EAN');
if (!defined('NATURE_OF_WORK_TITLE')) define('NATURE_OF_WORK_TITLE', 'Nature Of Work');
if (!defined('BRANCH_TITLE')) define('BRANCH_TITLE', 'Branches');
if (!defined('AUTHORITY_TITLE')) define('AUTHORITY_TITLE', 'Approval Authority');
if (!defined('TICKET_TITLE')) define('TICKET_TITLE', 'Ticket');

if (!defined('DEFAULT_WARNING_RESPONSE')) define('DEFAULT_WARNING_RESPONSE', "We couldn't retrieve the requested data at this time. Please try again later or contact support if the issue persists.");
if (!defined('EXPENSES_TITLE')) define('EXPENSES_TITLE', 'Expenses');
if (!defined('GROUP_EMAIL_TITLE')) define('GROUP_EMAIL_TITLE', 'Group Email');
if (!defined('STOCK_CATEGORY_TITLE')) define('STOCK_CATEGORY_TITLE', 'Stock Category');
if (!defined('STOCK_PRODUCT_TITLE')) define('STOCK_PRODUCT_TITLE', 'Stock Product');
if (!defined('STOCK_MOVEMENT_TITLE')) define('STOCK_MOVEMENT_TITLE', 'Stock Movement');
if (!defined('STOCK_CUSTOMERS_TITLE')) define('STOCK_CUSTOMERS_TITLE', 'Stock Customers');
if (!defined('STOCK_SALE_TITLE')) define('STOCK_SALE_TITLE', 'Stock Sale');

if (!defined('BILLING_DETAILS_TITLE')) define('BILLING_DETAILS_TITLE', 'Billing Details');
if (!defined('BILLING_MASTER_TITLE')) define('BILLING_MASTER_TITLE', 'Billing Master');

// New constants for default settings
if (!defined('DEFAULT_NUMBER_OF_SECTIONS')) define('DEFAULT_NUMBER_OF_SECTIONS', 3);
if (!defined('DEFAULT_NUMBER_OF_PARAGRAPHS')) define('DEFAULT_NUMBER_OF_PARAGRAPHS', 1);
if (!defined('DEFAULT_NUMBER_OF_SPECS')) define('DEFAULT_NUMBER_OF_SPECS', 5);
if (!defined('DEFAULT_NUMBER_OF_KEYWORDS')) define('DEFAULT_NUMBER_OF_KEYWORDS', 5);
if (!defined('DEFAULT_NUMBER_OF_KEY_FEATURES')) define('DEFAULT_NUMBER_OF_KEY_FEATURES', 5);
if (!defined('DEFAULT_NUMBER_OF_TITLE_LENGTH')) define('DEFAULT_NUMBER_OF_TITLE_LENGTH', 256);
if (!defined('DEFAULT_NUMBER_OF_USP_LENGTH')) define('DEFAULT_NUMBER_OF_USP_LENGTH', 256);

if (!defined('CONTENT_SOURCE')) {
    define('CONTENT_SOURCE', [
         'product' => 0,
         'ai_content'=> 1,
    ]);
}
if (!defined('CONTENT_SOURCE_DISPLAY')) {
    define('CONTENT_SOURCE_DISPLAY', [
         0=>'product',
         1=>'ai content'
    ]);
}
if (!defined('WRONG_ERROR')) define('WRONG_ERROR', "Something went wrong. Our team is aware.");
if (!defined('ADD_DATA_ERROR')) define('ADD_DATA_ERROR', "Please add product content and retry." );
if (!defined('DATA_READY')) define('DATA_READY', "Ready" );
if (!defined('ERROR_TYPE_USER')) define('ERROR_TYPE_USER', 0);
if (!defined('ERROR_TYPE_SYSTEM')) define('ERROR_TYPE_SYSTEM', 1);
if (!defined('DATA_READY_STATUS')) define('DATA_READY_STATUS', 2);

//Log Messages
if (!defined('SKIPPED_AI_REQUEST')) define('SKIPPED_AI_REQUEST', "Product data not available - AI content generation skipped. No prompt generation or AI request attempted.");

if (!defined('ADMIN_EMAILS')) {
    define('ADMIN_EMAILS', [
        'tim4@spamok.com',
        'tim4@spamok.nl',
        'tachmy.dilmy@shoppingresult.com'
    ]);
}

if (!defined('DEFAULT_RETRYING_MESSAGE')) define( 'DEFAULT_RETRYING_MESSAGE', "We are retrying to generate AI content for this product. Please wait a moment.");


if (!defined('DOC_VALIDITY')) {
    define('DOC_VALIDITY', [
        '1' => 'Life Time',
        '2' => 'Renewal',
        '3' => 'Provisional',
        '4' => 'Property'
    ]);
}

if (!defined('RAJ_PRIVATE_FOLDER_DROPBOX')) define('RAJ_PRIVATE_FOLDER_DROPBOX', 'Raj_Documents');
if (!defined('DEFAULT_FOLDER_DROPBOX')) define('DEFAULT_FOLDER_DROPBOX', 'Docmey');

if (!defined('TIMEZONE'))  define("TIMEZONE", "Asia/Kolkata");