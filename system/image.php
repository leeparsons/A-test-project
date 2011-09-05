<?php
    
    class image {
        
        private $_image;
        private $_cName;
        
        private $imageOriginal;
        private $typeOriginal;
        private $heightOriginal = 0;
        private $widthOriginal = 0;
        private $localImage = false;
        private $fileExists = false;
        private $imagePath;
        private $imageName;
        private $imageExtension;
        private $clientPath;
        private $fileSystem;
        private $section;
        private $savePath;
        private $friendlyName;
        private $_isImage = true;
        
        private $imageSizes = array(
                                    'clientfront'       =>  array('width'    =>    170,'height'    =>    170,'quality'    =>    100, 'prepend'   =>  false),
                                    'clientfrontadmin'  =>  array('width'    =>    100,'height'    =>    100,'quality'    =>    100, 'prepend'   =>  false),
                                    'landingadmin'      =>  array('width'    =>    100,'height'    =>    100,'quality'    =>    100, 'prepend'   =>  false),
                                    'splashadmin'       =>  array('width'    =>    50,'height'    =>    50,'quality'    =>    100, 'prepend'   =>  false),
                                    'front'             =>  array('width'    =>    170,'height'    =>    170,'quality'    =>    100, 'prepend'   =>  false),
                                    'splash'            =>  array('width'    =>    494,'height'    =>    494,'quality'    =>    100, 'prepend'   =>  false),
                                    'gallery'           =>  array('width'    =>    150,'height'    =>    150,'quality'    =>    100, 'prepend'   =>  true),
                                    'photoview'         =>  array('width'    =>    450,'height'    =>    450,'quality'    =>    100, 'prepend'   =>  true),
                                    'fancy'             =>  array('width'    =>    800,'height'    =>    800,'quality'    =>    100, 'prepend'   =>  true),
                                    'navigation'        =>  array('width'    =>    100,'height'    =>    100,'quality'    =>    100, 'prepend'   =>  true),
                                    'cart'              =>  array('width'    =>    50,'height'    =>    50,'quality'    =>    100, 'prepend'   =>  true),
                                    'landing'           =>  array('width'    =>    350,'height'    =>    350,'quality'    =>    100, 'prepend'   =>  false),
                                    'local'             =>  array('width'    =>    350,'height'    =>    350,'quality'    =>    100, 'prepend'   =>  true),
                                    'localthumb'        =>  array('width'    =>    100,'height'    =>    100,'quality'    =>    100, 'prepend'   =>  true)
                                    
                                    
                                    );
        
        public function __construct($image = '', $cName = '', $fileSystem = '', $section = '') {
            
            if ($image !== '') {
                if (strpos($image, '.') < 1) {
                    
                    $this->_isImage = false;
                    
                    return false;
                }
            } else {
                $this->_isImage = false;
                return false;
            }
            
            global $env, $urls;
            
            $this->_image = $image;
            
            $this->_cName = str_replace(' ', '_', strtolower($cName));
            
            
            if ($section == '') {
                $this->section = strtolower($urls->getFileSystem());
            } else {
                $this->section = strtolower($section);
            }
            
            if ($fileSystem == '') {
                $fileSystem = strtolower($urls->getFileSystem());
                if ($fileSystem == '') {
                    //assume we're in the front
                    $this->fileSystem = 'front';
                } else {
                    $this->fileSystem = strtolower($fileSystem);
                }
            } else {
                $this->fileSystem = $fileSystem;
            }

            $this->clientPath = $env->imageDir() . strtolower($this->_cName) . '/';
            $this->imagePath = $this->clientPath . strtolower(str_replace(' ', '_', $fileSystem)) . '/';

            if (empty($this->imageSizes[$fileSystem])) {
                if ($this->imageSizes['gallery']['prepend'] === true) {
                    $this->savePath = $this->clientPath . $fileSystem . strtolower($this->section) . '/';
                } else {
                    $this->savePath = $this->clientPath . strtolower($this->section) . '/';   
                }   
            } else {
                if ($this->imageSizes[$fileSystem]['prepend'] === true) {
                    $this->savePath = $this->clientPath . $fileSystem . strtolower($this->section) . '/';
                } else {
                    $this->savePath = $this->clientPath . strtolower($this->section) . '/';   
                }   
            }
            
            
            
            $bits = explode("/", $image);
            
            $bName = strrev($bits[count($bits) - 1]);
            
            $this->imageExtension = strrev(substr($bName,0,strpos($bName,'.')));
            $this->imageName = strrev(substr($bName,strpos($bName,'.') + 1));
            
            $this->friendlyName = str_replace(array('_','-','.'),array(' ',' ',' '),str_replace('.' . $this->imageExtension,'',$this->imageName));
            //determine if remote or local image
            
            
            if (stripos('http',$image)) {
                $this->localImage = false;
            } else {
                $this->localImage = true;   
            }
            
            

            if (file_exists($this->imagePath . $this->imageName . '.' . $this->imageExtension)) {
                $this->fileExists = true;
            } else {
                $this->fileExists = false;   
            }
            
            
            
        }
        
        public function deleteOld() {
            //this function is normally caled when uploading a new image to overrite an old one. So need to remove the old one, which should be stored in image!
            $path = $this->savePath;

            
            if (substr($path, strlen($path)-1) == '/') {
                $path = substr($path, 0, strlen($path)-1);   
            }
            
            $image = $this->_image;
            
            $ext = substr(strrev($image), 0, stripos(strrev($image), '.'));

            $ext = '.' . strrev($ext);
            
            $image = str_replace(' ', '_', substr($image, 0, strlen($image) - strlen($ext)));
            
            
            //file exists locally so do whatever
            if ((int)$this->widthOriginal == 0 || (int)$this->heightOriginal == 0) {
                $this->getImageInfo();
            }          
            
            foreach ($this->imageSizes as $size => $v) {
                $width = $v['width'];
                $height = $v['height'];
                if ($this->heightOriginal > 0 && $this->widthOriginal/$this->heightOriginal > 1) {
                    //landscape
                    $scale = number_format($width/$this->widthOriginal,2);
                    $height = number_format($scale * $this->heightOriginal,2);
                    
                } else {
                    //portrait
                    
                    $scale = number_format($height/$this->heightOriginal,2);
                    $width = number_format($scale * $this->widthOriginal,2);
                }
                
                $imgPath = $path . $size . '/' . $image . '_' . $height . '_' . $width . $ext;

                if (file_exists($imgPath)) {

                    //unlink the image

                    unlink($imgPath);
            
                }

            
            }
            
            //now remove the original
            if (file_exists($this->imagePath . $this->_image)) {
                unlink($this->imagePath . $this->_image);   
            }
        }
        
        public function upload() {
            //a very specific function designed to work only on uploading images!
            
            if (file_exists($this->imageOriginal)) {
                $this->makePath($this->imagePath);
                return move_uploaded_file($this->imageOriginal,$this->imagePath . strtolower(str_replace(' ','_',$this->_image)));
            } else {
                return true;
            }
        }
        
        public function setOriginal($image) {
            $this->imageOriginal = $image;    
        }
        
        public function isImage() {
            return $this->_isImage;   
        }
        
        public function updateSystem($system) {
            $this->fileSystem = $system;   
        }
        
        public function updateSection($section) {
            $this->savePath = $this->clientPath . $section . '/';
            $this->section = $section;
        }
        
        public function getNone($fileSystem = '') {
            global $urls;
            
            
            if ($fileSystem == '') {
                $fileSystem = $urls->getFileSystem();
                if ($fileSystem == '') {
                    //assume we're in the front
                    $fileSystem = 'front';
                } else {
                    $fileSystem = $fileSystem;
                }
            } else {
                $fileSystem = $fileSystem;
            }
            
            global $controller;
                        
            $url = $controller->url;
            
            
            if (substr(strrev($url), 0, 1) == '/') {
                
                $url = substr($url, 0, strlen($url) - 1);
                
            }
            
            $found = false;
            
            foreach ($this->imageSizes as $k => $v) {
                if ($k == $fileSystem) {
                    $found = true;
                    break;   
                }
            }
            
            
            if (!$found) {
                $fileSystem = 'gallery';   
            }
            
            global $env;
            $height = $this->imageSizes[$fileSystem]['height'];
            $width = $this->imageSizes[$fileSystem]['width'];  
            //create the no image!
            $this->savePath = $env->dir() . 'images/';
            $this->section = $fileSystem;
            $this->image = 'none.jpg';
            $this->localImage = true;
            $this->fileExists = true;
            $this->imageName = 'none';
            $this->imageExtension = 'jpg';
            
            $this->widthOriginal = 250;
            $this->heightOriginal = 250;
            $this->imagePath = $this->savePath;
            
            $img = $this->copyResized($height,$width);
            
            return '<img src="' . $url . '/images/none_' . $height . '_' . $width . '.jpg" alt="No Image"/>';
            
        }
        
        public function isPortrait() {
            if (!$this->validate()) {
                return false;
            }
            if ($this->widthOriginal/$this->heightOriginal < 1) {
                return true;
            }
        }
        
        public function resize($html = false, $original = false, $uploaded = false, $forceCopy = false) {
            if (!$this->_isImage) {
                return false;   
            }
            global $env;
            if (!$this->validate()) {
                return false;
            }
            
            if ($uploaded !== false) {
                
                if (!$this->moveUploaded($uploaded)) {
                    return false;
                }
                
            }
            
            if ($forceCopy === true) {
                
                //try and copy the original file into the new section folder - this is used if we instantiated the image object, and used it to copy and image, and then changed the file system or section
                
                $this->forceCopy();
                
            }
            if ($original === false && $this->fileSystem !== 'fancy') {
                
                $height = $this->imageSizes[$this->section]['height'];
                $width = $this->imageSizes[$this->section]['width'];
                $quality = $this->imageSizes[$this->section]['quality'];
            } elseif ($original  == 'fancy' || $this->fileSystem == 'fancy') {
                $height = $this->imageSizes['fancy']['height'];
                $width = $this->imageSizes['fancy']['width'];
                $quality = $this->imageSizes['fancy']['quality'];
            } else {
                if ($this->widthOriginal == 0 || $this->heightOriginal == 0) {
                    $this->getImageInfo();
                }
                $height = $this->heightOriginal;
                $width = $this->widthOriginal;
                $quality = 100;
            }

            $controller = new Controller();
            
            $url = $controller->url;
            
            
            if (substr(strrev($url), 0, 1) == '/') {
             
                $url = substr($url, 0, strlen($url) - 1);
                
            }
            
            
            if (!$this->localImage) {
                //http image
                if ($this->copyImage()) {
                    
                    if ($this->widthOriginal == 0 || $this->heightOriginal == 0) {
                        $this->getImageInfo();
                    }
                    
                    if ($this->widthOriginal/$this->heightOriginal > 1) {
                        //landscape
                        $scale = number_format($width/$this->widthOriginal,2);
                        $height = number_format($scale * $this->heightOriginal,2);
                        
                    } else {
                        //portrait
                        
                        $scale = number_format($height/$this->heightOriginal,2);
                        $width = number_format($scale * $this->widthOriginal,2);
                    }
                    $returnImage = $this->copyResized($height,$width);
                    
                    if (!$returnImage) {

                        
                        return ($html === true)?'<img src="' . $url . $this->_image . '" alt="' . $this->imageName . '" style="max-height:' . $height . 'px;max-width:' . $width . 'px;"/>':$this->_image;
                    }                    
                    return ($html === true)?'<img src="' . $url . $returnImage . '" alt="' . $this->_cName . '" width="' . $width . '" height="' . $height . '"/>':$returnImage;
                    
                    
                } else {
                    
                    return ($html === true)?'<img src="' . $url  . $this->_image . '" alt="' . $this->imageName . '" style="max-height:' . $height . 'px;max-width:' . $width . 'px;"/>':$this->_image;
                }
                
            } elseif (!$this->fileExists) {
                //TODO


                //need to create the file from the local file system!
                //copy the image from the clientpath to the gallerypath
                if (!$this->copyImage()) {
                    $this->getNone($this->fileSystem);
                    return ($html === true)?'<img src="' . $url . '/images/none_' . $height . '_' . $width . '.jpg" alt="No Image"/>':$url . '/images/none_' . $height . '_' . $width . '.jpg';
                } else {

                    //file exists locally so do whatever
                    if ($this->widthOriginal == 0 || $this->heightOriginal == 0) {
                        $this->getImageInfo();
                    }          
                    $height = $this->imageSizes[$this->section]['height'];
                    $width = $this->imageSizes[$this->section]['width'];
                    $quality = $this->imageSizes[$this->section]['quality'];
                    
                    
                    if ($this->widthOriginal/$this->heightOriginal > 1) {
                        //landscape
                        $scale = number_format($width/$this->widthOriginal,2);
                        $height = number_format($scale * $this->heightOriginal,2);
                        
                    } else {
                        //portrait
                        
                        $scale = number_format($height/$this->heightOriginal,2);
                        $width = number_format($scale * $this->widthOriginal,2);
                    }
                    
                    
                    //see if the local cached resized version exists
                    
                    
                    $returnImage =  $this->copyResized($height,$width);
                    

                    
                    if (!$returnImage) {
                        return ($html === true)?'<img src="'  . $url . $this->_image . '" alt="' . $this->imageName . '" style="max-height:' . $height . 'px;max-width:' . $width . 'px;"/>':$this->_image;
                    }
                    return ($html === true)?'<img src="'  . $url . $returnImage . '" alt="' . $this->_cName . '" width="' . $width . '" height="' . $height . '"/>':$url . $returnImage;
                    
                    
                    
                }
                
            } else {

                
                //file exists locally so do whatever
                if ($this->widthOriginal == 0 || $this->heightOriginal == 0) {
                    $this->getImageInfo();
                }          
                
                if ($this->widthOriginal/$this->heightOriginal > 1) {
                    //landscape
                    $scale = number_format($width/$this->widthOriginal,2);
                    $height = number_format($scale * $this->heightOriginal,2);
                    
                } else {
                    //portrait
                    
                    $scale = number_format($height/$this->heightOriginal,2);
                    $width = number_format($scale * $this->widthOriginal,2);
                }
                
                
                //see if the local cached resized version exists
                
                $returnImage =  $this->copyResized($height, $width);
                if (!$returnImage) {
                    return ($html === true)?'<img src="' . $url . $this->_image . '" alt="' . $this->imageName . '" style="max-height:' . $height . 'px;max-width:' . $width . 'px;"/>':$this->_image;
                }
                //need to remove the first slash from return image!
                return ($html === true)?'<img src="' . $url  . $returnImage . '" alt="' . $this->_cName . '" width="' . $width . '" height="' . $height . '"/>':$url . $returnImage;
                
            }
            
            
            
        }
        
        private function forceCopy() {
            
            //try to copy the image from the original location which should be under the client path into the new section which should be clientpath ../ section
            
            copy($this->imagePath . '/' . $this->imageName . '.' . $this->imageExtension, $this->clientPath . $this->section . '/../' . $this->fileSystem . '/' . $this->imageName . '.' . $this->imageExtension);
            
        }
        
        private function copyImage() {  
            
            //need to determine if the image is local, and if not, then copy it. Otherwise fail
            if (!$this->localImage) {
                if (!$this->makePath()) {
                    return false;                   
                }
                if (copy($this->_image,$this->imagePath . $this->imageName . '.' . $this->imageExtension)) {
                    $this->imageOriginal = $this->_image;
                    $this->localImage = true;
                    $this->_image = $this->imagePath . $this->imageName . '.' . $this->imageExtension;
                    return true;
                }
                
            } else {
                //it is a local image! But we've come in here because the system does not think it has been made
                //so the place we expect it to appear is imagePath but it is not there so default to looking in the current directory:
                
                //this might be an uploaded file! so it's already moved = just need to resize it!

                if (file_exists($this->imagePath . $this->imageName . '.' . $this->imageExtension)) {
                    $this->_image = $this->imagePath . $this->imageName . '.' . $this->imageExtension;
                    $this->fileExists = true;
                    return true;
                } else {
                    //see if the current image exists:
                    if (file_exists($this->imagePath . $this->_image)) {
                        //copy it!
                    }
                    
                }
                
                return false;
            }
            
            return false;
        }
        
        
        private function makePath($path = '') {
            if ($path == '') {
                if (!file_exists($this->savePath)) {
                    if (!mkdir($this->imagePath, 0777, true)) {
                        return false;
                    }
                    chmod($this->imagePath, 0777);
                }
            } else {
                if (!file_exists($path)) {
                    if (!mkdir($path,0777, true)) {
                        return false;
                    }
                    chmod($path, 0777);
                }
            }
            return true;
        }
        
        private function getImageInfo() {
            if (!$this->localImage) {
                $imageInfo = getimagesize($this->_image);
            } else {
                $imageInfo = getimagesize($this->imagePath . $this->imageName . '.' . $this->imageExtension);   
            }
            $this->widthOriginal = $imageInfo[0];
            $this->heightOriginal = $imageInfo[1];
            $this->typeOriginal = $imageInfo['mime'];
        }
        
        private function validate() {
            if ($this->_image !== '') {
                return true;
            } else {
                return false;   
            }
        }
        
        
        private function copyResized($height = 0,$width = 0,$quality = 0,$relative = true) {
            set_time_limit(0);
            
            if ((int)$width > 0 && (int)$height > 0) {
                
                
                if ((int)$quality == 0) {
                    $quality = $this->imageSizes[$this->section]['quality'];
                    
                }
                //resize the image
                
                if ($this->localImage && $this->fileExists) {                                       
                    //first determine what filesystem we're in and see if the resized image name already exists
                    $resizedName = $this->savePath . $this->imageName . '_' . $height . '_' . $width . '.' . $this->imageExtension;

                    if (file_exists($resizedName)) {
                        return ($relative === false)?$resizedName:$this->relativePath($resizedName);
                    } else {
                        $this->makePath($this->savePath);
                        //create the resized image!
                        
                        if ($this->localImage) {
                            $image = $this->imagePath . $this->imageName . '.' . $this->imageExtension;
                        } else {
                            $image = $this->_image;
                        }
                        
                        
                        switch (strtolower($this->imageExtension)) {
                            case 'jpeg':case 'jpg':                                
                                $curImage = imagecreatefromjpeg($image);
                                break;
                            case 'gif':
                                $curImage = imagecreatefromgif($image);
                                break;
                            case 'png':
                                $curImage = imagecreatefrompng($image);
                                break;
                            default:
                                die('There was an error uploading that image. Please supply a jpeg, gif or png only. <a href"../">Go Back</a>');
                                break;
                        }
                        
                        
                        $dest = imagecreatetruecolor($width, $height);
                        
                        // Copy
                        imagecopyresampled($dest, $curImage, 0, 0, 0, 0, $width, $height,$this->widthOriginal,$this->heightOriginal);
                        
                        imagejpeg($dest,$resizedName,$quality);
                        
                        imagedestroy($curImage);
                        imagedestroy($dest);
                        
                        return ($relative === false)?$resizedName:$this->relativePath($resizedName);
                        
                        
                    }
                    
                    
                    
                }                
            }
            return false;
            
        }
        
        public function getImageName() {
            return $this->friendlyName = str_replace(array('_','-','.'),array(' ',' ',' '),str_replace('.' . $this->imageExtension,'',$this->imageName));
        }
        
        public function getActualName() {
            return $this->imageName . '.' . $this->imageExtension;   
        }
        
        private function relativePath($path) {
            global $env;
            
            return '/' . str_replace($env->dir(),'',$path);
        }
        
        private function moveUploaded($tmp = '') {
            
            if ($tmp !== '') {
                
                if (file_exists($tmp)) {
                    
                    //move
                    $this->makePath($this->imagePath);
                    move_uploaded_file($tmp, $this->imagePath . $this->imageName . '.' . $this->imageExtension);
                    return true;
                    
                }
                
                
            }
            
            
            return false;   
            
        }
        
        
        public function renameDirectory($original = '',$new = '') {
            if ($original !== '' && $new !== '') {
                
                //figure out if the original exists:
                
                
                
                $original = str_replace(' ','_',strtolower($original));
                
                if (file_exists($this->clientPath) && !file_exists($this->clientPath . str_replace(' ','_',strtolower($new)))) {
                    rename($this->clientPath . $original,$this->clientPath . str_replace(' ','_',strtolower($new)));
                    return true;
                } else {
                    return false;   
                }
                
                
            } else {
                return false;   
            }
        }
        
        public function remove() {
            if (file_exists($this->savePath . $this->imageName . '/' . $this->imageExtension)) {
                unlink($this->savePath . $this->imageName . '/' . $this->imageExtension);
            }
        }
        
        
    }
