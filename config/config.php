<?php
error_reporting(1);


$whitelist = array('localhost', '127.0.0.1','demo4.coderangers.com');

if(!in_array($_SERVER['HTTP_HOST'], $whitelist)){
    // not valid
}


//Define app name 
define('APP_NAME',"Exling Video Archive");



//Appliation config
define('APP_PATH', realpath(dirname(__dir__)));

include_once APP_PATH.'/config/dbconfig.php';

//Define site title 
define('SITE_TITLE',"Exling Video Archive");

//Define meta description
define('META_DESCRIPTION',"Exling Video Archive"); 

//Define meta description
define('SITE_KEYWORDS',"Bangladesh video, video archive, bangla tutorial, bangla learning, CDN Bangladesh"); 


//SMTP Email configuration, It required PEAR mail packages
define('APP_EMAIL_FROM',"info@coderangers.com");
define('EMAIL_HOST',"ssl://smtp.gmail.com");
define('EMAIL_PORT',465);
define('EMAIL_USERNAME',"exlingdev@gmail.com");
define('EMAIL_PASSWORD',"exling123");
define('APP_PASS_ENCRYPTION',"MD5");

//uploaded files stored locaton
define('ORIGINAL_PATH',APP_PATH.'/assets/uploads/original/');
define('CONVERTED_PATH',APP_PATH.'/assets/uploads/converted/');
define('POSTER_PATH',APP_PATH.'/assets/uploads/posters/');
define('AVATAR_PATH',APP_PATH.'/assets/uploads/profile/');
define('AVATAR_THUMB_PATH',APP_PATH.'/assets/uploads/profile/thumb/');

define('AVATAR_URL',BASE_URL.'assets/uploads/profile/');
define('AVATAR_THUMB_URL',BASE_URL.'assets/uploads/profile/thumb/');
define('POSTER_URL',BASE_URL.'assets/uploads/posters/');
define('VIDEO_URL',BASE_URL.'assets/uploads/converted/');
define('CAT_POSTER_URL',BASE_URL.'assets/img/category/');

$route['profile'][':any'] = 'user=$1';
$route['profile']['loadprofilevideo'][':any'] = 'user=$1';
$route['video']['play'][':any'] = 'id=$1';
$route['video']['getlist'][':any'] = 'type=$1';
$route['video']['likevideo'][':any'] = 'id=$1';
$route['video']['postcomment'][':any'] = 'id=$1';
$route['video']['likecomment'][':any'] = 'id=$1';
$route['video']['getcatlist'][':any'] = 'type=$1';
?>