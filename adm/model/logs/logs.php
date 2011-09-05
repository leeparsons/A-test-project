<?php

    class logsModel {
        
        
        public function insertLog($log = '') {

            if ($log !== '') {
                $db = data::instantiate();
                $sql = "INSERT INTO logs SET stuff = '?', ldate = NOW()";

                $db->create($sql, array('?'), array($log));
                $db->close();

            }
        }
        
    }