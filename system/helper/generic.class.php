<?php
/**
 * Generic functions used throughout the script.
 *
 *
 * @author       Shamim <shamim@coderangerss.com>
 * @copyright    Copyright ©2013 coderangerss llc.
 */
date_default_timezone_set('UTC');

class Generic extends Connect {
 
    private $error;
    private $title;
    private $description;
    private $keywords;

    function __construct() {

        // Check to make sure install is complete
        $this->error = parent::checkInstall();


        include( APP_PATH . '/system/helper/prereqs.php' );
        $this->error = !empty($error) ? $error : $this->error;


        $this->definePaths();

        // Check if an upgrade is required
        //if(empty($this->error)) include_once( 'upgrade.class.php' );
        // Check for any errors and quit if there are
        $this->displayMessage($this->error);
    }

    public function setTitle($title){
        $this->title = $title;
    }
    public function getTitle(){
        return $this->title;
    }
    public function setMetaDescription($description){
        $this->description = $description;
    }
    public function getMetaDescription(){
        return $this->description;
    }

    public function setMetaKeywords($keywords){
        $this->keywords = $keywords;
    }
    public function getMetaKeywords(){
        return $this->keywords;
    }  

    /**
     * Returns a mySQL query.
     *
     * @param     string      $query    An SQL statement.
     * @param     array       $params   The binded variables to an SQL statement.
     * @return    resource    Returns the query's execution.
     */
    public function query($query, $params = array()) {

        if (!is_array($params))
            return false;

        $dbh = parent::$dbh;

        if (empty($dbh))
            return false;

        $stmt = $dbh->prepare($query);
        $stmt->execute($params);

        return $stmt;
    }

    /**
     * Redirects
     */
    public function redirect($url) {

        header("Location:" . $url);
    }

    /**
     * Retrieves an option value based on option name.
     *
     * @param     string    $option    Name of option to retrieve.
     * @param     bool      $check     Whether the option is a checkbox.
     * @param     bool      $profile   Whether to return a profile field, or an admin setting.
     * @param     int       $id        Required if profile is true; the user_id of a user.
     * @return    string    The option value.
     */
    public function getOption($option, $check = false, $profile = false, $id = '') {

        if (empty($option))
            return false;

        $option = trim($option);

        if ($profile) {
            $params = array(
                ':option' => $option,
                ':id' => $id
            );
            $sql = "SELECT `profile_value` FROM `login_profiles` WHERE `pfield_id` = :option AND `user_id` = :id LIMIT 1;";
        } else {
            $params = array(':option' => $option);
            $sql = "SELECT `option_value` FROM `login_settings` WHERE `option_name` = :option LIMIT 1;";
        }

        $stmt = $this->query($sql, $params);

        if (!$stmt)
            return false;

        $result = $stmt->fetch(PDO::FETCH_NUM);
        $result = $result ? $result[0] : false;

        if ($check)
            $result = !empty($result) ? 'checked="checked"' : '';

        return $result;
    }

    /**
     * Updates an option in the database.
     *
     * If an option exists in the database, it will be updated. If it does not exist,
     * the option will be created.
     *
     * @param     string    $option      Name of option to retrieve.
     * @param     bool      $newvalue    Option's new value to set.
     * @param     bool      $profile     Whether to update a profile field, or an admin setting.
     * @param     int       $id          Required if profile is true; the user_id of a user.
     * @return    bool      Whether the update was successful or not.
     */
    public function updateOption($option, $newvalue, $profile = false, $id = '') {

        $option = trim($option);
        if (empty($option) || !isset($newvalue))
            return false;


        $oldvalue = $profile ? $this->getOption($option, false, true, $id) : $this->getOption($option);

        if ($newvalue === $oldvalue)
            return false;

        $params = array(
            ':option' => $option,
            ':newvalue' => is_array($newvalue) ? serialize($newvalue) : $newvalue
        );

        if (false === $oldvalue) :

            if ($profile) {
                $params[':id'] = $id;
                $sql = "INSERT INTO `login_profiles` (`user_id`, `pfield_id`, `profile_value`) VALUES (:id, :option, :newvalue);";
            }
            else
                $sql = "INSERT INTO `login_settings` (`option_name`, `option_value`) VALUES (:option, :newvalue)";

            return $this->query($sql, $params);
        endif;

        if ($profile) {
            $params[':id'] = $id;
            $sql = "UPDATE `login_profiles` SET `profile_value` = :newvalue WHERE `pfield_id` = :option AND `user_id` = :id";
        } else {
            $sql = "UPDATE `login_settings` SET `option_value` = :newvalue WHERE `option_name` = :option";
        }

        return $this->query($sql, $params);
    }

