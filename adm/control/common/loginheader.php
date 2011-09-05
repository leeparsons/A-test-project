<?php

    class loginHeader extends controller {

        public function index() {
            $this->siteDescription = 'targ';
            
            //call the menu file!
            
            $this->css[] = $this->admUrl . 'css/adm.css';
            
        }

        
        
    }