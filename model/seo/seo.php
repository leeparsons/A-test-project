<?php


    class seoModel {


        public function getParams($url = '') {
        
            if ($url !== '') {
             
                $db = data::instantiate();                
                $sql = "SELECT * FROM seourls WHERE url = '?'";
                
                $arr = $db->query($sql, $url, 'ARRAY_A');
                $db->close();
                return $arr;
                
                
            }
            
        }
        
        
        public function getSeoUrl($lID = -1, $type = '')
        {
         
            if ((int)$lID > -1) {
                
                $db = data::instantiate();          
                
                $sql = "SELECT s.* FROM seourlsLinks AS sl
                
                LEFT JOIN seourls AS s ON s.id = sl.sID
                
                WHERE sl.lID = ?
                ";
                
                
                if ($type !== '') {
                    $sql .= " AND sl.type = '?'";
                    $arr = $db->query($sql, array((int)$lID, $type), 'ARRAY_A');
                } else {
                    $arr = $db->query($sql, $lID, 'ARRAY_A');
                }
                

                $db->close();
                
                if (empty($arr[0])) {
                    $arr = array();
                }
                return $arr;
                
                
            }
            
        }
        
    }