    /**
     * Sanitizes titles intended for SQL queries.
     *
     * Specifically, HTML and PHP tag are stripped. The return value
     * is not intended as a human-readable title.
     *
     * @param     string    $title    The string to be sanitized.
     * @return    string    The sanitized title.
     */
    public function sanitize_title($title) {

        $title = strtolower($title);
        $title = preg_replace('/&.+?;/', '', $title); // kill entities
        $title = str_replace('.', '-', $title);
        $title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
        $title = preg_replace('/\s+/', '-', $title);
        $title = preg_replace('|-+|', '-', $title);
        $title = trim($title, '-');

        return $title;
    }

    public function getProfilePic($pic) {

        if ($pic == "")
            return BASE_URL . 'assets/uploads/profile/default.jpg';
        else if ($this->contains($pic, 'graph.facebook.com'))
            return $pic;
        else
            return BASE_URL . 'assets/uploads/profile/' . $pic;
    }

    public function getProfileThumbPic($pic) {

        if ($pic == "")
            return BASE_URL . 'assets/uploads/profile/thumb/default.jpg';
        else if ($this->contains($pic, 'graph.facebook.com'))
            return $pic;
        else
            return BASE_URL . 'assets/uploads/profile/thumb/' . $pic;
    }

    public function contains($str, $value) {

        if (strpos($str, $value) !== FALSE)
            return true;
        else
            return false;
    }

    public function sendSMTPEmail($to, $subject, $body) {

        include("Mail.php");
        include('Mail/mime.php');

        $from = APP_EMAIL_FROM;
        $host = EMAIL_HOST;
        $port = EMAIL_PORT;
        $username = EMAIL_USERNAME;
        $password = EMAIL_PASSWORD;
        $crlf = "\n";

        $headers = array('From' => $from,
            'To' => $to,
            'Subject' => $subject);

        // Creating the Mime message
        $mime = new Mail_mime($crlf);

        // Setting the body of the email
        $mime->setTXTBody($body);
        $mime->setHTMLBody($body);

        $body = $mime->get();
        $headers = $mime->headers($headers);

        $smtp = Mail::factory('smtp', array('host' => $host,
                    'auth' => true,
                    'port' => $port,
                    'username' => $username,
                    'password' => $password));


        $mail = $smtp->send($to, $headers, $body);

        if (PEAR::isError($mail)) {
            //	echo("<p>" . $mail->getMessage() . "</p>");
            return false;
        } else {
            return true;
        }
    }

