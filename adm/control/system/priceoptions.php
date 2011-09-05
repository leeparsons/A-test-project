<?php
    
    class priceoptions extends controller {

        
        public function index() {
            
            Loader::model('user/loginauth');
            
            
            if (!loginauthModel::isLoggedIn()) {
            
           
                $this->redirectAdm('login/login');
            }
            
            
            
            
        }
        
        
        public function add() {
            
            //get the procuttypes
            Loader::model('products/product');
            
            $typesArr = productModel::getTypes();
            
            if (empty($typesArr)) {
                
                $this->create= $this->admUrl . 'products/types/add';
                
            }
            
            
            $this->action = $this->admUrl . 'system/priceoptions/save/';
            
            
            $this->template = 'add';
            
        }
        
    
    }