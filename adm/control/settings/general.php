<?php
    
    class general extends controller {

        
        public function index() {
            
            Loader::model('user/loginauth');
            
            
            if (!loginauthModel::isLoggedIn()) {
            
           
                $this->redirectAdm('login/login');
            }
            
            
            
            $this->css[] = $this->admUrl . 'js/colour/css/colorpicker.css';
            $this->js[] = $this->admUrl . 'js/colour/colorpicker.js';
            $this->js[] = $this->admUrl . 'js/colour/eye.js';
            $this->js[] = $this->admUrl . 'js/colour/layout.js';
            $this->js[] = $this->admUrl . 'js/colour/utils.js';
        
            

            
        }
        
                
    
    }