    /**
     * Sends HTML emails with optional shortcodes.
     *
     * @param     string    $to            Receiver of the mail.
     * @param     string    $subj          Subject of the email.
     * @param     string    $msg           Message to be sent.
     * @param     array     $shortcodes    Shortcode values to replace.
     * @param     bool      $bcc           Whether to send the email using Bcc: rather than To:
     *                                     Useful when sending to multiple recepients.
     * @return    bool      Whether the mail was sent or not.
     */
    public function sendEmail($to, $subj, $msg, $shortcodes = '', $bcc = false) {

        if (!empty($shortcodes) && is_array($shortcodes)) :

            foreach ($shortcodes as $code => $value)
                $msg = str_replace('{{' . $code . '}}', $value, $msg);

        endif;

        /* Multiple recepients? */
        if (is_array($to))
            $to = implode(', ', $to);

        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $headers .= 'From: ' . address . "\r\n";

        /* BCC address. */
        if ($bcc) {
            $headers .= 'Bcc: ' . $to . "\r\n";
            $to = null;
        }

        $headers .= 'Reply-To: ' . address . "\r\n";
        $headers .= 'Return-Path: ' . address . "\r\n";

        /*
         * If running postfix, need a fifth parameter since Return-Path doesn't always work.
         */
        // $optionalParams = '-r' . address;

        return mail($to, $subj, nl2br(html_entity_decode($msg)), $headers, $optionalParams);
    }

    /**
     * Checks if a user has access to view their own access log
     *
     * @return    bool    Whether the user can view access logs or not
     */
    public function denyAccessLogs() {

        return ( ($this->getOption('profile-timestamps-admin-enable') && !in_array(1, $_SESSION['coderangers']['user_level'])) || !$this->getOption('profile-timestamps-enable') );
    }

    /** Generates the access logs for a particular user in table format */
    public function generateAccessLogs() {

        $user_id = $this->getField('user_id');

        $params = array(':user_id' => $user_id);
        $sql = "SELECT `ip`, `timestamp` FROM `login_timestamps` WHERE `user_id` = :user_id ORDER BY `timestamp` DESC LIMIT 0,10";
        $stmt = $this->query($sql, $params);
        ?>
        <table class="table table-condensed span6">
            <thead>
                <tr>
                    <th><?php _e('Last Login'); ?></th>
                    <th><?php _e('Location'); ?></th>
                </tr>
            </thead>
            <tbody>
        <?php if ($stmt->rowCount() > 0) : ?>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                        <tr>
                            <td><?php echo date('M d, Y', strtotime($row['timestamp'])) . ' ' . _('at') . ' ' . date('h:i a', strtotime($row['timestamp'])); ?></td>
                            <td><?php echo $row['ip']; ?></td>
                        </tr>
            <?php endwhile; ?>
        <?php else : ?>
                    <tr>
                        <td><?php _e('Has not logged in yet'); ?></td>
                    </tr>
        <?php endif; ?>
            </tbody>
        </table>
                <?php
            }

            /**
             * Only allows guests to view page.
             *
             * A logged in user will be shown an error and denied from viewing the page.
             */
            public function guestOnly() {

                if (!empty($_SESSION['coderangers']['username'])) {
                    $this->error = "
							<div class='alert alert-error'>" . _('You\'re already logged in.') . "</div>
							<h5>" . _('What to do now?') . "</h5>
							<p>" . sprintf(_('Go <a href="%s">back</a> to the page you were viewing before this.'), 'javascript:history.go(-1)') . "</p>
							";
                }

                $this->displayMessage($this->error);
            }

            /**
             * Check if the user is logged in or not
             *
             * 
             */
            public function isLoggedIn() {
                if (!empty($_SESSION['coderangers']['username']))
                    return true;
                else
                    return false;
            }

            /**
             * Validate request with token.
             *
             * Intended for form validation to prevent exploit attempts.
             */
            public function tokenCheck($token) {

                if (empty($_SESSION['coderangers']['token']) || ($_SESSION['coderangers']['token'] != $token)) {
                    $response = array(
                        "status" => 401,
                        "success" => false,
                        "class" => 'danger',
                        "message" => "Exploit attempts detected and notified to admin."
                    );

                    echo json_encode($response);
                    die();
                }
                return true;
            }

            /**
             * Generates a new unique token.
             *
             * Intended for form validation to prevent exploit attempts.
             */
            public function generateNewToken() {

                $_SESSION['coderangers']['token'] = md5(uniqid(mt_rand(), true));
                return $_SESSION['coderangers']['token'];
            }

