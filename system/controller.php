<?php

    class controller {
        public $url;
        public $admUrl;
        public $template = '';
        public $css = array();
        public function __construct() {
            $db = data::instantiate();
			$settings = $db->query("SELECT * FROM settings", 'ARRAY_A');

			if (!empty($settings)) {
                foreach ($settings as $key => $arr) {
					if (!empty($arr)) {
						foreach ($arr as $k => $str) {
							$this->{$k} = $str;
						}
					} else {
						$this->redirect('install');
					}
				}

                if (substr($this->url, strlen($this->url) - 1, 1) !== '/') {
                    $this->url .= '/';   
                }
                
                $this->admUrl = $this->url . $this->admUrl;
                
                if (substr($this->admUrl, strlen($this->admUrl) - 1, 1) !== '/') {
                    $this->admUrl .= '/';   
                }
                
                $this->fullUrl = 'http://' . $_SERVER['SERVER_NAME'] . $this->url;


                $maxUploadSize = ini_get('upload_max_filesize');
                
                switch (strtolower(substr($maxUploadSize, strlen($maxUploadSize) - 1))) {
                    case 'g':
                        if ((int)$maxUploadSize > (8) || (int)$maxUploadSize == 0) {
                            $this->maxUploadSize = '8 GB';   
                        } else {
                            $this->maxUploadSize = $maxUploadSize . 'B';                            
                        }
                        break;
                    case 'k';
                        if ((int)$maxUploadSize > 8*1024 || (int)$maxUploadSize == 0) {
                            $this->maxUploadSize = '8 MB';   
                        } else {
                            $this->maxUploadSize = $maxUploadSize . 'B';                            
                        }                        
                        break;
                    default:
                        if ((int)$maxUploadSize > 8 || (int)$maxUploadSize == 0) {
                            $this->maxUploadSize = '8 MB';   
                        } else {
                            $this->maxUploadSize = $maxUploadSize . 'B';                            
                        }
                        break;
                
                }
                
                $this->cancelLink = $this->admUrl . 'clients/clients/';
				
			}
            $db->close();
        }
        


        
        public function getVar($var) {
            return $this->{$var};
        }
        
                
        public function request($method = 'request') {
            switch (strtolower($method)) {
                case 'request': 
                    return $_REQUEST;
                    break;
                case 'post':
                    return $_POST;
                    break;
                case 'get':
                    return $_GET;
                    break;
                default:
                    return array();
            }
                    
            
        }

    
        public function redirectAdm($turl = '') {
            if (substr($turl,0,1) == '/') {
                $turl = substr($turl,1);
            }
            
            header('location: ' . $this->admUrl . $turl);            

            die();
        }
        
        public function redirect($turl = '') {
            
            if (substr($turl,0,1) == '/') {
                $turl = substr($turl,1);
            }
            header('location: ' . $this->url . $turl);            
            die();
        }

        protected function decompressZip($zip, $path) {

            //figure out if the zip file is a zip extension:

            if (substr(strrev($zip['name']), 0, 4) == 'piz.') {
                if (!stripos(strrev($path), '/') == 0) {
                    $path .= '/';
                }
                
                if (!move_uploaded_file($zip['tmp_name'], $path . 'zipper.zip')) {
                    return 'Not a valid zip file.';
                }
                
                $zipFile = zip_open($path . 'zipper.zip');
                
                while ($contents = zip_read($zipFile)) {
                    
                    $entryName = basename(zip_entry_name($contents));
                    if (substr($entryName, 0, 1) !== '.' && $entryName !== '__MACOSX') {
                        $fp = fopen($path . basename($entryName), "w+");
                    
                        if (zip_entry_open($zipFile, $contents, "r")) {
                        
                            $buf = zip_entry_read($contents, zip_entry_filesize($contents));
                            zip_entry_close($contents);
                        
                        }
                    
                        fwrite($fp, $buf);
                    
                        fclose($fp);
                        unset($buf);
                    }
                }
                
                zip_close($zipFile);
                
                
                unlink($path . 'zipper.zip');
                return true;

            } else {
                return 'The uploaded file is not a valid zip folder.';   
            }
        }
    
    
        
        public function setVar($key, $var) {
            $this->{$key} = $var;
            echo $this->{$key};
        }
    }

