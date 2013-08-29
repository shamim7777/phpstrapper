<?php
 

    function &getInstance ($class, $arg1=null)
    // implements the 'singleton' design pattern.
    {
        if (array_key_exists($class, $instances)) {
		
            // instance exists in array, so use it
            $instance =& $instances[$class];
            
        } else {
            // load the class file (if not already loaded)
            if (!class_exists($class)) {
                switch ($class) {
                    case 'Rest':			
                        require_once APP_PATH.'/system/rest/Rest.php';
							
                        break;
 
                    default:
                        require_once "classes/$class.class.inc";
                        break;
                } // switch
            } // if

            // instance does not exist, so create it
            $instances[$class] = new $class($arg1);
            $instance =& $instances[$class];
        } // if

        return $instance;

    } // getInstance
 


?>