            /**
             * Generates a unique token.
             *
             * Intended for form validation to prevent exploit attempts.
             */
            public function generateToken() {

                if (empty($_SESSION['coderangers']['token']))
                    $_SESSION['coderangers']['token'] = md5(uniqid(mt_rand(), true));
            }

            /**
             * Prevents invalid form submission attempts.
             *
             * @param     string    $token    The POST token with a form.
             * @return    bool      Whether the token is valid.
             */
            public function valid_token($token) {

                if (empty($_SESSION['coderangers']['token']))
                    return false;

                if ($_SESSION['coderangers']['token'] != $token)
                    return false;

                return true;
            }

            static function sessionStart($name, $limit = 0, $path = '/', $domain = null, $secure = null) {
                // Set the cookie name
                session_name($name . '_coderangersS');

                // Set SSL level
                $https = isset($secure) ? $secure : isset($_SERVER['HTTPS']);

                // Set session cookie options
                session_set_cookie_params($limit, $path, $domain, $https, true);
                session_start();

                // Make sure the session hasn't expired, and destroy it if it has
                if (self::validateSession()) {
                    // Check to see if the session is new or a hijacking attempt
                    if (!self::preventHijacking()) {
                        // Reset session data and regenerate id
                        $_SESSION = array();
                        $_SESSION['IPaddress'] = $_SERVER['REMOTE_ADDR'];
                        $_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
                        self::regenerateSession();

                        // Give a 5% chance of the session id changing on any request
                    } elseif (rand(1, 100) <= 5) {
                        self::regenerateSession();
                    }
                } else {
                    $_SESSION = array();
                    session_destroy();
                    session_start();
                }
            }

            /**
             * Secures any string intended for SQL execution.
             *
             * @param     string    $string
             * @return    string    The secured value string.
             */
            public function secure($string) {

                // Because some servers still use magic quotes
                if (get_magic_quotes_gpc()) :

                    if (!is_array($string)) :
                        $string = htmlspecialchars(stripslashes(trim($string)));
                    else :
                        foreach ($string as $key => $value) :
                            $string[$key] = htmlspecialchars(stripslashes(trim($value)));
                        endforeach;
                    endif;

                    return $string;

                endif;


                if (!is_array($string)) :
                    $string = htmlspecialchars(trim($string));
                else :
                    foreach ($string as $key => $value) :
                        $string[$key] = htmlspecialchars(trim($value));
                    endforeach;
                endif;

                return $string;
            }

