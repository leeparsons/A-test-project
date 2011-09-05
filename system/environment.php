<?php

    class environment {
    
        private $_dir;
        private $_admDir = '';

        public function __construct() {
            $this->_dir = (string)dirname($_SERVER['SCRIPT_FILENAME']) . '/';

            
            $rev = strrev($this->_dir);
            
            
            if (substr($rev, 0, 1) == '/') {

                if (substr($rev, 0, 4) == '/mda') {
                    $this->_admDir = $this->_dir;
                    $this->_dir = strrev(substr($rev, 4));
                }
                
            } else {

                if (substr($rev, 0, 3) == 'mda') {
                    $this->_admDir = $this->_dir . '/';
                    $this->_dir = strrev(substr($rev, 3)) . '/';
                }
            }
        }
        
        public function dir() {
            return $this->_dir;
        }
        
        public function systemDir() {
            return $this->_dir . 'system/';
        }

        public function configDir() {
            return $this->_dir;
        }
        
        public function viewDir() {
            if ($this->_admDir !== '') {
                return $this->_admDir . '/view/';   
            }
            return $this->_dir . 'view/';
        }
        
        public function modelDir() {
            if ($this->_admDir !== '') {
                return $this->_admDir;   
            }
            return $this->_dir;
        }
        
        public function controllerDir() {

            if ($this->_admDir !== '') {
                return $this->_admDir . '/control/';   
            }
            return $this->_dir . 'control/';
        }
        
        public function imageDir() {
            return $this->_dir . '_galleries_/';
        }
    
        public function isAdm() {
            if ($this->_admDir !== '') {
                return true;   
            }
            
            return false;
        }
    }

