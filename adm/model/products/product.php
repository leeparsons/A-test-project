<?php

    class productModel {

        public function getTypes() {
         
            $db = data::instantiate();
            
            $sql = "SELECT t.*,COUNT(p.typeID) AS linkedProducts
            
            
            FROM producttypes AS t
            
            LEFT JOIN producttypetoproduct AS p ON p.typeID = t.typeID
            
            GROUP BY t.typeID";
            
            $arr = $db->query($sql,'ARRAY_A');
            $db->close();
            return $arr;
            
            
        }
        
        public function getType($t = -1) {
            
            if ((int)$t > 0) {
                $db = data::instantiate();
                
                $sql = "SELECT t.*,COUNT(p.typeID) AS linkedProducts
                
                
                FROM producttypes AS t
                
                LEFT JOIN producttypetoproduct AS p ON p.typeID = t.typeID
                
                WHERE t.typeID = ?
                
                GROUP BY t.typeID";
                
                $arr = $db->query($sql,(int)$t,'ARRAY_A');
                $db->close();

                return $arr;
                

            
            }            
            
            
            return array();
        }
        
        public function typeExists($name,$t = -1) {
            $db = data::instantiate();
            
            $t = (int)$t;
            
            $arr = $db->query("SELECT * FROM producttypes WHERE name = '?' AND t <> '?'",array($name,$t),'ARRAY_A');
            $db->close();

            if (empty($arr)) {
                return false;   
            } else {
                return true;
            }
            
        }
        
        public function createType($name = '',$description = '',$file = '',$how = 1,$section = 1) {
            if ($name !== '') {

                $db = data::instantiate();
                $db->create("INSERT INTO producttypes (name,description,image,how,section) VALUES ('?','?','?','?','?')",array('?','?','?','?','?'),array($name,$description,$file,$how,$section));
                $db->close();

            }

        }

        
        public function updateType($name = '',$description = '',$file = '',$t = -1) {

            if ($name !== '' && (int)$t > 0) {
                
                $db = data::instantiate();

                $db->create("UPDATE producttypes SET name = '?', description = '?', image = '?' WHERE typeID = ?",array('?','?','?','?'),array($name,$description,$file,(int)$t));
                $db->close();

            }
            
        }
        
        public function getOptions() {
            $db = data::instantiate();
            
            $sql = "SELECT  p.name AS pName,p.description AS pDescription, p.productID AS pID,
            pt.typeID as tID, pt.name AS tName,pt.description AS tDescription, pt.image,
            GROUP_CONCAT(v.name ORDER BY v.cost ASC SEPARATOR '__') AS value,GROUP_CONCAT(v.cost ORDER BY v.cost ASC SEPARATOR '__') AS cost, GROUP_CONCAT(v.valueID ORDER BY v.cost ASC SEPARATOR '__') AS valueIDs,
            (SELECT COUNT(productID) FROM productstogalleries WHERE productID = p.productID) AS c

            FROM products AS p
            
            INNER JOIN producttypetoproduct AS ptp ON ptp.productID = p.productID
            
            
            INNER JOIN producttypes AS pt ON pt.typeID = ptp.typeID
            
            LEFT JOIN productvaluestoproduct AS pvp ON pvp.productID = p.productID
            
            LEFT JOIN productvalues AS v ON v.valueID = pvp.valueID


            
            GROUP BY pvp.productID
            
            ORDER BY v.valueID ASC";
            
            
            $arr = $db->query($sql,'ARRAY_A');
            $db->close();
            return $arr;
            
            
        }
        
        
        
        public function getOption($p = -1, $t = -1) {

            if ((int)$p > 0 && (int)$t > 0) {
                $db = data::instantiate();
                
                $sql = "SELECT  p.name AS pName,p.description AS pDescription, p.productID AS pID,
                pt.typeID as tID, pt.name AS tName,pt.description AS tDescription, pt.image,
                GROUP_CONCAT(v.name ORDER BY v.valueID ASC SEPARATOR '__') AS value,GROUP_CONCAT(v.cost ORDER BY v.valueID ASC SEPARATOR '__') AS cost, GROUP_CONCAT(v.valueID ORDER BY v.valueID ASC SEPARATOR '__') AS valueIDs,
                (SELECT COUNT(productID) FROM productstogalleries WHERE productID = p.productID) AS c
                
                FROM products AS p
                
                LEFT JOIN producttypetoproduct AS ptp ON ptp.productID = p.productID
                
                
                LEFT JOIN producttypes AS pt ON pt.typeID = ptp.typeID
                
                LEFT JOIN productvaluestoproduct AS pvp ON pvp.productID = p.productID
                
                LEFT JOIN productvalues AS v ON v.valueID = pvp.valueID
                
                WHERE pt.typeID IS NOT NULL AND p.productID = ? AND pt.typeID = '?'
                
                GROUP BY pvp.productID
                
                ORDER BY v.valueID ASC";
                
                
                $arr = $db->query($sql,array((int)$p,(int)$t),'ARRAY_A');
                $db->close();
                return $arr;
                
            } else {
                return array();   
            }
            
        }
        
        
        public function updateProductValues($p = -1,$t = -1,$description = '',$options = array()) {
            if ((int)$t > 0 && (int)$p > 0) {
        
                //check the options are not empty
                
                $db = data::instantiate();
                
                //get the value ids from the product table:
                
                $db->delete("DELETE FROM productvalues WHERE valueID IN (SELECT valueID FROM productvaluestoproduct WHERE productID = ?)",'?',(int)$p);
                
                $db->delete("DELETE FROM productvaluestoproduct WHERE productID = ?",'?',(int)$p);
                //update the product description
                
                $db->create("UPDATE products SET description = '?' WHERE productID = ?",array('?','?'),array((string)$description,(int)$p));
                
                
                if (!empty($options)) {
                 
                    
                    //update the options
                    
                    
                    foreach ($options as $k => $v) {
                     
                        $id = $db->create("INSERT INTO productvalues (name,cost) VALUES ('?','?')",array('?','?'),array((string)$v['name'],$v['cost']),true);
                        //now update the productvaluestoproducts table based on this id:
                        
                        $db->create("INSERT INTO productvaluestoproduct (valueID,productID) VALUES ('?','?')",array('?','?'),array((int)$id,(int)$p));
                        
                    }
                    
                    
                }
                $db->close();

                
            }
        }
        
        public function createProductValues($t = -1, $name = '', $description = '', $options = array()) {
            if ($name !== '' && $t > 0) {
             
                
                $db = data::instantiate();
                
                //insert the product into teh db:
                
                $pid = $db->create("INSERT INTO products (name,description) VALUES ('?','?')", array('?','?'), array($name,$description),true);
                //insert into the product to type table
                
                $db->create("INSERT INTO producttypetoproduct (productID,typeID) VALUES ('?','?')", array('?','?'), array($pid, (int)$t));
                
                //now insert the product values into the value table:
                
                if (!empty($options)) {
                    
                    
                    //update the options
                    
                    
                    foreach ($options as $k => $v) {
                        
                        $id = $db->create("INSERT INTO productvalues (name,cost) VALUES ('?','?')", array('?','?'), array((string)$v['name'], $v['cost']), true);
                        //now update the productvaluestoproducts table based on this id:
                        
                        $db->create("INSERT INTO productvaluestoproduct (valueID,productID) VALUES ('?','?')", array('?','?'), array((int)$id, (int)$pid));
                        
                    }
                    
                    
                }
                
                
                $db->close();

                
            }
        }
     
        
        public function removeType($t = -1) {
            if ((int)$t > 0) {
             
                $db = data::instantiate();
                //get all the product ids which are related to this type
                
                $pids = $db->query("SELECT GROUP_CONCAT(productID) AS p FROM producttypetoproduct WHERE typeID = ?",$t);

                
                $db->delete("DELETE FROM products WHERE productID IN (?)",'?',$pids[0]['p']);
                $db->delete("DELETE FROM productstogalleries WHERE productID IN (?)",'?',$pids[0]['p']);
                $db->delete("DELETE FROM producttypetoproduct WHERE typeID = ?",'?',$t);

                $vids = $db->query("SELECT GROUP_CONCAT(valueID) AS v FROM productvaluestoproduct WHERE productID IN (?)",$pids[0]['p']);
                $db->delete("DELETE FROM productvaluestoproduct WHERE productID IN (?)",'?',$pids[0]['p']);
                $db->delete("DELETE FROM productvalues WHERE valueID IN (?)",'?',$vids[0]['v']);

                
                $db->delete("DELETE FROM producttypes WHERE typeID = ?",'?',$t);
                $db->close();

            }
        }
        
        
        public function removeProduct($t = -1, $p = -1) {
            if ((int)$t > 0 && (int)$p > 0) {
                
                $db = data::instantiate();
                //get all the product ids which are related to this type
                
                $db->delete("DELETE FROM products WHERE productID = ?",'?',(int)$p);
                $db->delete("DELETE FROM productstogalleries WHERE productID = ?",'?',(int)$p);
                $db->delete("DELETE FROM producttypetoproduct WHERE typeID = ? AND productID = ?", array('?','?'), array((int)$t, (int)$p));
                
                $vids = $db->query("SELECT GROUP_CONCAT(valueID) AS v FROM productvaluestoproduct WHERE productID = ?",(int)$p);
                $db->delete("DELETE FROM productvaluestoproduct WHERE productID = ?",'?',(int)$p);
                $db->delete("DELETE FROM productvalues WHERE valueID IN (?)",'?',$vids[0]['v']);
                $db->close();
            }
        }
        
        public function getOneOffs() {
            
            $db = data::instantiate();
            
            $sql = "SELECT * FROM productoneoffs";
            
            $arr = $db->query($sql, 'ARRAY_A');
            $db->close();

            if (!empty($arr[0])) {
                return $arr;   
            }
            return array();
            
        }

        public function getOneOff($id = -1) {

            if ((int)$id > 0) {
                $db = data::instantiate();
                
                $sql = "SELECT * FROM productoneoffs WHERE id = ?";
                
                $arr = $db->query($sql, (int)$id, 'ARRAY_A');
                $db->close();

                if (!empty($arr[0])) {
                    return $arr[0];   
                }
                
            }
            return array();
            
        }
        
        public function checkOneOffUnique($name = '') {
         
            if ($name !== '') {
             
                $db = data::instantiate();

                $sql = "SELECT id FROM productoneoffs WHERE name = '?'";
                
                $arr = $db->query($sql, $name, 'ARRAY_A');
                $db->close();

                if (empty($arr[0])) {
                    return true;   
                }
                
                
            }
            return false;
            

        
        }
        
        
        public function checkOneOffUniqueById($id = -1, $name = '') {
            
            if ($name !== '' && (int)$id > 0) {
                
                $db = data::instantiate();
                
                $sql = "SELECT id FROM productoneoffs WHERE name = '?' AND id <> '?'";
                
                $arr = $db->query($sql, array($name, (int)$id), 'ARRAY_A');
                $db->close();

                if (empty($arr[0])) {
                    return true;   
                }
                
                
            }
            return false;
        } 
        
        public function updateOneOff($id = -1, $name = '', $description = '', $image = '', $cost = 0.00) {
            if ($name !== '' && (int)$id > 0) {
                
                $db = data::instantiate();
                
                $sql = "UPDATE productoneoffs SET name = '?', description = '?', image = '?', cost = '?' WHERE id = '?'";


                
                $db->create($sql, array('?', '?', '?', '?', '?'), array($name, $description, $image, $cost, (int)$id));
                $db->close();

                
            }
        }
        
        public function createOneOff($name = '', $description = '', $image = '', $cost = 0.00) {
            if ($name !== '') {
             
                $db = data::instantiate();
                
                $sql = "INSERT INTO productoneoffs (name, description, image, cost, urlID) VALUES ('?', '?', '?', '?', '?')";
                
                
                
                $db->create($sql, array('?', '?', '?', '?', '?'), array($name, $description, $image, $cost, uniqid()));
                $db->close();

                
            }
        }
        
        public function deleteOneOff($i = -1)
        {
            if ((int)$i > 0) {
             
                $db = data::instantiate();
                
                $sql = "DELETE FROM productoneoffs WHERE id = ?";
                                
                $db->delete($sql, '?', (int)$i);
                
                $db->close();

            }
            
        }
        
    }