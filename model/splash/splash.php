<?php

    class splashModel {
        
        
        
        
        public function getClients($cID = -1) {
            $db = data::instantiate();
            $sql = 'SELECT * FROM clients WHERE cID = ?';

            $arr = $db->query($sql,array($cID),'ARRAY_A');
            $db->close();
            return $arr;
            
        }
        
        
        public function getClientGalleries($cID = -1) {
            $db = data::instantiate();
            $sql = "SELECT g.*,COUNT(gi.iID) AS c, s.url FROM galleries AS g INNER JOIN galleryimages AS gi ON (gi.gID = g.gID)
            
            LEFT JOIN seourlsLinks AS sl ON sl.lID = g.gID
            
            LEFT JOIN seourls AS s ON s.id = sl.sID
            
            WHERE cID = ? AND (gExpiry > ? OR noexpiry = 1) AND active = 1
            
            AND sl.type = 'g'
            
            GROUP BY g.gID";
            
            $arr = $db->query($sql, array($cID, time()), 'ARRAY_A');
            $db->close();
            if (empty($arr[0]['c'])) {
                return false;   
            }
            
            return $arr;
            
        }
        
    }