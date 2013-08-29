<?php

class Controllers_Register extends RestController {

    public function get() {
        $db = $this->db;
        $this->generateToken();
        $this->template->show('header');
        $this->template->show('signup');
        $this->template->show('footer');

        //print_r($this->request['function']);
        exit();
    }
    public function facebook() {

        $this->template->show('header');
        $this->template->show('facebook');
        $this->template->show('footer');
        exit();
    }
    public function facebookcallback() {
 
      //  print_r($this->request['params']);

        $response = $this->parse_signed_request($this->request['params']['signed_request'],FACEBOOK_SECRET);

        //print_r($response);

        $name = $response["registration"]["name"];
        $email = $response["registration"]["email"];
        $username = str_replace('.com','',str_replace('@', '', $email));
        $password = $response["registration"]["password"];
        $fbid = $response["user_id"];

    
        $this->request['params']['name']= $name;
        $this->request['params']['username']= $username;
        $this->request['params']['email']= $email;
        $this->request['params']['password']= $password;
       
        $emailcheck = Models_User::checkEmail($email);
   
        if($emailcheck==0){
            $response = $this->create($fbid);
        
            if($response['status']==200){
             $info['message'] = "User registration is successfull, now you can login.";
             $info['class'] = "success";
            }
                  
        }else
        {
            $info['message'] = "Email address already registered. Try <a data-toggle='modal' class='btn' role='button' id='forgotpasswordlink' href='#forgotpassword' style='margin:10px 0'>Forgot password?</a> to retrieve your password.";
            $info['class'] = "danger";
    
        }
         
            $this->template->info =  $info;   
            $this->template->show('header');
            $this->template->show('alert');
            $this->template->show('footer');
            exit();
    }

    public function facebooklogin(){
      // Create our Application instance.
        $facebook = new Library_FB_Facebook(array(
          'appId'  => FACEBOOK_APP_ID,
          'secret' => FACEBOOK_SECRET,
          'cookie' => true,
        ));



        //Facebook Authentication part
        $user       = $facebook->getUser();
    
        
        $loginUrl   = $facebook->getLoginUrl(
        array(
                    'scope'         => 'email',
                    'redirect_uri'  => 'http://demo4.coderangers.com/register/facebooklogin/'
        ));

 

        $logoutUrl  = $facebook->getLogoutUrl();   


        if ($user){
     
                    $account_info = Models_User::getUserInfoByFBID($user);

                    $_SESSION['coderangers']['username'] = $account_info['username'];
                    $_SESSION['coderangers']['email'] = $account_info['email'];
                    $_SESSION['coderangers']['user_id'] = $account_info['user_id'];
                    $this->redirect('/home');
        }else
        {

            echo "<script>top.location.href = '$loginUrl';</script>";
   
        }

       die();

    }

  

    private function parse_signed_request($signed_request, $secret)
    {

        list($encoded_sig, $payload) = explode('.', $signed_request, 2);
        // decode the data
        $sig = $this->base64_url_decode($encoded_sig);



        $data = json_decode($this->base64_url_decode($payload), true);


        if (strtoupper($data['algorithm']) !== 'HMAC-SHA256')
        {
            error_log('Unknown algorithm. Expected HMAC-SHA256');
            return null;
        }

        // check sig
        $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
        if ($sig !== $expected_sig)
        {
          error_log('Bad Signed JSON signature!');
        }

        return $data;
    }

