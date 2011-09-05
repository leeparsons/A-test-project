<?php

    class logout extends controller {

        public function index() {


            setcookie('tpproofing_login', '', time() - 100, $this->url);
            
            if (isset($_GET['redirect'])) {
                $url = urldecode(stripslashes($_GET['redirect']));
                if (substr($url, 0, 1) !== '/') {
                    $this->redirect('/' . $url); 
                }
                $this->redirect();
            } else {
            
                $this->redirect();
            }
        }        
        
    }