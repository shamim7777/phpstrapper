<?php

class Models_User extends Application {

    public function getTestData($a) {

        $q = ( $this->db->query("SELECT `user_id` FROM `login_integration"));
        $count = $q->rowCount();
    }

    public function checkUserName($name) {

        $q = ( $this->db->query("SELECT `username` FROM `login_users` where username='$name'"));
        $count = $q->rowCount();
        return $count;
    }

    public function checkEmail($email) {
        $q = ( $this->db->query("SELECT `username` FROM `login_users` where email='$email'"));
        $count = $q->rowCount();
        // echo $count;
        return $count;
    }

    public function RegisterNewUser($userInfo) {
        $q = $this->db->insert('login_users', $userInfo);
        return $q;
    }

    public function ActivationKeyGeneraion($ActivationInfo) {

        $q = $this->db->insert('login_confirm', $ActivationInfo);
        return $q;
    }

    public function ActivationCheck($key) {

        $q = ( $this->db->select("login_confirm", "activation_key='$key'"));

        $email = $q[0][email];

        if (count($q) > 0) {

            $ActivationInfo['verified'] = 1;
            $res = $this->db->update('login_users', $ActivationInfo, "email='$email'");
        }
        return count($q);
    }

    public function UpdatePassword($UserInfo, $email) {

        $res = $this->db->update('login_users', $UserInfo, "email='$email'");

        return count($q);
    }

    public function PasswordKeyCheck($key) {

        $q = ( $this->db->select("login_confirm", "activation_key='$key'"));
        return $q[0];
    }

    public function getUserInfo($username) {
        $user = $this->db->select("login_users", "username='$username'");
        return $user[0];
    }

    public function getUserInfoByFBID($fbid) {
        $user = $this->db->select("login_users", "fbid='$fbid'");
        return $user[0];
    }

    public function getProfileInfo($userid) {

        $user = $this->db->run("SELECT u.user_id, u.verified, u.name, u.username, u.email, 
            u.timestamp, p.bio, p.location, p.avatar, p.website 
            FROM login_users as u LEFT JOIN login_profiles as p ON u.user_id = p.user_id 
            WHERE u.user_id='$userid'");

        return $user[0];
    }

    public function getUserIdFromUsername($username) {
        
        $user_id = $this->db->select("login_users", "username = '$username'", "user_id");
        
        return $user_id[0];
    }

    public function SetProfile($user_info) {
        $user_info['user_id'] = $this->db->lastid();
        $res = $this->db->insert('login_profiles', $user_info);
        return $res;
    }
    public function updateUser($user_info) {

        $user_id = $_SESSION['coderangers']['user_id'];

        $res = $this->db->update('login_users', $user_info['user'], "user_id='$user_id'");

        $q = $this->db->select("login_profiles", "user_id='$user_id'", '*');

        if (count($q) > 0) {
            $res = $this->db->update('login_profiles', $user_info['profile'], "user_id='$user_id'");
        } else {
            $user_info['profile']['user_id'] = $user_id;
            $res = $this->db->insert('login_profiles', $user_info['profile']);
        }

        return $res;
    }

    public function LoginLog($log) {

        $q = $this->db->insert('login_timestamps', $log);

        return $q;
    }

}
