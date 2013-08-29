<?php
error_reporting(1);
 include_once ( realpath(dirname(__dir__)).'/config/config.php' );
 include_once (APP_PATH.'/system/i18n/translate.class.php' );
 include_once (APP_PATH.'/system/database/connect.class.php' );
 include_once (APP_PATH.'/system/helper/Singleton.php' );
 include_once (APP_PATH.'/system/helper/Template.php' );
 include_once (APP_PATH.'/system/helper/Registry.php' );
 include_once (APP_PATH.'/system/helper/generic.class.php' );
 include_once (APP_PATH.'/system/helper/Session.php' );
 include_once (APP_PATH.'/system/rest/Rest.php' );
 


 class Application extends Generic{
 
    public $db;
	public $rest;
 
 	function __construct() {
		$this->init();
		$this->db = parent::getConn();
		
	}

 
	public function getDB(){

		return $this->db ;
	}
	private function initSession(){
		$domain =str_replace('http://', '', BASE_URL);
		$domain = str_replace('/', '', $domain);
		SessionManager::sessionStart('coderangers', 0, '/', $domain, false);
	}	
	private function init(){
			
		
			// Define path to application directory
			defined('APPLICATION_PATH')
				|| define('APPLICATION_PATH', realpath(dirname(__FILE__)));
			
			// Define application environment
			defined('APPLICATION_ENV')
				|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
			
			// Ensure library/ is on include_path
			set_include_path(implode(PATH_SEPARATOR, array(
				realpath(APPLICATION_PATH),
				get_include_path(),
			)));
			
			// Define path to data directory
			defined('APPLICATION_DATA')
				|| define('APPLICATION_DATA', realpath(dirname(__FILE__) . '/../../data/logs'));
			
			function __autoload($path) {
				return include str_replace('_', '/', $path) . '.php';
			}
			
 			$rest =& getInstance('Rest');	
			$this->rest = $rest;			
			$this->initSession();



			if(($_SESSION['lang'])=="")
			$_SESSION['lang']='en';

			if(isset($_GET['lang']) && isset($_GET['lang'])!=""){
				$_SESSION['lang']=$_GET['lang'];
			}
		}
		
 
		
 }
 
ob_start();
$APP = new Application();
$setTranslate = new Translate();
$APP->rest->setDb($APP->db);	
$APP->rest->setRoute($route);	
$APP->rest->setTemplate(new Template(new Registry(),$APP));	
$APP->rest->process();
ob_end_flush();


?>