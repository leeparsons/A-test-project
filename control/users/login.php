<?php

    class login extends controller {

        public $error = array();
        
        public function index() {

            if (isset($_REQUEST['redirect'])) {
                $this->redirectUrl = urlencode(stripslashes($_REQUEST['redirect']));
            } elseif (isset($_REQUEST['redirectUrl'])) {
                $this->redirectUrl = urlencode(stripslashes($_REQUEST['redirectUrl']));
            } else {
                $this->redirectUrl = '';   
            }
            $this->action = $this->url . 'users/login/loginsubmit/';
            $this->register = $this->url . 'users/login/register/';
            $this->error = false;

            $this->forgottenLink = $this->url . 'users/reminder/?r=' . $this->redirectUrl;
            
        }        
        
        
        public function loginsubmit() {

            $data = $this->request('post');
            $this->register = $this->url . 'users/login/register/';

            if (isset($data['email']) && strlen($data['email']) > 0 && isset($data['password']) && strlen($data['password']) > 0) {
                
                //check that the user combination exists:
                
                
                Loader::model('user/status');
                
                if (!$loginInfo = statusModel::checkedDetails(stripslashes($data['email']), stripslashes($data['password']))) {
                    
                    $this->error = true;
                    return;
                } else {
                    
                    //set the user login information in the cookie!

                    statusModel::setLogin($loginInfo[0]['id']);
                    
                    if (isset($data['redirect']) && strlen($data['redirect']) > 0) {
                        $this->redirect(urldecode(stripslashes($data['redirect'])));
                    }
                    $this->redirect();   
                }
                
                
            } else {
                if (isset($data['redirect']) && strlen($data['redirect']) > 0) {
                    $this->redirectUrl = stripslashes($data['redirect']);
                } else {
                    $this->redirectUrl = '';   
                }
                $this->forgottenLink = $this->url . 'users/reminder/?r=' . $data['redirect'];
                $this->error = true;
                $this->action = $this->url . 'users/login/loginsubmit/';

            
            }
            
        }
        
        public function register() {
            $data = $this->request('post');

            if (isset($data['redirecturl']) && strlen($data['redirecturl']) > 0) {
                $this->redirectUrl = urldecode($data['redirecturl']);   
            } else {
                $this->redirectUrl = '';   
            }

            $this->action = $this->url . 'users/login/doregister/';
            
            $this->template = 'register';
            
            //redirectUrl may have split up everything.
            
        }
        
        public function doregister() {
            $data = $this->request('post');   

            if (!isset ($data['name']) || strlen($data['name']) < 1) {
             
                $this->error[] = 'Please enter your name.';
                $this->name = '';
                
            } else {
                $this->name = stripslashes($data['name']);             
            }
            
            
            if (!isset ($data['email']) || strlen($data['email']) < 1) {
                
                $this->error[] = 'Please enter valid email address.';
                $this->email = '';
            } elseif (!$this->validateEmail($data['email'])) {
                $this->email = stripslashes($data['email']);
                $this->error[] = 'Please enter valid email address.';
            } else {
                $this->email = stripslashes($data['email']);             
            }
            
            
            if (!isset($data['password1']) || strlen($data['password1']) < 1) {
                $this->error[] = 'Please enter a password.';
                $this->password1 = '';
            } else {
                $this->password1 = stripslashes($data['password1']);
            }

            if (isset($data['password2']) && strlen($data['password2']) > 0) {
                $this->password2 = stripslashes($data['password2']);
            } else {
                $this->password2 = '';
            }
            
            
            if ($this->password1 !== $this->password2) {
                $this->error[] = 'Please make sure your passwords are the same.';   
            }
            
            if (!isset($data['reminderq']) || strlen($data['reminderq']) < 1) {
                $this->error[] = 'Please enter a reminder question for your password.';
                $this->reminderq = '';
            } else {
                $this->reminderq = stripslashes($data['reminderq']);
            }
            
            
            if (!isset($data['remindera']) || strlen($data['remindera']) < 1) {
                $this->error[] = 'Please enter a reminder answer for your password.';
                $this->remindera = '';
            } else {
                $this->remindera = stripslashes($data['remindera']);
            }
            

            
            if (isset($data['redirect'])) {
                $this->redirectUrl = $data['redirect'];
            } else {
                $this->redirectUrl = '';   
            }
            
            
            if (empty($this->error)) {
             
                $this->data = array(
                                    'name'              =>  $this->name,
                                    'password'          =>  md5($this->password1),
                                    'normalpassword'    =>  $this->password2,
                                    'reminder'          =>  $this->reminderq,
                                    'reminderanswer'    =>  $this->remindera,
                                    'email'             =>  trim($this->email),
                                    );
                
                Loader::model('user/status');
                
                if ($id = statusModel::create($this->name, md5($this->password1), $this->password2, $this->reminderq, $this->remindera, trim($this->email))) {
                    //create the user
                    
                    //now create the login session
                    
                    
                    statusModel::setLogin($id);
                    
                    if ($this->redirectUrl !== '') {
                        $this->redirect(urldecode($this->redirectUrl));
                    } else {
                        $this->redirect();
                    }
                    
                } else {
                    $this->error[] = 'That email address is already in use. Please login to continue. <a href="' . $this->url . 'users/login/">Click here to login</a>';
                }
                
            }
            
            
            $this->template = 'register';
        }
        

        
        protected function validateEmail($email) {
            // First, we check that there's one @ symbol, 
            // and that the lengths are right.
            
            
            
            if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
                // Email invalid because wrong number of characters 
                // in one section or wrong number of @ symbols.
                return false;
            }
            // Split it into sections to make life easier
            $email_array = explode("@", $email);
            $local_array = explode(".", $email_array[0]);
            for ($i = 0; $i < sizeof($local_array); $i++) {
                if
                    (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&
                           ↪'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$",
                           $local_array[$i])) {
                        return false;
                    }
            }
            // Check if domain is IP. If not, 
            // it should be valid domain name
            if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
                $domain_array = explode(".", $email_array[1]);
                if (sizeof($domain_array) < 2) {
                    return false; // Not enough parts to domain
                }
                for ($i = 0; $i < sizeof($domain_array); $i++) {
                    if
                        (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|
                               ↪([A-Za-z0-9]+))$",
                               $domain_array[$i])) {
                            return false;
                        }
                }
            }
            return true;
        }
        
        
    }