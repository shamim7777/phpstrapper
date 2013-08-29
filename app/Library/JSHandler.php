<?php

/**
 * shamim@coderangers.com
 *
 * PHP version 5
 *
 * Copyright (c) 2013 Shamim Ahmed
 *
 *  
 */
Class Library_JSHandler {
   

    const VERSION = '1.0';

    public function __construct() {
      
    }

    private function addScriptTag($jsfile) {

        return '<script src="'.BASE_URL.'assets/js/'.$jsfile.'"></script>';
    }

    public function jQuery() {
        echo Library_JSHandler::addScriptTag('jquery-1.8.3.min.js');
    }

    public function FileUploader() {
        echo Library_JSHandler::addScriptTag('fileuploader/jquery.fineuploader.js');
    }
    public function DataTables($pagination) {

        echo Library_JSHandler::addScriptTag('jquery.dataTables.min.js');
        if($pagination)
        echo Library_JSHandler::addScriptTag('jquery.dataTables.bootstrap.js');
    }
    public function Chosen() {
        echo Library_JSHandler::addScriptTag('chosen/chosen.jquery.js');
    }
   
}
 