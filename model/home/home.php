<?php

    class homeModel {

        public function getClientGalleries() {
            $db = data::instantiate();
            
            
            $sql = "SELECT c.* FROM clients AS c
            
            LEFT JOIN galleries AS g ON g.cID = c.cID
            
            GROUP BY c.cName
            
            ORDER BY g.gCreated DESC, c.cName ASC";

            $arr = $db->query($sql,'ARRAY_A');
            
            $db->close();
            return $arr;
            
        
        }
    

    }