<?
define('_PROJECT_PATH_',dirname(__FILE__)."/..");
define('_PROJECT_URL_','http://'.$_SERVER['HTTP_HOST'].'/angular');
define('_JS_VERSION_',substr(md5(rand()), 0, 7));
define('_GOOGLE_API_KEY_',file_get_contents(_PROJECT_PATH_.'/backend/keys/google_api_key'));
define('_EBAY_API_KEY_',file_get_contents(_PROJECT_PATH_.'/backend/keys/ebay_api_key'));
