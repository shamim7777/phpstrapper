<?php

Class Template {
    /*
     * @the registry
     * @access private
     */

    private $registry;

    /*
     * @Variables array
     * @access private
     */
    private $vars = array();

    private $app;
    /**
     *
     * @constructor
     *
     * @access public
     *
     * @return void
     *
     */
    function __construct($registry,$app) {
        $this->registry = $registry;
        $this->app = $app;
        initLocalization();
    }

    /**
     *
     * @set undefined vars
     *
     * @param string $index
     *
     * @param mixed $value
     *
     * @return void
     *
     */
    public function __set($index, $value) {
        $this->vars[$index] = $value;
    }

    function show($name) {

        

        $path = APP_PATH . '/app/Views' . '/' . $name . '.php';

        if (file_exists($path) == false) {
            throw new Exception('Template not found in ' . $path);
            return false;
        }

        // Load variables
        foreach ($this->vars as $key => $value) {
            $$key = $value;
        }

        $this->app->setTitle(SITE_TITLE);       
        $title =$this->app->getTitle();

        $this->app->setMetaDescription(META_DESCRIPTION);   
        $description =$this->app->getMetaDescription();

        $this->app->setMetaKeywords(SITE_KEYWORDS); 
        $keywords =$this->app->getMetaKeywords();

        include ($path);
    }

    function email($name) {

        $path = APP_PATH . '/app/Views' . '/' . $name . '.php';

        if (file_exists($path) == false) {
            throw new Exception('Template not found in ' . $path);
            return false;
        }

        // Load variables
        foreach ($this->vars as $key => $value) {
            $$key = $value;
        }

        return file_get_contents($path);
    }

}

    $loc = null;

    function initLocalization(){


        if($_SESSION['lang']=="" || $_SESSION['lang']=="en" )
        $lang = 'en_EN';

        if($_SESSION['lang']=="bd")
        $lang = 'bd_BD';      
 
        global $loc;
        $loc = new Library_Localization_Object;
        $loc->setDomain("video");
        $loc->setLocale($lang); 
        $loc->compileAll();

    }

    function _l($t){
        global $loc;

        return $loc->_($t);
    }

?>
