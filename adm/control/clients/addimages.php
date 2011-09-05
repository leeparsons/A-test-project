<?php
    
    class addimages extends controller {
        public $cError = '';
        
        public function index() {

            $this->loggedIn();
            
            $data = $this->request('get');
            
            if (isset($data['g']) && isset($data['c']) && (int)$data['g'] > 0 && (int)$data['c'] > 0) {
                
                
                if ($this->seoUrls == 1) {
                
                    Loader::model('seo/seo');

                    $galleryUrl = seoModel::getUrl((int)$data['g'], 'g');
                    if ($galleryUrl !== '') {
                        $this->viewGalleryLink = $this->url . $galleryUrl;
                    } else {
                        $this->viewGalleryLink = $this->url . 'splash/gallery/?g=' . $data['g'] . '&amp;c=' . $data['c']; 
                
                    }
                }
                $this->clientLink = $this->admUrl . 'clients/edit/?c=' . $data['c'];
                $this->galleryLink = $this->admUrl . 'clients/gallery/?g=' . $data['g'] . '&amp;c=' . $data['c'];

                
                
                //get the information for this client:
                
                Loader::model('clients/galleries');
                
                
                $info = galleriesModel::getGalleryInfo((int)$data['g'], (int)$data['c']);

                $this->total = galleriesModel::countImages((int)$data['c'], (int)$data['g']);
                
                if (empty($info)) {
                    $this->redirectAdm('clients/clients/');
                }
                

                Loader::model('clients/clients');

                $this->name = ucwords($info['gName']);
                $this->client = ucwords($info['cName']);
                $info2 = clientsModel::getClients((int)$data['c']);

                $this->action = $this->admUrl . 'clients/addimages/upload/';

                
                $imageTool = new image($info2[0]['cFrontImage'], $info['cName'], $info['gName'], 'splash', 'slash');

                
                $image = $imageTool->resize(true);
                
                
                if ($image == '') {
                    $image = $imageTool->getNone();   
                }
                    

                unset($imageTool);
                $this->cid = (int)$data['c'];
                $this->g = (int)$data['g'];
                $this->image = $image;
                
                $this->loader = $this->admUrl . 'images/loader.gif';
                
                Loader::model('upload/flashuploader');
                
                
                /*
                 $uploader = new FlashUploader('uploader', $this->admUrl . 'js/uploader', $this->admUrl . 'clients/addimages/flashuploader/');
                 
                 
                 $uploader->pass_var('g', $data['g']);
                 
                 $uploader->pass_var('c', $data['c']);
                 
                 $this->uploader = $uploader;
                 
                 */
                
                $this->swfName = $this->admUrl . 'js/uploader';
                
                $this->uploadUrl = $this->admUrl . 'clients/addimages/flashuploader/?g=' . $this->g . '&c=' . $this->cid;
                
                $this->button = $this->admUrl . 'images/button.png';
                
                $this->expressIntsaller = $this->admUrl . 'js/expressInstall.swf';
                
                $this->js[] = $this->admUrl . 'js/swfobject.js';
                $this->js[] = $this->admUrl . 'js/swfupload.js';
                $this->js[] = $this->admUrl . 'js/queue.js';
                $this->js[] = $this->admUrl . 'js/fileprogress.js';
                $this->js[] = $this->admUrl . 'js/handlers.js';
                
                $this->reScan = $this->admUrl . 'clients/gallery/rescan/?g=' . $this->g . '&amp;c=' . $this->cid;
                $this->reScanFlat = $this->admUrl . 'clients/gallery/rescan/';
                $this->reScanParams = "g:'" . $this->g . "', c:'" . $this->cid . "', d:'" . time() . "'";
                
                
                
            } else {
                $this->redirectAdm('clients/clients/');   
            }
            
        }
        

        public function flashuploader() {



		    $data = $this->request('request');
            Loader::model('logs/logs');


            if (isset($data['g']) && (int)$data['g'] > 0 && isset($data['c']) && (int)$data['c'] > 0) {


                $filename	= $_FILES['Filedata']['name'];
                $temp_name	= $_FILES['Filedata']['tmp_name'];
                $error		= $_FILES['Filedata']['error'];
                $size		= $_FILES['Filedata']['size'];
                
                
                if (!$error) {
        	        Loader::model('clients/galleries');
	                $info = galleriesModel::getGalleryInfo((int)$data['g'], (int)$data['c']);
                   $env = new Environment();
                    $path = $env->imageDir() . strtolower(str_replace(' ', '_', $info['cName'])) . '/' . strtolower(str_replace(' ', '_', $info['gName'])) . '/';
	                if (!move_uploaded_file($temp_name, $path . $filename)) {
                        echo '1';
                    } else {
			chmod($path . $filename, 0777);
                        echo '0';
                    }
                    
                    
                }
                
            }
            $this->noRender = true;
        }

        public function upload() {
           // $this->loggedIn();

            $this->doUpload();
            
        }
        
        private function doUpload() {

            $data = $this->request('post');

//global $db;

//$db->create("INSERT INTO logs SET stuff = '" . print_r($data, true) . "'");

            if (isset($data['g']) && (int)$data['g'] > 0 && isset($data['c']) && (int)$data['c'] > 0) {

                
                $this->cid = (int)$data['c'];
                $this->g = (int)$data['g'];
                
                Loader::model('clients/galleries');

                $info = galleriesModel::getGalleryInfo((int)$data['g'], (int)$data['c']);



                if (!empty($info)) {
                    
                    $zip = $_FILES['zipfile'];

                    //check that the file is a zip file!


                    if ($zip['name'] !== '' && $zip['error'] == 0) {
                        global $env;
                        $path = $env->imageDir() . strtolower(str_replace(' ', '_', $info['cName'])) . '/' . strtolower(str_replace(' ', '_', $info['gName'])) . '/';

                        $err = $this->decompressZip($zip, $path);

                        if ($err !== true) {
                            $this->cError = $err; 
                            $this->template = 'addimages';
                        } else {
                            $this->redirectAdm('clients/gallery/?g=' . $data['g'] . '&amp;c=' . $data['c']);
                        }
                    } elseif ($zip['error'] == 1) {
                        $this->cError = 'The uploaded file was too large for your server to handle. Please upload a smaller file.';
                    } else {
                        $this->cError = 'There is a problem with the uploaded file.';
                    }
                    
                    
                } else {
                    $this->redirectAdm('clients/clients/');
                }

                
            } else {
                $this->redirectAdm('clients/clients/');
            }

            
            
            
            
        }
        
                private function loggedIn() {
            Loader::model('user/loginauth');
            
            
            if (!loginauthModel::isLoggedIn()) {
                
                
                $this->redirectAdm('login/login');
            }
            
        }
        
    }
