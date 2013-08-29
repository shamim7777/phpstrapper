<?php
## Some Resources:
## PHP + I18n: http://onlamp.com/pub/a/php/2002/11/28/php_i18n.html
## xgettext for ->msg(...): http://ubuntuforums.org/showthread.php?t=488412
## gettext tutorial: http://mel.melaxis.com/devblog/2005/08/06/localizing-php-web-sites-using-gettext/

class Library_Localization_Object {
	
	#######
	## Fields
	#######
	private $locale_folder;
	private $selected_locale;
	private $selected_domain;
	
	private $current_translations;
	private $is_loaded;
	
	const translatedFileAdding = '.po';
	const serializedFileAdding = '.ser';
	
	#######
	## Constructor and Deconstructor
	#######
	
	function __construct(){
		$this->locale_folder = APP_PATH.'/app/Library/Localization/'.'locale';
		$this->selected_domain = 'messages';
		$this->selected_locale = 'en_US';
		
		$this->is_loaded = false;
	}
	
	function __destruct(){
	}
	
	
	#######
	## Functions
	#######
	
	public function setDomain($domain){
		$this->selected_domain = $domain;
		$this->is_loaded = false;
	}

	public function setLocale($locale){
		$this->selected_locale = $locale;
		$this->is_loaded = false;
	}
	
	private function load(){
		$file = $this->getCurrentLocaleDomainFile();
		$this->current_translations = unserialize(file_get_contents($file));
		
		$this->is_loaded = true;
	}
	
	private function getCurrentLocaleDomainFile(){
		$locale = $this->selected_locale;
		
		$file = $this->getSerializedFilePathForLocale($locale);
		if(file_exists($file))
			return $file;
		
		// remove last part of locale after @ and .
		if(strpos($locale, "@")>0)
			$locale = substr($locale,0,strpos($locale, "@"));
		if(strpos($locale, ".")>0)
			$locale = substr($locale,0,strpos($locale, "."));
		
		$file = $this->getSerializedFilePathForLocale($locale);
		if(file_exists($file))
			return $file;
		
		// remove part after _
		if(strpos($locale, "_")>0)
			$locale = substr($locale,0,strpos($locale, "_"));
		
		$file = $this->getSerializedFilePathForLocale($locale);
		if(file_exists($file))
			return $file;
		
		return null;
	}
	
	private function getSerializedFilePathForLocale($locale) {
		return $this->locale_folder."/".$locale."/LC_MESSAGES/".$this->selected_domain.self::translatedFileAdding.self::serializedFileAdding;
	}
	
	public function _($orig_msg){
		if(!$this->is_loaded)
			$this->load();
			
		if($this->current_translations == false)
			return "##".$orig_msg."##";
			
		return $this->current_translations[$orig_msg];
	}
	
	public function createGettextPotFor($file) {
		// TODO: check permissions (fileperms)
		if(is_array($file))
			$file = implode(" ", $file);
		else if(file_exists("$this->locale_folder/$this->selected_domain.pot")) {
			$file = "--join-existing ".$file;
		}
		exec("xgettext --from-code=utf-8 -d $this->selected_domain -o $this->locale_folder/$this->selected_domain.pot $file");
	}

	public function compileAll(){
		if ($handle = opendir($this->locale_folder)) {
	    	while (false !== ($file = readdir($handle))) {
	    		$language_dir = $this->locale_folder."/".$file;
	        	if ($file != "." && $file != "..") {
	        		$messages_dir = $language_dir."/LC_MESSAGES/";
	        		if(is_dir($this->locale_folder."/".$file) && is_dir($messages_dir)) {
        	    		$this->compileLanguageDir($messages_dir);
        	    	}
        		}
    		}
    		closedir($handle);
		}
	}
	
	private function compileLanguageDir($dir) {
		if ($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) {
				$file_location = $dir."/".$file;
				if (is_file($file_location) && $file != "." && $file != "..") {
					$path_info = pathinfo($file);
					if($path_info['extension']=="po"){
						$this->compileFile($file_location);
					}
				}
			}
			closedir($handle);
		}
	}
	
	private function compileFile($file){
		$translations = array();
		$lines = file($file);
		foreach ($lines as $line_num => $line) {
			if(strlen($line) > 5 && substr_compare($line, "msgid", 0, 5)==0) {
				$id = $this->getValuePartOfGettext($line);
				$trans = $this->getValuePartOfGettext($lines[$line_num+1]);
				$translations[$id] = $trans;
			}
		}
		
		$this->serializeToFile($translations, $file.self::serializedFileAdding);
	}
	
	private function getValuePartOfGettext($line){
		$pos_first = strpos($line, '"')+1;
		$pos_last  = strrpos($line, '"');
		return substr($line, $pos_first, $pos_last - $pos_first);
	}

	private function serializeToFile($translations, $file) {
		if($f = @fopen($file,"w"))
        {
            if(@fwrite($f,serialize($translations)))
            {
                @fclose($f);
            }
            else die("Could not write to file ".$file." at Localization::serializeToFile");
        }
        else die("Could not open file ".$file." for writing, at Localization::serializeToFile");
	}
}

?>