    private function base64_url_decode($input)
    {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    public function create($fbid) {

        $body = implode(', ', ($this->request['params']));

        unset($this->request['params']['terms']);
        unset($this->request['params']['passwordagain']);

        if($fbid!="")
        {
            $this->request['params']['fbid'] = $fbid; 
            $this->request['params']['verified'] = 1; 
        }
        else
        {
            $this->request['params']['name'] = $this->request['params']['username'];
        }

        $this->request['params']['user_level'] = 2;
        $this->request['params']['timestamp'] = date("Y-m-d H:i:s");
        $this->request['params']['password'] = $this->hashPassword($this->request['params']['password']);

        // $this->sendSMTPEmail("shamim7777@gmail.com","Welcome to Exling video archive.","<h3>Welcome to Exling video archive.</h3>".$body);
        $response = Models_User::RegisterNewUser($this->request['params']);
      
        if($fbid=="")
        {

                $data['username'] = $this->request['params']['username'];
                $data['email'] = $this->request['params']['email'];
                $data['activation_key'] = $this->hashPassword($this->request['params']['email'] . $this->request['params']['username']);
                $data['type'] = 'new_user';


                $mailresponse = Models_User::ActivationKeyGeneraion($data);

                $activation_url = BASE_URL . 'register/activation?key=' . $data['activation_key'];

                $shortcodes['subject'] = APP_NAME . " User Activation";
                $shortcodes['activate'] = "Activate";
                $shortcodes['baseurl'] = BASE_URL;
                $shortcodes['url'] = $activation_url;
                $shortcodes['appname'] = APP_NAME;
                $shortcodes['body'] = "Congratulations!!, You have successfully registered to " . APP_NAME . ". Please click on the acitivation link below.";

                $shortcodes['body'] .= "Alternatively you can do it by copying and pasting it in your browser: <br /><a href='" . $shortcodes['url'] . "'>" . $shortcodes['url'] . "</a>";

                $body = $this->template->email('email/basic');


                foreach ($shortcodes as $code => $value)
                    $body = str_replace('{{' . $code . '}}', $value, $body);


                if ($mailresponse)
                    $this->sendSMTPEmail($data['email'], APP_NAME . " User Activation", $body);
        }

        $this->response = array(
            "status" => 200,
            "dbprocess" => $response,
            "activationmail" => $mailresponse,
            "message" => "User registration is successfull, check email for activation"
        );

        $this->responseStatus = 200;

        if($fbid!="")
        {
           $this->setFBProfilePic($fbid);
           return $this->response; 
        }
    }

    private function setFBProfilePic($fbid){

        $fburl = "http://graph.facebook.com/$fbid/picture?type=large";
        $profile['avatar'] =  $fburl;
        
         
        return Models_User::SetProfile($profile);
    }

    public function activation() {

        $isActivated = Models_User::ActivationCheck($this->request['params']['key']);

        if ($isActivated) {
            $data['class'] = 'success';
            $data['message'] = "You have sucessfully activated your " . APP_NAME . " account. You can now <a href='/home'><strong class='warning'><span style='font-size:14px' class='label label-warning'>login</span></strong></a>";
            $this->template->activation = $data;
        } else {
            $data['class'] = 'danger';
            $data['message'] = 'Activation failed!!. Key does not exists.';
            $this->template->activation = $data;
        }
        $this->template->show('header');
        $this->template->show('activated');
        $this->template->show('footer');

        exit();
        $this->responseStatus = 200;
    }
    
    public function emailActivation() {

        $isActivated = Models_User::ActivationCheck($this->request['params']['key']);

        if ($isActivated) {
            $data['class'] = 'success';
            $data['message'] = "You have sucessfully validated your email address. You can now <a href='/home'><strong class='warning'><span style='font-size:14px' class='label label-warning'>login</span></strong></a>";
            $this->template->activation = $data;
        } else {
            $data['class'] = 'danger';
            $data['message'] = 'Validation failed!!. Email is not valid.';
            $this->template->activation = $data;
        }
        $this->template->show('header');
        $this->template->show('activated');
        $this->template->show('footer');

        exit();
    }

    public function usernamecheck() {
        $isExist = Models_User::checkUserName($this->request['params']['value']);

        $this->response = array(
            "value" => $_REQUEST["value"],
            "valid" => $isExist > 0 ? 0 : 1,
            "message" => "Username " . $_REQUEST["value"] . " already taken!"
        );

        $this->responseStatus = 200;
    }

    public function emailcheck() {

        $isExist = Models_User::checkEmail($this->request['params']['value']);
     
        $this->response = array(
            "value" => $_REQUEST["value"],
            "valid" => $isExist > 0 ? 0 : 1,
            "message" => "Email address " . $_REQUEST["value"] . " already taken!"
        );

        $this->responseStatus = 200;
    }

    public function emailexistance() {

        $isExist = Models_User::checkEmail($this->request['params']['value']);

        $this->response = array(
            "value" => $_REQUEST["value"],
            "valid" => $isExist,
            "class" => $isExist ? "success" : "danger",
            "message" => $isExist ? "Email address found" : "Email address does not exist",
        );

        $this->responseStatus = 200;
    }

    public function post() {
        $this->response = array('TestResponse' => 'I am POST response. Variables sent are - ' . http_build_query($this->request['params']));
        $this->responseStatus = 201;
    }

    public function put() {
        $this->response = array('TestResponse' => 'I am PUT response. Variables sent are - ' . http_build_query($this->request['params']));
        $this->responseStatus = 200;
    }

    public function delete() {
        $this->response = array('TestResponse' => 'I am DELETE response. Variables sent are - ' . http_build_query($this->request['params']));
        $this->responseStatus = 200;
    }

}