            /**
             * Validates an email address.
             *
             * @param     string    $email    The email address.
             * @return    bool      Whether the email address is valid or not.
             */
            public function isEmail($email) {

                if (!empty($email))
                    $email = (string) $email;
                else
                    return false;

                return(preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i", $email));
            }

            /**
             * Defines variables used throughout the script.
             *
             * Definitions:
             * cINC                   The current directory, whether /admin/ or root.
             * address                Administrator's email address.
             * SITE_PATH              Should be set with a trailing slash, where activate.php is located.
             * phplogin_db_version    The current script's database version.
             *                        Used for keeping track of necessary db updates.
             *                        Follows format - Year : Month : Day : Revision.
             * phplogin_version       Core version of the script.
             */
            public function definePaths() {

                if (!defined('cINC'))
                    define('cINC', dirname($_SERVER['SCRIPT_FILENAME']) . '/');
                if (!defined('address'))
                    define('address', $this->getOption('admin_email'));
                if (!defined('SITE_PATH'))
                    define('SITE_PATH', $this->getOption('site_address'));
                if (!defined('phplogin_db_version'))
                    define('phplogin_db_version', 1206210);
                if (!defined('phplogin_version'))
                    define('phplogin_version', 3.02);
            }

            /**
             * Hashes a password for either MD5 or SHA256.
             *
             * If hashing SHA256, a unique salt will be hashed with it.
             *
             * @param     string    $password    A plain-text password.
             * @return    string    Hashed password.
             */
            public function hashPassword($password) {

                $type = APP_PASS_ENCRYPTION;

                //$type = $this->getOption('pw-encryption');
                // Checks if the pw should be MD5, if so, don't continue
                if ($type == 'MD5')
                    return md5($password);

                $salt = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
                $hash = hash($type, $salt . $password);
                $final = $salt . $hash;

                return $final;
            }

            /**
             * Validates a password.
             *
             * A plain-text password is compared against the hashed version.
             *
             * @param     string    $password       A plain-text password.
             * @param     string    $correctHash    The hashed version of a correct password.
             * @return    bool      Whether or not the plain-text matches the correct hash.
             */
            public function validatePassword($password, $correctHash) {

                $type = $APP_PASS_ENCRYPTION;
                $password = (string) $password;

                // Checks if the password is MD5 and return
                if (strlen($correctHash) == 32)
                    return md5($password) === $correctHash;
                else
                    $type = 'SHA256';

                // Continue testing the hash against the salt
                $salt = substr($correctHash, 0, 64);
                $validHash = substr($correctHash, 64, 64);
                $testHash = hash($type, $salt . $password);
                return $testHash === $validHash;
            }

            /**
             * Displays an error and optionally quits the script.
             *
             * @param     string    $error    The error message to display.
             * @param     bool      $exit     Whether to exit after the error and prevent the
             *                                page from loading any further.
             */
            public function displayMessage($error, $exit = true) {

                if (!empty($error)) :

                    // Current headers
                    //include_once(cINC . 'header.php');
                    // The error itself
                    echo $error;

                    // Shall we exit or not?
                    if ($exit) {
                        //	include_once(cINC . 'footer.php');
                        exit();
                    }

                endif;
            }

            /**
             * Ajax validation.
             *
             * Used on forms that check for duplicate email, username, or level.
             */
            public function checkExists() {

                if (!empty($_POST['email']) && !empty($_POST['checkemail'])) {
                    $params = array(':email' => $_POST['email']);
                    $sql = "SELECT `email` FROM `login_users` WHERE `email` = :email";
                } else if (!empty($_POST['username']) && !empty($_POST['checkusername'])) {
                    $params = array(':username' => $_POST['username']);
                    $sql = "SELECT `username` FROM `login_users` WHERE `username` = :username";
                } else if (!empty($_POST['auth']) && !empty($_POST['checklevel'])) {
                    $params = array(':auth' => $_POST['auth']);
                    $sql = "SELECT `level_level` FROM `login_levels` WHERE `level_level` = :auth";
                }
                else
                    return false;

                $stmt = $this->query($sql, $params);
                echo ( $stmt->rowCount() > 0 ) ? "false" : "true";
                exit();
            }

            /**
             * Finds the current IP address of a visiting user.
             *
             * @return    string    The IP address
             */
            public function getIPAddress() {

                if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) :
                    $ipAddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
                else :
                    $ipAddress = isset($_SERVER["HTTP_CLIENT_IP"]) ? $_SERVER["HTTP_CLIENT_IP"] : $_SERVER["REMOTE_ADDR"];
                endif;

                return $ipAddress;
            }

            /**
             * Get either a Gravatar URL or complete image tag for a specified email address.
             *
             * @param string $email The email address
             * @param string $s Size in pixels, defaults to 80px [ 1 - 512 ]
             * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
             * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
             * @param boole $img True to return a complete IMG tag False for just the URL
             * @param array $atts Optional, additional key/value attributes to include in the IMG tag
             * @return String containing either just a URL or a complete image tag
             * @source http://gravatar.com/site/implement/images/php/
             */
            public function get_gravatar($email, $img = false, $s = 80, $d = 'mm', $r = 'g', $atts = array()) {
                $url = 'http://www.gravatar.com/avatar/';
                $url .= md5(strtolower(trim($email)));
                $url .= "?s=$s&d=$d&r=$r";
                if ($img) {
                    $url = '<img class="gravatar thumbnail" src="' . $url . '"';
                    foreach ($atts as $key => $val)
                        $url .= ' ' . $key . '="' . $val . '"';
                    $url .= ' />';
                }
                return $url;
            }

