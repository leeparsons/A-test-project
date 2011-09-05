<?php

    class galleriesModel {
        
        public function getGalleryInfo($gID = -1, $cID = -1) {
            
            if ((int)$gID > 0 && (int)$cID > 0) {
                
                $db = data::instantiate();
                
                $sql = "SELECT COUNT(gi.gID) AS total, c.cName, g.* FROM galleries AS g
                
                LEFT JOIN galleryimages AS gi ON gi.gID = g.gID
                
                LEFT JOIN clients AS c ON c.cID = g.cID
                
                WHERE g.gID = ? AND c.cID = ?";
                
                $arr = $db->query($sql,array($gID,$cID),'ARRAY_A');

                $db->close();

                if (!empty($arr)) {
                    return $arr[0];
                }
                
            }
            
            return array();
            
        }
        
        
        public function updateGallery($blog = '', $activate = false, $gid = -1, $expiry = 0, $noexpiry = 0, $pword = null) {
            if ((int)$gid < 1) {
                return -1;   
            }
            
            $db = data::instantiate();
            
            $sql = "UPDATE galleries SET gExpiry = '?', gBlog = '?', active = '?', noexpiry = '?', gpword = '?' WHERE gID = '?'";
            
            $db->create($sql, array('?', '?', '?', '?', '?', '?'), array((int)$expiry, $blog, $activate, $noexpiry, $pword, (int)$gid));
            $db->close();

            
        }
        
        public function createGallery($name = '', $blog = '', $activate = false, $cid = -1, $expiry = 0, $noexpiry = 0, $return = false, $clientName = '', $pword = '') {
            if ($name !== '' && (int)$cid > 0) {
                
                $db = data::instantiate();
                
                $sql = "INSERT INTO galleries (cID, gName, gExpiry, gCreated, gBlog, active, noexpiry, gpword) VALUES ('?', '?', '?', '?', '?', '?', '?', '?')";

                
                if ($expiry == 'indef') {
                    $noexpiry = 1;
                }
                

                $gID = $db->create($sql, array('?', '?', '?', '?', '?', '?', '?', '?'), array($cid, $name, $expiry, time(), $blog, $activate, $noexpiry, $pword), true);
                
                //now update the client
                
                $sql = "UPDATE clients SET modified = '?' WHERE cID = ?";
                
                $db->create($sql, '?', $cid);
                
                //now create the gallery folder if not exists
                
                if ($clientName !== '') {

                    
                    $env = new environment();

                    if (file_exists($env->imageDir() . strtolower(str_replace(' ', '_', $clientName)) . '/' )) {
                        if (!file_exists($env->imageDir() . strtolower(str_replace(' ', '_', $clientName) . '/' . strtolower(str_replace(' ', '_', $name))))) {
                            mkdir($env->imageDir() . strtolower(str_replace(' ', '_', $clientName)) . '/' . strtolower(str_replace(' ', '_', $name)), 0777);
                            chmod($env->imageDir() . strtolower(str_replace(' ', '_', $clientName)) . '/' . strtolower(str_replace(' ', '_', $name)), 0777);
                        }
                    }
                }
                $db->close();

                if ($return === true) {
                    return $gID;   
                }
                
                
            } else {
            
                if ($return === true) {
                    return -1;
                }
            
            }
        }
        
        
        
        public function linkOptions($gID = -1, $options = array()) {

            if ((int)$gID > -1 && !empty($options)) {

                $replaces = '';
                $sql = '';
                $replace = '';
                foreach ($options as $o) {
                    $sql .= ($sql == '')?" ('?', '?') ":", ('?', '?')";
                    $replace .= ($replace == '')?"?,?":",?,?";
                    $replaces .= ($replaces == '')?(int)$gID . ',' . $o:',' . (int)$gID . ',' . $o;
                }
                
                
                $db = data::instantiate();
                
                //delete from the options!
                $db->delete("DELETE FROM productstogalleries WHERE gID = ?", '?', (int)$gID);
                
                if ($sql !== '' && $replaces !== '') {
                 
                    //insert into the db!
                    
                    $db->create("INSERT INTO productstogalleries (gID, productID) VALUES " . $sql, explode(',', $replace), explode(',', $replaces));
                }
                $db->close();

            }
        }
        
        public function getCostOptions($gID = -1, $idsOnly = false) {
         
            if ((int)$gID > 0) {
                
                $db = data::instantiate();
                
                if ($idsOnly === true) {
                    $sql = "SELECT  p.productID AS pID";
                    
                } else {
                    $sql = "SELECT  p.name AS pName,p.description AS pDescription, p.productID AS pID,
                    pt.typeID as tID, pt.name AS tName,pt.description AS tDescription, pt.image,
                    GROUP_CONCAT(v.name ORDER BY v.cost ASC SEPARATOR '__') AS value,GROUP_CONCAT(v.cost ORDER BY v.cost ASC SEPARATOR '__') AS cost, GROUP_CONCAT(v.valueID ORDER BY v.cost ASC SEPARATOR '__') AS valueIDs";
                    
                    
                }
                
                $sql .= "
                FROM products AS p
                
                INNER JOIN producttypetoproduct AS ptp ON ptp.productID = p.productID
                
                
                INNER JOIN producttypes AS pt ON pt.typeID = ptp.typeID
                
                LEFT JOIN productvaluestoproduct AS pvp ON pvp.productID = p.productID
                
                LEFT JOIN productvalues AS v ON v.valueID = pvp.valueID
                
                LEFT JOIN productstogalleries AS pgp ON pgp.productID = p.productID
                
                WHERE pgp.gID = ?
                
                
                GROUP BY pvp.productID
                
                ORDER BY v.valueID ASC";
                
                
                $arr = $db->query($sql, (int)$gID, 'ARRAY_A');
                $db->close();

                if (!empty($arr[0])) {
                    return $arr;   
                }
                
                
            }
            return array();
        }
        
        public function populateImages($path = '',$clientName = '',$galleryName = '',$gID = -1) {

            if ($path !== '' && $clientName !== '' && $galleryName !== '' && (int)$gID > 0) {
                

                $images = array();
                // open the directory

                if (strpos(strrev($path),'/') > 1) {
                 
                    $path .= '/';
                    
                }

                $path = str_replace(' ', '_', $path);
                
                $dir = @opendir($path);

                if ($dir === false) {
                    die('false');
                    
                }
                
                while (false !== ($fName = readdir( $dir ))) {
                    if (!is_dir($fName) && str_replace(array('.','/'),array('',''),$fName) !== '' && strpos($fName,'.') > 1) {
                            $images[] = $fName;
                    }
                }
                
                closedir( $dir );

                //insert into the db the number of images for this gallery!
                $db = data::instantiate();
                $db->delete("DELETE FROM galleryimages WHERE gID = ?",'?',(int)$gID);
                $db->delete("DELETE FROM gallerysetlink WHERE sID IN (SELECT sID FROM gallerysets WHERE gID = ?)",'?',(int)$gID);
                $db->delete("DELETE FROM gallerysets WHERE gID = ?",'?',(int)$gID);
                $db->close();
                
                
                if (!empty($images)) {
                    $sql = '';
                    $tot = (int)0;
                    foreach ($images as $k => $i) {

                        // parse path for the extension
                        $imageTool = new image($i,$clientName,$galleryName,'photoview');
                        
                        if ($imageTool->isImage()) {
                            $imageTool->resize(false,false,false); 
                            $sql .= "('" . $i . "', '" . $gID . "'),";
                            $tot++;
                        }
                        unset($imageTool);
                        
                        
                    }
                    if ($tot > 0) {
                    
                        $sql = substr($sql,0,strlen($sql) - 1);
                        $sql = 'INSERT INTO galleryimages (iName,gID) VALUES ' . $sql;
			$db = data::instantiate();
                        $db->create($sql, array('?'), array((int)$gID));
		    	$db->close();

                    }
                
                    return (int)$tot;
                
                } else {

                    return (int)0;   
                }
                
            }
            
            return array();
        }
        
     
        public function deleteGallery($g = -1, $deleteSEO = false) {

            if ((int)$g > 0) {

                $env = new environment();

                //check permissions on the galleries folder first:

                $db = data::instantiate();

                //get the gallery name

                $gname = $db->query("SELECT gName FROM galleries WHERE gID = ?", (int)$g, 'ARRAY_A');


                if (!empty($gname[0]['gName'])) {
                
                    //get the client name as this leads to the gallery path!

                    $cname = $db->query("SELECT cName FROM galleries AS g LEFT JOIN clients AS c ON c.cID = g.cID WHERE g.gID = ?", (int)$g, 'ARRAY_A');

                    if (!empty($cname[0]['cName'])) {
                        
                        $path = $env->imageDir() . strtolower(str_replace(' ', '_', $cname[0]['cName'])) . '/' . strtolower(str_replace(' ', '_', $gname[0]['gName'])) . '/';
                        
                        unset($cname);
                        unset($gname);
                        
                        $e = '';
                        
                        if ($path == $env->imageDir()) {
                            header('location: ../');
                        }
                        
                        if (file_exists($path)) {
                            
                            $perm = substr(base_convert(fileperms($path), 10, 8), 2);
                            
                            if ($perm !== '777') {
                                $e .= ($e =='')?'':'<br/>';
                                $e .= 'There was an error trying to remove the directory: ' . $path . '. You may want to delete them via ftp or set the permissions on the directory to 0777. Remember to come back here after! <a href="../' . '">Go Back</a>';
                            }
                        }
                        
                        $db->delete("DELETE FROM galleries WHERE gID = ?", '?', (int)$g);
                        
                        $db->delete("DELETE FROM galleryimages WHERE gID = ?", '?', (int)$g);
                        
                        //get the gallerysetids
                        $s = $db->query("SELECT GROUP_CONCAT(sID) AS s FROM gallerysets WHERE gID = ?", (int)$g, 'ARRAY_A');
                        
                        //now remove all records from the gallery sets
                        
                        $db->delete("DELETE FROM gallerysets WHERE gID = ?", '?', (int)$g);
                        
                        //now remove all records from the gallerysetlink
                        $db->delete("DELETE FROM gallerysetlink WHERE sID IN (?)", '?', $s[0]['s']);
                        
                        //delete from any products linked to these galleries
                        $db->delete("DELETE FROM productstogalleries WHERE gID = ?", '?', (int)$g);
                        $db->close();
                        
                        
                        if ($deleteSEO === true) {
                            Loader::model('seo/seo');
                            seoModel::deleteUrls((int)$g, 'g');
                        }
                        
                        //remove the directory for the gallery
                        
                        //cycle through the directory and remove all files and directories!
                        $e .= ($e =='')?'':'<br/>';
                        $e .= galleriesModel::iterateDirectories($path);
                        
                        if ($e !== '') {
                            die('There were a few problems: <br/>' . $e);
                        }
                        
                    } else {
                        $e = 'This gallery has already been removed. Redirecting in <script type="text/javascript">document.write(\'<p id="time">5</p>\'); var t = 5; var tm = setInterval(function () {if (t == 0) {clearInterval(tm);history.go(-1);} else {t--;document.getElementById(\'time\').innerHTML = t;}}, 1000);</script>';
                        echo $e;
                        die();
                        
                    }
                    
                } else {
                 
                    $e = 'This gallery has already been removed. Redirecting in <script type="text/javascript">document.write(\'<p id="time">5</p>\'); var t = 5; var tm = setInterval(function () {t--; if (t == 0) {clearInterval(tm);history.go(-1);} else {t--;document.getElementById(\'time\').innerHTML = t;}}, 1000);</script>';
                    echo $e;
                    die();
                }
            }
        }
        
        
        public static function iterateDirectories($path) {
            if (substr(strrev($path), 0, 2) == '//') {
                return;   
            }
            set_time_limit(0);
            
            if (file_exists($path)) {
            
                
                
                $e .= ($e =='')?'':'<br/>';
                $perm = substr(base_convert(fileperms($path), 10, 8), 2);
                
                if ($perm !== '777') {
                    
                    return 'There was an error trying to remove the directory: ' . $path . '. You may want to delete them via ftp or set the permissions on the directory to 0777. Remember to come back here after! <a href="../' . '">Go Back</a>';
                    
                }
                
                $dir = @opendir($path);
                if ($dir === false) {
                    
                    return 'There was an error trying to remove the directory: ' . $path . '. You may want to delete them via ftp. <a href="../' . '">Go Back</a>';
                    
                }
                
                
                
                $dirs = array();
                
                while (false !== ($fName = readdir( $dir ))) {
                    
                    
                    if ($fName !== './' && $fName !== '../' && $fName !== '..' && $fName !== '.') {
                        
                        if (!is_dir($path . '/' . $fName)) {
                            $perm = substr(base_convert(fileperms($path . '/' . $fName), 10, 8), 2);
                            
                            
                            if ($perm !== '777') {
                                
                                try {
                                    chmod($path . '/' . $fName, 0777);
                                } catch (Exception $ex) {
                                    $e .= ($e =='')?'':'<br/>';
                                    
                                    $e .= 'There was an error trying to remove the directory: ' . $path . '. You may want to delete them via ftp or set the permissions on the directory to 0777. Remember to come back here after! <a href="../' . '">Go Back</a>';
                                }
                                
                            }
                            

                            
                            unlink($path . '/' . $fName);
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
                                $e = 'There was an error trying to remove the directory: ' . $d . '. You may want to delete them via ftp or set the permissions on the directory to 0777. Remember to come back here after! <a href="../' . '">Go Back</a>';
                            }
                            
                        }
                        $e .= ($e =='')?'':'<br/>';
                        $e .= galleriesModel::iterateDirectories($d);
                        
                        if ($e !== '') {
                            return $e;   
                        }   
                    }
                }
                
                rmdir($path);

            }
            return '';
        }
        
        
        public function activate($g = -1) {
            if ((int)$g > 0) {
             
                $db = data::instantiate();
                
                $db->create("UPDATE galleries SET active = 1 WHERE gID = ?", '?', (int)$g);
                $db->close();

            }
        }

        public function deactivate($g = -1) {
            if ((int)$g > 0) {
                
                $db = data::instantiate();
                
                $db->create("UPDATE galleries SET active = 0 WHERE gID = ?", '?', (int)$g);
                $db->close();

            }
        }
        
        
        public function getImages($cID = -1, $gID = -1, $start = 0, $end = 100) {
            $db = data::instantiate();
            $sql = 'SELECT gi.iID, gi.iName, c.cName, g.gName FROM galleryimages AS gi INNER JOIN galleries AS g ON (gi.gID = g.gID)
                       
            LEFT JOIN clients AS c ON c.cID = g.cID
            
            WHERE g.cID = ? AND g.gID = ? ORDER BY gi.iName ASC';
            
            

            
            if ((int)$start >= 0) {
                if ((int)$end >= 0) {
                    $sql .= ' LIMIT ' . (int)$start . ', ' . (int)$end ;
                } else {
                    $sql .= ' LIMIT ' . (int)$start;
                }
            } elseif ($start > -1) {
                $sql .= ' LIMIT 0, 100';   
            }
            


            $arr = $db->query($sql, array($cID, $gID, time()), 'ARRAY_A');
            $db->close();
            return $arr;
        }
        
        
        public function countImages($cID = -1, $gID = -1) {
            $db = data::instantiate();
            
            
            
            $sql = 'SELECT COUNT(*) AS c FROM galleryimages AS gi INNER JOIN galleries AS g ON (gi.gID = g.gID) WHERE cID = ? AND g.gID = ?';
            
            $arr = $db->query($sql,array($cID, $gID), 'ARRAY_A');
            
            $db->close();

            
            if (empty($arr[0])) {
                return (int)0;
            }
            
            return (int)$arr[0]['c'];
            
        }

        
        public function getImageInfo($ids = array(), $gID = -1) {

            $arr = array();
            if ((int)$gID > 0 && !empty($ids) && is_array($ids)) {
                
                $db = data::instantiate();
                
                $sql = "SELECT c.cName, g.gName, gi.iName, gi.iID FROM galleryimages AS gi
                
                LEFT JOIN galleries AS g ON g.gID = gi.gID
                
                LEFT JOIN clients AS c ON c.cID = g.cID
                
                WHERE gi.iID IN (?) AND g.gID = ?
                
                ";
                
                $idStr = '';
                foreach ($ids as $k => $id) {
                    if ((int)$id > 0) {
                        $idStr .= ($idStr == '')?$id:', ' . $id;
                    } else {
                        $idStr .= ($idStr == '')?$k:', ' . $k;   
                    }
                }
                
                
                $arr = $db->query($sql, array($idStr, (int)$gID), 'ARRAY_A');
                $db->close();
   
            }
            
            return $arr;
            
        }

        public function deleteImage($id = -1) {
            if ((int)$id > 0) {
                $db = data::instantiate();
                
                $sql = "DELETE FROM galleryimages WHERE iID = ?";
                
                
                $db->delete($sql, '?', $id);
                $db->close();

            }
        }
        
    }
