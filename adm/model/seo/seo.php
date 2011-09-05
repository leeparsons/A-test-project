<?php

class seoModel {

    /*  lID is the id of the object referenced
    **  path is the location of the controller file relative to the controller folder
    **/
    public function update($lID = -1, $path = '', $params = '', $originalUrl = '', $seoUrl = '', $type = 'c') {
        
        if (is_array($params)) {
            $params = serialize($params);   
        }

        if ((int)$lID > 0 && strlen($path) > 0 && strlen($params) > 0 && strlen($originalUrl) > 0 && strlen($seoUrl) > 0) {
            $db = data::instantiate();
            
            //if $seoUrl has not got a trailing slash add them!
            if (substr($seoUrl, strlen($seoUrl) - 1, 1) !== '/') {
                $seoUrl .= '/';
                
            }

            //update the seo links!
            //see if the records exist for this identifier
            $sql = "SELECT sID FROM seourlsLinks WHERE lID = ? AND type = '?'";
            $arr = $db->query($sql, array((int)$lID, $type), 'ARRAY_A');
            

            
            if (substr($originalUrl, strlen($originalUrl) - 1, 1) !== '/') {
                $originalUrl .= '/';
                
            }
            if (substr($originalUrl, 0, 1) !== '/') {
                $originalUrl = '/' . $originalUrl;   
            }
            
            
            
            if (substr($path, strlen($path) - 1, 1) !== '/') {
                $path .= '/';
                
            }
            if (substr($path, 0, 1) == '/') {
                $path = substr($path, 1);   
            }
            
            if (!empty($arr[0])) {
             
                $sID = $arr[0]['sID'];
                
                //make sure the selected seoUrl does ont already exist!
                $sql= "SELECT * FROM seourls WHERE url = '?' AND id <> ?";
                
                $arr = $db->query($sql, array($seoUrl, $sID), 'ARRAY_A');
                
                if (!empty($arr[0])) {
                    $db->close();

                    return false;
                }
                
                //update the seo record
                $sql = "UPDATE seourls SET path = '?', params = '?', originalUrl = '?', url = '?' WHERE id = '?'";

                $db->create($sql, array('?', '?', '?', '?', '?'), array($path, $params, $originalUrl, $seoUrl, $sID));

		//update the links db - fixes a previous bug!

		$sql = "UPDATE seourlsLinks SET type = 'g' WHERE lID = ? AND sID = ?";

		$db->create($sql, array('?', '?'), array($lID, $sID));

                $db->close();
            } else {
             
                //make sure the selected seoUrl does ont already exist!
                $sql= "SELECT * FROM seourls WHERE url = '?'";
                
                $arr = $db->query($sql, $seoUrl, 'ARRAY_A');
                
                if (!empty($arr[0])) {
                    $db->close();
                    return false;
                }

                //create the seo link!
                
                $sql = "INSERT INTO seourls (path, params, originalUrl, url) VALUES ('?', '?', '?', '?')";
                
                $sID = $db->create($sql, array('?', '?', '?', '?'), array($path, $params, $originalUrl, $seoUrl), true);

                $sql = "INSERT INTO seourlsLinks (sID, lID, type) VALUES ('?', '?', '?')";

                $db->create($sql, array('?', '?', '?'), array((int)$sID, (int)$lID, $type));
                $db->close();
            }
            

            return true;
        }
    }
    
    public static function sanitize($str = '', $baseUrl) {
        $replaces = array('http://www.' . $baseUrl, 'www.' . $baseUrl, 'www.' . $baseUrl, 'https://www.' . $baseUrl, 'http://' . $baseUrl, 'https://' . $baseUrl, ' ');
        $replaceWith = array('', '', '', '', '', '', '-');
        return str_replace($replaces, $replaceWith, strtolower($str));
        
    }
    
    public static function checkIfSeoUrlExists($url = '', $id = -1) {
    
        $db = data::instantiate();
        
        if ((int)$id > 0) {
            //check to see if there is a linked record:
            $sql = "SELECT sID FROM seourlsLinks WHERE lID = ?";
            $arr = $db->query($sql, (int)$id, 'ARRAY_A');
            
            if (!empty($arr[0])) {
                $sID = $arr[0]['sID'];
                
                //make sure the selected seoUrl does ont already exist!
                $sql= "SELECT * FROM seourls WHERE url = '?' AND id <> ?";
                
                $arr = $db->query($sql, array($url, $sID), 'ARRAY_A');
                
                if (!empty($arr[0])) {
                    $db->close();

                    return true;
                }
                
            } else {
                $sql= "SELECT * FROM seourls WHERE url = '?'";
                $arr = $db->query($sql, $url, 'ARRAY_A');

                if (!empty($arr[0])) {
                    $db->close();

                    return true;
                }   
            }
        } else {
            $sql= "SELECT * FROM seourls WHERE url = '?'";
            $arr = $db->query($sql, $url, 'ARRAY_A');
            
            if (!empty($arr[0])) {
                $db->close();

                return true;
            }
        }
        $db->close();

        return false;
        
        
    }
    
    
    
    public function getUrl($lID = -1, $type = 'c') {
        
        if ((int)$lID > 0) {
            
            $db = data::instantiate();

            $sql = "SELECT sID FROM seourlsLinks WHERE lID = ? AND type = '?'";
            
            $arr = $db->query($sql, array((int)$lID, $type), 'ARRAY_A');
            
            if (!empty($arr[0])) {
                
                
                $sID = $arr[0]['sID'];
                $sql = "SELECT url FROM seourls WHERE id = ?";
            
                $arr = $db->query($sql, $sID, 'ARRAY_A');
                
                if (!empty($arr[0])) {
                    $db->close();

                    return $arr[0]['url'];   
                }
                
            } 
            $db->close();

            
        }
        return '';
        
    }
    
    //ids can be a string of ids as in using IN
    public function deleteUrls($ids = '', $type = 'c')
    {
        if (strlen($ids) > 0) {
            $db = data::instantiate();
            
            
            //get all the links ids
            
            $sql = "SELECT sID FROM seourlsLinks WHERE lID IN (?)";
            
            $sql .= " AND type = '?'";
            $arr = $db->query($sql, array($ids, $type), 'ARRAY_A');
            
                              
            if (!empty($arr[0])) {
                
                $idStr = '';
                
                foreach ($arr[0] as $id) {
                    
                    $idStr .= ($idStr == '') ? $id : ',' . $id;
                    
                }
                
                $sql = "DELETE FROM seourls WHERE id IN (?)";
                
                
                $db->delete($sql, '?', $idStr);
            }
            
            $sql = "DELETE FROM seourlsLinks WHERE lID IN (?)";
            
            $sql .= " AND type = '?'";
            $db->delete($sql, array('?', '?'), array($ids, $type));
            
            $db->close();
            
            
        }
    }
    
}