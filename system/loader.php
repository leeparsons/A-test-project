<?php

    class Loader {
    
        public function model($path = '') {
            global $env;
            if ($path !== '') {
                include_once $env->modelDir() .'model/' . $path . '.php';
                
            }
            
        }
        public function system($path = '') {
            global $env;
            if ($path !== '') {
                include_once $env->dir() .'system/' . $path . '.php';
                
            }
            
        }
        
        
    }
    
