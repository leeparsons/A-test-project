<?php

    class clientsModel {

        protected $cError = '';
        
        public function getClients($cID = -1) {
            $db = data::instantiate();

            if (is_numeric($cID) && (int)$cID > 0) {
                $sql = "SELECT c.cEmail AS email,c.cID, GROUP_CONCAT(g.active SEPARATOR ',') AS active, GROUP_CONCAT(g.gExpiry SEPARATOR ',') AS gExpiry,c.cFrontImage,c.cDescription AS description,c.cName,  c.splash, GROUP_CONCAT(g.gID SEPARATOR ',') AS gIDs, GROUP_CONCAT(g.gExpiry SEPARATOR ',') AS expiry, GROUP_CONCAT(g.noexpiry SEPARATOR ',') AS noexpiry, GROUP_CONCAT(g.gName SEPARATOR ',') AS gName
                
                    FROM clients AS c LEFT JOIN galleries AS g ON g.cID = c.cID
                
                    WHERE c.cID = ? GROUP BY g.cID

                    ORDER BY modified DESC, c.cID DESC";

                $arr = $db->query($sql,$cID,'ARRAY_A');
                $db->close();

            } else {
                $sql = "SELECT c.cID,c.cFrontImage,c.cName,c.splash,GROUP_CONCAT(g.active  SEPARATOR ',') AS active,GROUP_CONCAT(g.gExpiry SEPARATOR ',') AS gExpiry,GROUP_CONCAT(g.gID SEPARATOR ',') AS gIDs, GROUP_CONCAT(g.gExpiry SEPARATOR ',') AS expiry, GROUP_CONCAT(g.gName SEPARATOR ',') AS gName, GROUP_CONCAT(g.noexpiry SEPARATOR ',') AS noexpiry FROM clients AS c Left Join galleries AS g ON g.cID = c.cID GROUP BY c.cName ORDER BY modified DESC, c.cID DESC";

                $arr = $db->query($sql, 'ARRAY_A');
                $db->close();

            }
            return $arr;


        }
    

        public function create($name = '', $description = '', $email = '', $file = '', $splash = '') {

            if ($name !== '') {
                
                $db = data::instantiate();
                $sql = "INSERT INTO clients SET cName = '?', cDescription = '?', cEmail = '?', cFrontImage = '?', splash = '?', modified = '?'";
                
                $cID = $db->create($sql, array('?', '?', '?', '?', '?', '?'), array($name, $description, $email, $file, $splash, time()), true);
                //create the directory!

                $env = new environment();
                if (!file_exists($env->imageDir() . strtolower(str_replace(' ', '_', $name)))) {
	                mkdir($env->imageDir() . strtolower(str_replace(' ', '_', $name)), 0777, true);
	                chmod($env->imageDir() . strtolower(str_replace(' ', '_', $name)), 0777);
                }
                $db->close();

                return (int)$cID;
                
            } else {
                return false;   
            }
            
            
        }
        
        public function update($name = '', $description = '', $email = '', $file = '', $cID = -1, $splash = '') {
            if ($name !== '' && (int)$cID > 0) {

                $db = data::instantiate();
                $sql = "UPDATE clients SET cName = '?', cDescription = '?', cEmail = '?', cFrontImage = '?', splash = '?', modified = '?' WHERE cID = ?";

                $db->create($sql, array('?', '?', '?', '?', '?', '?', '?'), array($name, $description, $email, $file, $splash, time(), (int)$cID));
                $db->close();
                
            } else {
                return false;   
            }
            
            
        }
        
        public function getGalleryInformation($gID = -1, $cID = -1) {
            
            if ((int)$gID > 0 && (int)$cID > 0) {
                
                $db = data::instantiate();
                
                $sql = "SELECT COUNT(gi.gID) AS total, c.cName, g.*, s.url FROM galleries AS g
                
                LEFT JOIN galleryimages AS gi ON gi.gID = g.gID
                
                LEFT JOIN clients AS c ON c.cID = g.cID
                
                LEFT JOIN seourlsLinks AS sl ON sl.lID = g.gID
                
                LEFT JOIN seourls AS s ON s.id = sl.sID
                
		

                WHERE g.gID = ? AND c.cID = ?
                AND sl.type = 'g'
		";
                
                $arr = $db->query($sql,array($gID,$cID),'ARRAY_A');
                $db->close();

                if (!empty($arr)) {
                    return $arr[0];
                }
                
            }
            
            return array();
            
        }
        
        public function deleteClient($c = -1, $deleteSEO = false) {

            
            if ((int)$c > 0) {
            
                $env = new environment();
                
                //check permissions on the galleries folder first:
                
                $db = data::instantiate();
                
                //get the client name

                $cname = $db->query("SELECT cName FROM clients WHERE cID = ?", $c, 'ARRAY_A');
                if (!empty($cname[0]['cName'])) {
                    $path = $env->imageDir() . strtolower(str_replace(' ', '_', $cname[0]['cName']));
                    $e = '';
                    
                    
                    if ($path == $env->imageDir()) {
                        
                        header('location: ../');
                    }
                    
                    if (file_exists($path)) {
                        
                        
                        $perm = substr(base_convert(fileperms($path), 10, 8), 2);
                        
                        if ($perm !== '777') {
                            $e .= ($e =='')?'':'<br/>';
                            $e .= 'There was an error trying to remove the clients directores: ' . $path . '. You may want to delete them via ftp or set the permissions on the directory to 0777. Remember to come back here after! <a href="../' . '">Go Back</a>';
                            
                        }
                    }

                    $db->delete("DELETE FROM clients WHERE cID = ?",'?',$c);
                    
                    //get galleryids
                    
                    $g = $db->query("SELECT GROUP_CONCAT(gID) AS g FROM galleries WHERE cID = ?", (int)$c, 'ARRAY_A');
                    
                    $db->delete("DELETE FROM galleries WHERE cID = ?", '?', (int)$c);
                    
                    $db->delete("DELETE FROM galleryimages WHERE gID IN (?)",'?',$g[0]['g']);
                    
                    //get the gallerysetids
                    $s = $db->query("SELECT GROUP_CONCAT(sID) AS s FROM gallerysets WHERE gID IN (?)", $g[0]['g'],'ARRAY_A');
                    
                    //now remove all records from the gallery sets
                    
                    $db->delete("DELETE FROM gallerysets WHERE gID IN (?)", '?', $g[0]['g']);
                    
                    //now remove all records from the gallerysetlink
                    $db->delete("DELETE FROM gallerysetlink WHERE sID IN (?)", '?', $s[0]['s']);
                    
                    //delete from any products linked to these galleries
                    $db->delete("DELETE FROM productstogalleries WHERE gID IN (?)",'?',$g[0]['g']);
                    
                    //remove the directory for the clients
                    $db->close();
                    
                    if ($deleteSEO === true) {
                        Loader::model('seo/seo');
                        seoModel::deleteUrls((int)$c, 'c');
                    }
                    
                    
                    
                    //cycle through the directory and remove all files and directories!
                    $e .= ($e =='')?'':'<br/>';
                    $e .= clientsModel::iterateDirectories($path, $e);

                    if ($e !== '') {
                        die('There were a few problems: <br/>' . $e);
                    }
                    
                }                
                
            }
        }
        
        
        public static function iterateDirectories($path, &$e) {
            
            if (substr(strrev($path), 0, 2) == '//') {
                return;   
            }
            if (file_exists($path)) {
                $e .= ($e =='')?'':'<br/>';
                $perm = substr(base_convert(fileperms($path), 10, 8), 2);
                
                if ($perm !== '777') {
                    
                    return 'There was an error trying to remove the clients directores: ' . $path . '. You may want to delete them via ftp or set the permissions on the directory to 0777. Remember to come back here after! <a href="../' . '">Go Back</a>';
                    
                }
                
                $dir = @opendir($path);
                if ($dir === false) {
                    
                    return 'There was an error trying to remove the clients directores: ' . $path . '. You may want to delete them via ftp. <a href="../' . '">Go Back</a>';
                    
                }

                $dirs = array();
                
                while (false !== ($fName = readdir( $dir ))) {
                    
                    
                    if ($fName !== './' && $fName !== '../' && $fName !== '..' && $fName !== '.') {
                        
                        if (file_exists($path . '/' . $fName) && !is_dir($path . '/' . $fName)) {

                            $perm = substr(base_convert(fileperms($path . '/' . $fName), 10, 8), 2);

                            
                            if ($perm !== '777' && $perm != '0777') {
                                
                                try {
                                    chmod($path . '/' . $fName, 0777);
                                } catch (Exception $ex) {
                                    $e .= ($e =='')?'':'<br/>';

                                    $e .= 'There was an error trying to remove the clients directores: ' . $path . '. You may want to delete them via ftp or set the permissions on the directory to 0777. Remember to come back here after! <a href="../' . '">Go Back</a>';
                                }
                                
                            }
				try {
	                            unlink($path . '/' . $fName);
				} catch (Exception $e) {
					$e .= 'There was an error removing one of the files<br/>';
				}
                        } else {
                            $dirs[] = $path . '/' . $fName;
                        }
                    }
                }   
                
                closedir( $dir );
                
                if ($e !== '') {
                    return $e;   
                }
                
                
                
                
                if (!empty($dirs)) {
                    
                    foreach ($dirs as $d) {
                        
                        
                        
                        $perm = substr(base_convert(fileperms($d), 10, 8), 2);
                        
                        if ($perm !== '777') {
                            try {
                                chmod($d, 0777);
                            } catch (Exception $ex) {
                                $e = 'There was an error trying to remove the clients directores: ' . $d . '. You may want to delete them via ftp or set the permissions on the directory to 0777. Remember to come back here after! <a href="../' . '">Go Back</a>';
                            }
                            
                        }
                        $e .= ($e =='')?'':'<br/>';
                        $e .= clientsModel::iterateDirectories($d, $e);
                        
                        if ($e !== '') {
                            return $e;   
                        }   
                    }
                }
                
                rmdir($path);

            
            }
            return '';
        }
        
    }