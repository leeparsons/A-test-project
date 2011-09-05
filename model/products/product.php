<?php

    class productModel {
        
        
        public function getOptionsByGallery($g = -1) {
            if ((int)$g > 0) {
                $sql = "SELECT pt.name AS type, p.name AS product, pt.typeID AS tid, GROUP_CONCAT(pv.name ORDER BY pv.cost ASC SEPARATOR '__') AS name, GROUP_CONCAT(pv.cost ORDER BY pv.cost ASC SEPARATOR '__') AS cost 
                
                
                FROM productstogalleries AS ptg
                
                LEFT JOIN products AS p ON ptg.productID = p.productID
                
                LEFT JOIN producttypetoproduct AS ptp ON ptp.productID = p.productID
                
                LEFT JOIN producttypes AS pt ON pt.typeID = ptp.typeID
                
                LEFT JOIN productvaluestoproduct AS pvp ON pvp.productID = p.productID
                
                LEFT JOIN productvalues AS pv ON pv.valueID = pvp.valueID
                
                WHERE ptg.gID = ?
                
                GROUP BY pt.name, p.productID
                ";
                
                
                $db = data::instantiate();
                
                $arr = $db->query($sql, (int)$g, 'ARRAY_A');
                $db->close();
                if (empty($arr[0])) {
                    return array();   
                }
                
                return $arr;
                
            }            
            
            
            return array();
        }
        
        public function getOneOffByURL($id = -1) {
            if (strlen($id) > 0) {
                
                $db = data::instantiate();
                
                $sql = "SELECT * FROM productoneoffs WHERE urlID = '?'";
                
                $arr = $db->query($sql, (string)$id, 'ARRAY_A');
                $db->close();
                if (!empty($arr[0])) {
                 
                    return $arr[0];
                    
                }
                
            }
            
            return false;
        }
        
        public function getOneOffs() {
            $db = data::instantiate();
            $sql = "SELECT * FROM productoneoffs ORDER BY name ASC";
            
            $arr = $db->query($sql, ARRAY_A);
            $db->close();
            return $arr;
            
            
        }
        
   
    }