<?php

    class homeModel {

        public function getClientGalleries() {
            $db = data::instantiate();
            
            
            $sql = "SELECT * FROM clients ORDER BY modified DESC, cID DESC";

            $arr = $db->query($sql, 'ARRAY_A');
            $db->close();
            return $arr;
            
        
        }
    

    }