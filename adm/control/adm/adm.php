<?php
    
    class adm extends controller {

        
        
        public function index() {
            
            //figure out if the user is logged in, otherwise show them the login page!

            Loader::model('user/loginauth');
            
            
            if (!loginauthModel::isLoggedIn()) {
                
                
                $this->redirectAdm('login/login');
            }

            $this->redirectAdm('dashboard');
            
            
        }
    
    }