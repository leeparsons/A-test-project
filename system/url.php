<?php

    class url {
    
        
        private $includes = array();
        private $host;
        private $user;
        private $pass;
        private $path;
        private $query;
        private $fragment;
        
        
        public function __construct() {

            if (!empty($_SERVER['HTTPS'])) {
                if ($_SERVER['HTTPS'] != "on") {
                    $this->scheme = 'http';
                } else {
                    $this->scheme = 'https';
                }
            } else {
                $this->scheme = 'http';
            }
            
            $bits = parse_url($this->scheme . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
            
            $this->host = (!empty($bits['host']))?$bits['host']:'';
            $this->user = (!empty($bits['user']))?$bits['user']:'';
            $this->pass = (!empty($bits['pass']))?$bits['pass']:'';
            if (!empty($bits['path'])) {
                $rev = strrev($bits['path']) ;
                if (substr($rev,0,1) == '/') {
                    $this->path = substr($bits['path'],0,strlen($bits['path']) - 1);
                } else {
                    $this->path = $bits['path'];
                }
            }      
            
            if (substr($this->path, strlen($this->path) - 1, 1) !== '/') {
                $this->path .= '/';
            }
            
            $this->query = (!empty($bits['query']))?$bits['query']:'';
            $this->fragment = (!empty($bits['fragment']))?$bits['fragment']:'';
        }
        
        public function getBaseUri() {
            return $this->path;
        }
        
        public function getFullBaseUri() {

            return $this->scheme . '://' . $this->host;

        }
        
        public function getIncludes() {
            
            global $env;
            //need to determine if we're in the adm section
            if ($env->isAdm()) {
                
                Loader::model('user/loginauth');
                if (loginauthModel::isLoggedIn()) {
                    //need to get the menu and other adm includes
                    $this->includes[] = 'common/header';
                    $this->includes[] = 'common/menu';
                } else {
                    $this->includes[] = 'common/loginheader';   
                }
            } else {
                $this->includes[] = 'common/header';   
            }

            global $controller;
            
            switch (str_replace($controller->url, '', (string)$this->path)) {
                case '':case '/':
                    #home page
                    $this->includes[] = 'home/home';
                    break;
                default:

                    if ($controller->url !== '/') {
                        $newpath = '/' . str_replace($controller->url, '', $this->path);
                    } else {
                        $newpath = $this->path;   
                    }
                        
                    //remove the end slash!
                    
                    $newpath = substr($newpath, 0, strlen($newpath) - 1);
                    

                    $pathBits = explode('/', $newpath);
                    

                    
                    if (count($pathBits) == 2) {
                        
                        $this->includes[] = $newpath . $newpath;
                    } else {

                        $this->includes[] = $newpath;
                    }
                    

                    
                    break;
            }
            $this->includes[] = 'common/footer'; 

            return $this->includes;
        }
        
        public function getFileSystem() {
            $controller = new controller();
            //need to determine which system we're in
            switch (str_replace($controller->url, '', (string)$this->path)) {
                case '':case'/':
                    return 'front';
                    break;
                case '/splash':
                    return 'splash';
                    break;
                case '/splash/gallery':
                    return 'gallery';
                    break;
                case '/photoview':
                    return 'photoview';
                default:
                    return 'misc';
                    break;
            }

        }
        
        public function reindex($url) {
            $bits = parse_url($this->scheme . '://' . $_SERVER['SERVER_NAME'] . $url);
            
            $this->host = (!empty($bits['host']))?$bits['host']:'';
            $this->user = (!empty($bits['user']))?$bits['user']:'';
            $this->pass = (!empty($bits['pass']))?$bits['pass']:'';
            if (!empty($bits['path'])) {
                $rev = strrev($bits['path']) ;
                if (substr($rev,0,1) == '/') {
                    $this->path = substr($bits['path'],0,strlen($bits['path']) - 1);
                } else {
                    $this->path = $bits['path'];
                }
            }      
            
            if (substr($this->path, strlen($this->path) - 1, 1) !== '/') {
                $this->path .= '/';
            }
            
            $this->query = (!empty($bits['query']))?$bits['query']:'';
            $this->fragment = (!empty($bits['fragment']))?$bits['fragment']:'';
            
        }
    
    }