            /**
             * Takes array of shortcodes and replace the shortcodes in html with these values
             * 
             * @author Fayyaaz Mushtaque <fayyaaz@coderangers.com>
             * @param array $shortcodes
             * @param string $html
             * @return string
             */
            public function scodeParser($shortcodes, $html) {
                foreach ($shortcodes as $code => $value)
                    $html = str_replace('{{' . $code . '}}', $value, $html);

                return $html;
            }

            /**
             * Unlinks the given file
             * @author Fayyaaz Mushtaque <fayyaaz@coderangers.com>
             * @param string $file
             * @return boolean
             */
            public function unlinkFile($file) {

                return unlink($file);
            }

            /**
             * Returns the basename & extension of a file
             * @author Fayyaaz Mushtaque <fayyaaz@coderangers.com>
             * @param string $file
             * @return array
             */
            public function getFileInfo($file) {

                $ext = pathinfo($file, PATHINFO_EXTENSION);
                $filename = basename($file, '.' . $ext);

                return array('basename' => $filename, 'extension' => $ext);
            }

            /**
             * Returns the user's browser info
             * @author Fayyaaz Mushtaque <fayyaaz@coderangers.com>
             * @return array
             */
            public function getBrowser() {

                static $browser;
                if (!isset($browser)) {
                    $browser = get_browser($_SERVER['HTTP_USER_AGENT']);
                }
                return $browser;
            }

            /**
             * Returns the UUID
             * @author Shamim Ahmed <shamim@coderangers.com>
             * @return string
             */
            public function generate_uuid() {
                return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
                );
            }

            /**
             * Shorten the title
             * @author Shamim Ahmed <shamim@coderangers.com>
             * @return string
             */
            function substr_title($title) {
                if (strlen($title) > 60) {
                    $title = substr($title, 0, 60);
                    $title = trim(substr($title, 0, strrpos($title, " "))) . " ... ";
                }

                return $title;
            }

            /**
             * Returns browser name
             * @author Shamim Ahmed <shamim@coderangers.com>
             * @return string
             */
            function browser() {
                $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
                // you can add different browsers with the same way ..
                if (preg_match('/(chromium)[ \/]([\w.]+)/', $ua))
                    $browser = 'chromium';
                elseif (preg_match('/(chrome)[ \/]([\w.]+)/', $ua))
                    $browser = 'chrome';
                elseif (preg_match('/(safari)[ \/]([\w.]+)/', $ua))
                    $browser = 'safari';
                elseif (preg_match('/(opera)[ \/]([\w.]+)/', $ua))
                    $browser = 'opera';
                elseif (preg_match('/(msie)[ \/]([\w.]+)/', $ua))
                    $browser = 'msie';
                elseif (preg_match('/(mozilla)[ \/]([\w.]+)/', $ua))
                    $browser = 'mozilla';

                preg_match('/(' . $browser . ')[ \/]([\w]+)/', $ua, $version);

                return array($browser, $version[2], 'name' => $browser, 'version' => $version[2]);
            }

            /**
             * Returns the time ago
             * @author Shamim Ahmed <shamim@coderangers.com>
             * @return string
             */
            function ago($date) {

                $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
                $lengths = array("60", "60", "24", "7", "4.35", "12", "10");
                $now = date();
                $difference = strtotime($now) - strtotime($date);

                $tense = "ago";
                for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
                    $difference /= $lengths[$j];
                }
                $difference = round($difference);
                if ($difference != 1) {

                    $periods[$j].= "s";
                }
                return "$difference $periods[$j] ago ";
            }

        }

        $generic = new Generic();