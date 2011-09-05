<?php

    class galleryModel {
        
        
        public function getImages($cID = -1,$gID = -1,$start = 0,$end = 25) {
            $db = data::instantiate();
            $sql = 'SELECT * FROM galleryimages AS gi INNER JOIN galleries AS g ON (gi.gID = g.gID) WHERE cID = ? AND g.gID = ? AND (gExpiry > ? OR noexpiry = 1) AND active = 1 ORDER BY gi.iName ASC';
            

            
            
            
            if ((int)$start >= 0) {
                if ((int)$end >= 0) {
                    $sql .= ' LIMIT ' . (int)$start . ', ' . (int)$end ;
                } else {
                    $sql .= ' LIMIT ' . (int)$start;
                }
            } elseif ($start > -1) {
                $sql .= ' LIMIT 0,25';   
            }
            
            return $db->query($sql,array($cID,$gID,time()),'ARRAY_A');
        }
        
        
        public function countImages($cID = -1, $gID = -1) {
            $db = data::instantiate();
            
            
            
            $sql = 'SELECT COUNT(*) AS c FROM galleryimages AS gi INNER JOIN galleries AS g ON (gi.gID = g.gID) WHERE cID = ? AND g.gID = ? AND (gExpiry > ? OR noexpiry = 1) AND active = 1';

            
            $arr = $db->query($sql,array($cID, $gID, time()), 'ARRAY_A');
            $db->close();


            if (empty($arr[0])) {
                return (int)0;
            }
            
            return (int)$arr[0]['c'];
            
        }
        
        public function getGalleryInformation($gid = -1) {
            if ((int)$gid > 0) {
                
                $db = data::instantiate();
                
                
                $sql = "SELECT gName, gpword, gExpiry, active, noexpiry FROM galleries WHERE gid = ?";

                $arr = $db->query($sql, (int)$gid, 'ARRAY_A');

                $db->close();
                if (!empty($arr[0])) {
                 
                    return $arr[0];
                    
                }
                
                
                
            }
            
            
            return false;
        }
        
    }