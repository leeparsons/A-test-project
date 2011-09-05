<?php
    
    class login extends controller {

        public $usr = '';
        public $pss = '';
        public $message = '';
        protected $admLogged = false;
        protected $checkedLogin = false;
        
        public function index() {
            
            Loader::model('user/loginauth');
            
            if ($this->checkedLogin === false) {
                $this->admLogged = loginauthModel::isLoggedIn();
            }
            
            
            //figure out if the user is logged in, otherwise show them the login page
            if ($this->admLogged === true) {
                $this->redirectAdm('dashboard');
            } else {

                $this->template = 'loginform';
                $this->css[] = $this->admUrl . 'css/login.css';

                $this->action = $this->admUrl . 'login/login/auth';
            }

        }
        
        public function auth() {
            Loader::model('user/loginauth');

            $this->admLogged = loginauthModel::isLoggedIn();
            $this->checkedLogin = true;

            if ($this->admLogged === true) {

                $this->redirectAdm('dashboard');
            } else {

                $data = $this->request('post');
                
                if (isset($data['usr'])) {
                    $this->usr = $data['usr'];   
                }
                
                if (isset($data['pss'])) {
                    $this->pss = $data['pss'];   
                }
                
                //figure out if the user credentials match those in the db

                $uid = loginauthModel::isUser($this->usr, $this->pss);

                if (!$uid) {
                    $this->message = '<p class="err">Please enter the correct user name and password combination.</p>';   
                    $this->index();
                    return;
                }
                
                
                loginauthModel::Hello($uid['id']);

                $this->redirectAdm('dashboard');
                
            }
            
            
            
        }
        
        public function logout() {
         
            Loader::model('user/loginauth');
            loginauthModel::logout();
            
            $this->redirectAdm();
            
        }
    
    }