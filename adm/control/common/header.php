<?php

    class header extends controller {

        public function index() {
            $this->siteDescription = '';
            
            //call the menu file!
            
            $this->css[] = $this->admUrl . 'css/adm.css';
            $this->css[] = $this->admUrl . 'css/ui.css';
            
            $this->menu = '';
            
            $this->js[] = $this->admUrl . 'js/jquery.js';
            $this->js[] = $this->admUrl . 'js/ui.js';

            
        }

        
        
    }