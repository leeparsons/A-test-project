<?php

    class photoviewModel {
        
        
        public function getImage($cID = -1, $gID = -1, $iID = -1) {
            $db = data::instantiate();
            $sql = 'SELECT * FROM galleryimages AS gi INNER JOIN galleries AS g ON (gi.gID = g.gID) WHERE cID = ? AND g.gID = ? AND iID = ? AND (gExpiry > ? OR noexpiry = 1) AND active = 1';
            $image = $db->query($sql,array($cID,$gID,$iID,time()),'ARRAY_A');
            $db->close();
            return $image[0];
        }
        
        
        
        public function getGalleryNav($iID = -1, $gID = -1, $cID = -1) {
            
            //if the set does not yet exist for this gallery then we need to create them
            
            $db = data::instantiate();

            $setId = $db->query("SELECT gs.sID FROM gallerysets AS gs LEFT JOIN galleries AS g ON g.gID = gs.gID LEFT JOIN gallerysetlink AS gsl ON gsl.sID = gs.sID WHERE g.gID = ? AND gsl.iID = ? ORDER BY sID DESC", array((int)$gID, (int)$iID), 'ARRAY_A');

            if (empty($setId[0])) {
                //sets do not exist so create them:
                
                $db->query("DELETE FROM gallerysetlink WHERE sID IN (SELECT sID FROM gallerysets WHERE gID = " . (int)$gID . ")");
                $db->query("DELETE FROM gallerysets WHERE gID = " . (int)$gID);
                
                //now get all the images from the gallery and create the gallery navigation
                
                Loader::model('splash/gallery');
                
                $images = galleryModel::getImages($cID, $gID, -1, -1);
                
                if (!$images) {
                    $db->close();
                    return false;
                }

                $nImages = count($images);
                
                //now we need to figure out the pagination = results per gallery = 16
                
                $max = (int)16;
                
                $i = (int)1;
                $html = '';

                foreach ($images as $image) {

                    if ($i == 1) {
                        //create the image set
                        
                        $sID = $db->create("INSERT INTO gallerysets (gID) VALUES (?)", '?', (int)$gID, true);
                        //check the image set was created  
                       
                        if (!$sID) {
                            $db->close();
                            return false;
                        }
                        
                    }
                    
                    
                    //now insert the image ids into the gallerysetlink table

                    $db->create("INSERT INTO gallerysetlink (sID, iID) VALUES (?, ?)", array('?', '?'), array((int)$sID, (int)$image['iID']));
                    flush();
                    if ($i == 16) {
                        $i = 0;   
                    }
                    
                    $i++;
                }
                
                $setId = $db->query("SELECT gs.sID FROM gallerysets AS gs LEFT JOIN galleries AS g ON g.gID = gs.gID LEFT JOIN gallerysetlink AS gsl ON gsl.sID = gs.sID WHERE g.gID = ? AND gsl.iID = ? ORDER BY sID DESC", array((int)$gID, (int)$iID), 'ARRAY_A');
            }
              
            if (empty($setId[0])) {

            } else {
                
                //get the current set from the setId!
             
                $arr = $db->query("SELECT gi.iName,gi.iID,sID FROM gallerysetlink AS gs LEFT JOIN galleryimages AS gi ON gi.iID = gs.iID WHERE sID = ? ORDER BY gi.iName ASC", (int)$setId[0]['sID'], 'ARRAY_A');
                $db->close();
                return $arr;
                
            }
            $db->close();
            return false;
            
        }
        
        
        public function getPreviousImageSetImage($sID = -1,$gID = 1) {
            $db = data::instantiate();

            $sql = "SELECT gsl.iID FROM gallerysets AS gs
            
            LEFT JOIN gallerysetlink AS gsl ON gsl.sID = gs.sID
            
            LEFT JOIN galleryimages AS gi ON gi.iID = gsl.iID
            
            WHERE gs.gID = ? AND gs.sID < ?
            
            ORDER BY gs.sID DESC, gi.iName ASC LIMIT 1";
            
            
            $res = $db->query($sql, array((int)$gID,(int)$sID),'ARRAY_A');           
            $db->close();
            if (!$res || !is_array($res) || empty($res)) {
                return (int)0;
            }
            
            return $res[0]['iID'];
            
        }
        
        
        public function getNextImageSetImage($sID = -1,$gID = 1) {
            $db = data::instantiate();

            $sql = "SELECT gsl.iID FROM gallerysets AS gs
            
            LEFT JOIN gallerysetlink AS gsl ON gsl.sID = gs.sID
            
            LEFT JOIN galleryimages AS gi ON gi.iID = gsl.iID
            
            WHERE gs.gID = ? AND gs.sID > ?
            
            ORDER BY gs.sID,gi.iName ASC LIMIT 1";

            
            $res = $db->query($sql, array((int)$gID, (int)$sID), 'ARRAY_A');
            $db->close();
            if (!$res || !is_array($res) || empty($res)) {
                return (int)0;
            }
            
            return $res[0]['iID'];
            
        }

        
        public function getFirstGallery($sID = -1,$gID = 1) {
            $db = data::instantiate();
            
            $sql = "SELECT gsl.iID AS p,gs.sID AS s
            
            FROM gallerysets AS gs
            
            LEFT JOIN gallerysetlink AS gsl ON gsl.sID = gs.sID
            
            LEFT JOIN galleryimages AS gi ON gi.iID = gsl.iID
            
            WHERE gs.gID = ? AND gs.sID <= ?
            
            ORDER BY gs.sID,gi.iName ASC LIMIT 1";

            
            $res = $db->query($sql, array((int)$gID,(int)$sID),'ARRAY_A');
            $db->close();

            if (!$res || !is_array($res) || empty($res)) {
                return (int)0;
            }
            
            return $res[0];
            
        }
        
        public function getPrevGallery($sID = -1, $gID = -1) {
            $db = data::instantiate();

            $sql = "SELECT gi.iID AS p FROM galleryimages AS gi
            
            LEFT JOIN gallerysetlink AS gsl ON gsl.iID = gi.iID
            
            LEFT JOIN gallerysets AS gs ON gs.sID = gsl.sID
            
            WHERE gi.gID = ? AND gsl.sID < ?
            
            ORDER BY gi.iID DESC LIMIT 1";
            
            $arr = $db->query($sql, array((int)$gID, (int)$sID) , 'ARRAY_A');
            $db->close();
            if (empty($arr)) {return array('p' => 1);}
            return $arr[0];
            
            
        }
        
        
        public function getNextGallery($sID = -1, $gID = -1) {
            $db = data::instantiate();
            
            $sql = "SELECT gi.iID AS p FROM galleryimages AS gi
            
            LEFT JOIN gallerysetlink AS gsl ON gsl.iID = gi.iID
            
            LEFT JOIN gallerysets AS gs ON gs.sID = gsl.sID
            
            WHERE gi.gID = ? AND gsl.sID > ?
            
            ORDER BY gi.iID ASC LIMIT 1";
            
            $arr = $db->query($sql, array((int)$gID, (int)$sID) , 'ARRAY_A');
            if (empty($arr)) {
                //return the last image
                $sql = "SELECT gi.iID AS p FROM galleryimages AS gi
                
                LEFT JOIN gallerysetlink AS gsl ON gsl.iID = gi.iID
                
                LEFT JOIN gallerysets AS gs ON gs.sID = gsl.sID
                
                WHERE gi.gID = ?
                
                ORDER BY gi.iID DESC LIMIT 1";
                
                $arr = $db->query($sql, $gID, 'ARRAY_A');
                $db->close();
                return array('p' => $arr[0]['p']);
            }
            
            $db->close();
            return $arr[0];
            
            
        }
        
        
        public function getLastGallery($gID = 1) {
            $db = data::instantiate();
            
            $sql = "SELECT gsl.iID AS p, gs.sID AS s
            
            FROM gallerysets AS gs
            
            LEFT JOIN gallerysetlink AS gsl ON gsl.sID = gs.sID
            
            LEFT JOIN galleryimages AS gi ON gi.iID = gsl.iID
            
            WHERE gs.gID = ? AND gs.sID = (SELECT sID FROM gallerysets WHERE gID = ?
            
            ORDER BY sID DESC LIMIT 1) ORDER BY gi.iName ASC LIMIT 1";
            
            $res = $db->query($sql,array((int)$gID,(int)$gID),'ARRAY_A');
            $db->close();
            if (!$res || !is_array($res) || empty($res)) {
                return (int)0;
            }
            
            return $res[0];
            
        }
        
    }