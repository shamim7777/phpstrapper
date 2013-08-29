<?php

/**
 * Establish a mySQL connection and select a database.
 *
 * @author       Shamim <shamim@coderangers.com>
 * @copyright    Copyright ©2013 coderangers llc.
 */
include(APP_PATH . "/system/drivers/pdo/class.db.php");

class Connect {

    private $error;
    public static $dbh;

    /**
     * Checks if installation is complete by seeing if config.php exists.
     *
     * The user will be prompted to visit home.php and click "Begin Install" if
     * there is no config.php yet setup. This prompt will be persistent and won't
     * allow any pages to load until config.php is created.
     *
     * @return    string    The error message if an install does not exist.
     */
    public function checkInstall() {


        if (!file_exists(APP_PATH . '/config/config.php')) :

            return "<div class='alert alert-warning'>" . _('Installation has not yet been ran!') . "</div>
                                        <h1>" . _('Woops!') . "</h1>
                                        <p>" . _('You\'re missing a config.php file preventing a database connection from being made.') . "</p>
                                        <p>" . _('Please click') . " <a href='home.php'>" . _('Begin Install') . "</a> " . _('on the home page to create a config file.') . "</p>
                                        ";

        endif;
    }

    /**
     * Connect to mySQL and select a database.
     *
     * The credentials used to connect to the database are pulled from /classes/config.php.
     *
     * @return    string    Error message for any incorrect database connection attempts.
     */
    public function getConn() {
        try {
            $dbh = new db("mysql:host=" . HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            return $dbh;
        } catch (PDOException $e) {
            return '<div class="alert alert-error">' . _('Database error: ') . $e->getMessage() . '</div>';
        }
    }

}

// Instantiate the Connect class
$connect = new Connect();