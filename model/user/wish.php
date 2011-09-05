<?php

    class wishModel {
        
        public function getWishLists($hash = '') {
            if ($hash !== '') {
             
                $db = data::instantiate();
                
                
                $sql = "SELECT w.name, w.id, count(imageid) as c FROM users AS u
                
                LEFT JOIN wishliststouser AS w ON w.uid = u.id
                
                LEFT JOIN wishlists AS wl ON wl.wid = w.id
                
                
                WHERE hashStr = '?'
                
                GROUP BY w.id
                ";
                $arr = $db->query($sql, $hash, 'ARRAY_A');

                $db->close();

                
                if (!empty($arr[0]) && $arr[0]['name'] !== null) {
                    return $arr;   
                }
                
                
                
            }
            
            
            return array();
            
        }

        
        public function getWishListsWithProducts($hash = '') {
            if ($hash !== '') {
                
                $db = data::instantiate();
                
                
                $sql = "SELECT COUNT(g.iID) AS c, GROUP_CONCAT(g.iID) AS images, w.name, w.id FROM users AS u
                
                LEFT JOIN wishliststouser AS w ON w.uid = u.id
                
                LEFT JOIN wishlists AS wl ON wl.wid = w.id
                
      			LEFT JOIN galleryimages AS g ON g.iID = wl.imageid 
                
                
                WHERE hashStr = '?'
                
                GROUP BY w.id
                ";

                $arr = $db->query($sql, $hash, 'ARRAY_A');
                
                $db->close();


                if (!empty($arr[0])) {
                    return $arr;   
                }

                
                
            }
            
            
            return array();
            
        }
        
        
        public function getWishListFromWID($w = -1) {
            
            if ((int)$w > 0) {
                
                $sql = "SELECT g.gName, c.cName, gi.iID, gi.iName, g.cid, g.gID AS gid, w.name
                
                FROM users AS u
                
                INNER JOIN wishliststouser AS w ON w.uid = u.id
                
                LEFT JOIN wishlists AS wl ON wl.wid = w.id
                
                LEFT JOIN galleryimages AS gi ON gi.iID = wl.imageid
                
                LEFT JOIN galleries AS g ON g.gID = gi.gID
                
                LEFT JOIN clients AS c ON c.cID = g.cID
                
                WHERE w.id = ?
                ";

                $db = data::instantiate();
                
                
                $arr = $db->query($sql, (int)$w, 'ARRAY_A');
                $db->close();

                if (!empty($arr[0])) {
                    return $arr;
                }
                
            }
            
            return array();
            
        }
        
        public function getWishList($hash = '', $w = -1) {
         
            if (strlen($hash) > 0 && (int)$w > 0) {
                $db = data::instantiate();
                
                
                $sql = "SELECT g.gName, c.cName, gi.iID, gi.iName, g.cid, g.gID AS gid, w.name
                
                FROM users AS u
                
                INNER JOIN wishliststouser AS w ON w.uid = u.id
                
                LEFT JOIN wishlists AS wl ON wl.wid = w.id
                
                LEFT JOIN galleryimages AS gi ON gi.iID = wl.imageid
                
                LEFT JOIN galleries AS g ON g.gID = gi.gID
                
                LEFT JOIN clients AS c ON c.cID = g.cID
                
                WHERE hashStr = '?' AND w.id = ?
                ";

                $arr = $db->query($sql, array($hash, (int)$w), 'ARRAY_A');
                $db->close();

                if (!empty($arr[0]) && $arr[0]['cid'] !== null) {
                    return $arr;   
                }
                
            }
            
            return array();
            
        }
        
        
        public function add($hash = '', $wishes = array(), $item = -1) {

            if (strlen($hash) > 0  && is_array($wishes) && (int)$item > 0) {
             
                $db = data::instantiate();
                
                //get the user id
                
                $sql = "SELECT id FROM users WHERE hashstr = '?'";
                
                $arr = $db->query($sql, $hash, 'ARRAY_A');
                
                if (!empty($arr[0])) {
                

                    $sql = "INSERT INTO wishlists (imageid, wid) VALUES ";
                    
                    $values = '';
                    $replaces = array();
                    $marks = array();
                    
                    //wishes are the ids of each wish list to add the item id to
                    //item id is an image id
                    foreach ($wishes as $wish) {
                        
                        $values .= ($values == '')?"('?', '?')":", ('?', '?')";
                        $marks[] = '?';
                        $marks[] = '?';
                        $replaces[] = $item;
                        $replaces[] = $wish;
                        
                    }

                    //now insert!
                    
                    $db->create($sql . $values, $marks, $replaces);

                    
                }
                $db->close();

            }
            
            
        }
        
        public function remove($hash = '', $wishes = array(), $item = -1) {

            if (strlen($hash) > 0  && is_array($wishes) && (int)$item > 0) {
                
                $db = data::instantiate();
                
                //get the user id
    
                $sql = "SELECT id FROM users WHERE hashstr = '?'";
                
                $arr = $db->query($sql, $hash, 'ARRAY_A');
                
                if (!empty($arr[0])) {
                    
                    foreach ($wishes as $wish) {
                        $marks = array();
                        $replaces = array();
                        $sql = <<<QUERY
                        DELETE FROM wishlists WHERE imageid = '?' AND wid = '?';

QUERY;
                        
                        $marks[] = '?';
                        $marks[] = '?';
                        
                        $replaces[] = $item;
                        $replaces[] = $wish;

                        $db->delete($sql, $marks, $replaces);

                    }
                    
                    
                    
                }
                $db->close();

            }
            
            
        }
        
        public function isNameUnique($hash = '', $name = '') {
            if ($name !== '' && $hash !== '') {
                
                $db = data::instantiate();
                
                $sql = "SELECT u.id FROM users AS u
                
                LEFT JOIN wishliststouser AS wu ON wu.uid = u.id
                
                
                WHERE hashstr = '?'  AND wu.name = '?'";

                $arr = $db->query($sql, array($hash, $name), 'ARRAY_A');
                $db->close();

                if (!empty($arr[0])) {
                    return $arr[0]['id'];   
                }
                
                
            }
            
            return false;
        }
        
        
        
        
        public function createWishList($hash = '', $name = '', $uid = -1) {
            if ($name !== '' && $hash !== '' && (int)$uid > 0) {
                
                $db = data::instantiate();
                
                //create the wish list
                $sql = "INSERT INTO wishliststouser (uid, name) VALUES ('?', '?')";

                $db->create($sql, array('?', '?'), array((int)$uid, (string)$name));
                $db->close();

            }
            
        }
        
        public function removeItem($w = -1, $p = -1) {
            if ((int)$w > 0 && (int)$p > -1) {
             
                $db = data::instantiate();
                
                $sql = "DELETE FROM wishlists WHERE imageid = '?' AND wid = '?'";

                $db->delete($sql, array('?', '?'), array((int)$p, (int)$w));
                $db->close();

            }
        }
        
        public function getSimpleInformation($w = -1) {

            if ((int)$w > 0) {
             
                $db = data::instantiate();
                
                
                $sql = "SELECT name FROM wishliststouser
                
                WHERE id = '?'";

                $arr = $db->query($sql, (int)$w, 'ARRAY_A');
                $db->close();

                return $arr[0];

                
            }
            
            return array();
            
        }
        
        public function deleteWishList($w = -1) {
            
            if ((int)$w > 0) {
                
                $db = data::instantiate();
                
                
                $sql = "DELETE FROM wishliststouser
                
                WHERE id = '?'";
                
                $db->delete($sql, '?', (int)$w);
                                
                $sql = "DELETE FROM wishlists WHERE wid = '?'";
                
                $db->delete($sql, '?', (int)$w);
                $db->close();

            }
            
            
        }
        
        
        public function countWishLists($hash = '') {

            if (strlen($hash) > 0) {
                
                $sql = "SELECT count(*) AS c FROM users AS u
                
                
                INNER JOIN wishliststouser AS wu ON wu.uid = u.id
                
                WHERE u.hashStr = '?'";

                $db = data::instantiate();
                
                $countArr = $db->query($sql, $hash, 'ARRAY_A');
                $db->close();

                
                if (!empty($countArr[0])) {
                    return $countArr[0]['c'];
                }
                
            }
            
            
            return (int)0;
        }
        
    }