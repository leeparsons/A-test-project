<?php

    class session {
    
        public function __construct() {

            ini_set('session.gc_maxlifetime',5184000);

            session_start();
        }
        
    }

