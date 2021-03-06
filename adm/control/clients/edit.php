<?php
    
    class edit extends controller {
        public $cError = array();
        private $newImage = false;
        
        public function index() {
            
            Loader::model('user/loginauth');
            
            
            if (!loginauthModel::isLoggedIn()) {
            
           
                $this->redirectAdm('login/login');
            }
            
            
            if (!isset($_GET['c'])) {
                
                $this->redirectAdm('clients/clients/');
                
            }
            
            
            $this->create = $this->admUrl . 'clients/clients/gcreate/?c=' . $_GET['c'];

            $this->populous();
        }

        public function save() {

            if ($this->seoUrls == 1) {
                Loader::model('seo/seo');
            }
            $this->validateClient();
            if (empty($this->cError)) {
                
                Loader::model('clients/clients');
                if ($this->newImage === true) {
                    if (!empty($_FILES['image']) && empty($this->cError['image'])) {
                        $file = str_replace(' ','_',$_FILES['image']['name']);
                    } else {
                        $file = $this->file;
                    }
                    
                    if ($this->originalFile !== $file) {
                        $imageTool = new image($file, $this->originalName, 'clientfront', 'clientfrontadmin');
                        $imageTool->resize(false, false, $_FILES['image']['tmp_name']);
                        $imageTool->updateSystem('clientfrontadmin');

                        $imageTool->resize(false, false, false, true);

                        if ($this->originalFile !== '') {
                            $imageTool2 = new image($this->originalFile, str_replace(' ','_',$this->originalName), 'clientfrontadmin', 'clientfrontadmin');
                            $imageTool2->remove();
                            unset($imageTool2);
                        }
                        
                        
                        unset($imageTool);
                    }
                } else {
                    $file = $this->originalFile;  
                }
                

                if ($this->newSplashImage === true) {
                    if (!empty($_FILES['splash']) && empty($this->cError['splash'])) {
                        $splash = str_replace(' ','_',$_FILES['splash']['name']);
                    } else {
                        $splash = $this->splash;   
                    }

                    if ($this->originalSplashFile !== $splash) {

                        $imageTool = new image($splash, $this->originalName, 'splash','splashadmin');
                        $imageTool->resize(false, false, $_FILES['splash']['tmp_name']);
                        $imageTool->updateSystem('splash');
                        $imageTool->resize(false, false, false, true);

                        if ($this->originalSplashFile !== '') {
                            $imageTool2 = new image($this->originalSplashFile, str_replace(' ','_',$this->originalName),'splash','splashadmin');
                            $imageTool2->remove();
                            unset($imageTool2);
                        }
                        
                        
                        unset($imageTool);
                    }
                } else {
                    $splash = $this->originalSplashFile;  
                }
                
                
                //first work out if the old directory has been changed at all
                
                
                
                if ($this->originalName !== $this->name) {
                    $imageTool = new image();
                    $imageTool->renameDirectory($this->originalName, $this->name);
                    unset($imageTool);
                }
                
                
                
                
                
                    
                $this->getCid();



                clientsModel::update($this->name, $this->description, $this->email, $file, $this->cidentify, $splash);

		                
                if ($this->seoUrls == 1) {
                    
                    
                    if ($this->seoUrl !== '') {
                

                        $seod = seoModel::update($this->cidentify, 'splash/', serialize(array('c' => $this->cidentify)), '/splash/', $this->seoUrl, 'c');
                
                        if ($seod) {
                            $this->redirectAdm('clients/edit/?c=' . $this->cidentify);
                        } else {
                            $this->cError['seoUrl'] = 'That url is already taken.';
                        }
                    } else {
                        //remove the seo url if it exists!
                        seoModel::deleteUrls($this->c, 'c');
                    }
                } else {
                    $this->redirectAdm('clients/edit/?c=' . $this->cidentify);
                }
                
                
            }
            $this->populous();

            $this->action = $this->admUrl . 'clients/edit/save';            

        }
        
        private function getCid() {
            if (isset($_GET['c'])) {
                $this->cidentify = $_GET['c'];   
            } elseif (isset($_POST['c'])) {
                $this->cidentify = $_POST['c'];
            } else {
                $this->redirectAdm('/clients/clients');   
            }
        }
        private function validateClient() {


            if (!isset($_POST['name']) || $_POST['name'] == '') {
                $this->cError['name'] = 'The client&lsquo;s name can not be blank';                
            } else {
                $this->name = $_POST['name'];   
            }

            if (isset($_POST['originalname'])) {
                $this->originalName = stripslashes($_POST['originalname']);
            } else {
                $this->cError['name'] = 'There is a problem with this client&lsquo;s original name';   
            }

            if (isset($_POST['email'])) {
                $this->email = stripslashes($_POST['email']);
            } else {
                $this->email = '';
            }

            if (isset($_POST['description'])) {
                $this->description = stripslashes($_POST['description']);
            } else {
                $this->description = '';
            }

            if ($this->seoUrls == 1 && isset($_POST['seourl'])) {
                $this->seoUrl = seoModel::sanitize($_POST['seourl'], $this->url);
            } else {
                $this->seoUrl = '';
            }

            if (isset($_POST['newfile']) && (string)$_POST['newfile'] == 'on') {
                $this->newImage = true;
                if (isset($_POST['originalfile'])) {
                    $this->originalFile = stripslashes($_POST['originalfile']);
                } else {
                    $this->cError['image'] = 'There is a problem with this client&lsquo;s original image';   
                }
                
                
                
                if (!empty($_FILES['image']['name'])) {
                    
                    switch ($_FILES['image']['type']) {
                        case 'image/jpeg':
                        case 'image/pjpeg':
                        case 'image/jpg':
                        case 'image/pjpg':
                        case 'image/png':
                        case 'image/gif':
                            if ($_FILES['image']['error'] !== 0) {
                                $this->cError['image'] = 'There was an error uploading the image you selected';
                            } elseif ($_FILES['image']['size'] > 150000) {
                                $this->cError['image'] = 'Please choose a file less than 150KB';
                            }
                            break;
                        default:
                            $this->cError['image'] = 'Please make sure you only upload a jpg, png or gif image';
                            break;
                            
                    }
                    
                }
            
            } else {
                if (isset($_POST['originalfile'])) {
                    $this->originalFile = $_POST['originalfile'];
                }
                $this->newImage = false;   
            }
            
            
            if (isset($_POST['newsplash']) && (string)$_POST['newsplash'] == 'on') {
                $this->newSplashImage = true;
                if (isset($_POST['originalsplashfile'])) {
                    $this->originalSplashFile = stripslashes($_POST['originalsplashfile']);
                } else {
                    $this->cError['splash'] = 'There is a problem with this client&lsquo;s original image';   
                }
                
                
                
                if (!empty($_FILES['splash']['name'])) {
                    
                    switch ($_FILES['splash']['type']) {
                        case 'image/jpeg':
                        case 'image/pjpeg':
                        case 'image/jpg':
                        case 'image/pjpg':
                        case 'image/png':
                        case 'image/gif':
                            if ($_FILES['splash']['error'] !== 0) {
                                $this->cError['splash'] = 'There was an error uploading the image you selected';
                            } elseif ($_FILES['splash']['size'] > 150000) {
                                $this->cError['splash'] = 'Please choose a file less than 150KB';
                            }
                            break;
                        default:
                            $this->cError['splash'] = 'Please make sure you only upload a jpg, png or gif image';
                            break;
                            
                    }
                    
                }
                
            } else {
                if (isset($_POST['originalsplashfile'])) {
                    $this->originalSplashFile = $_POST['originalsplashfile'];
                }
                $this->newSplashImage = false;   
            }

        }
        
    
        private function populous() {
            $this->getCid();
            
            
            Loader::model('clients/clients');
            
            $clientInfo = clientsModel::getClients($this->cidentify);
            
            $clientInfo = $clientInfo[0];
                        
            
            $this->originalName = $clientInfo['cName'];
            $this->file = $clientInfo['cFrontImage'];
            $this->originalFile = $clientInfo['cFrontImage'];
            
            $this->originalSplashFile = $clientInfo['splash'];

            if (!empty($clientInfo)) {

                $galleryArr = explode(',', $clientInfo['gIDs']);

                $galleries = array();
                if (!empty($galleryArr[0])) {

                    $gNames = explode(',', $clientInfo['gName']);
                    $gSplash = explode(',', $clientInfo['splash']);
                    //$gLanding = explode(',', $clientInfo['landing']);
                    $gIDs = explode(',', $clientInfo['gIDs']);
                    $gExpiry = explode(',', $clientInfo['gExpiry']);
                    $gActive = explode(',' , $clientInfo['active']);
                    $gNoExpiry = explode(',' , $clientInfo['noexpiry']);

                    foreach ($galleryArr as $gk => $gallery) {

                        if (!empty($gSplash)) {

                            if (!empty($gSplash[$gk]) && $gSplash[$gk] !== '' && $gSplash[$gk] !== null) {
                                $imageTool = new image($gSplash[$gk],$clientInfo['cName'],'gallery','splashadmin');
                                $image = $imageTool->resize(true);
                                unset($imageTool);
                            } else {
                                $imageTool = new image();
                                $image = $imageTool->getNone('splashadmin');
                                unset($imageTool);
                            }
                            
                            
                        }                        

                        
                        $addImagesLink = $this->admUrl . 'clients/addimages/?g=' . $gIDs[$gk] . '&amp;c=' . $clientInfo['cID'];
                        $addImagesText = 'Add More Images';

                        if ($gActive[$gk] !== null && (int)$gActive[$gk] == 1) {
                            if ($gNoExpiry[$gk] == 1) {
                                $activate = 'deactivate (never expires)';
                            } elseif ($gExpiry[$gk] < time()) {
                                $activate = 'deactivate (expired)';
                            } else {
                                $activate = 'deactivate (expires: ' . date('F M Y', $gExpiry[$gk]) . ')';
                            }
                            $activateLink = $this->admUrl . 'clients/gallery/dact/?g=' . $gIDs[$gk] . '&amp;c=' . $clientInfo['cID'];
                        } else {
                            $activateLink = $this->admUrl . 'clients/gallery/act/?g=' . $gIDs[$gk] . '&amp;c=' . $clientInfo['cID'];
                            $activate = 'activate';
                        }


                        
                        $galleries[] = array(
                                             'gLink'            =>  $this->admUrl . 'clients/gallery/?g=' . $gIDs[$gk] . '&amp;c=' . $clientInfo['cID'],
                                             'name'             =>  $gNames[$gk],
                                             'image'            =>  $image,
                                             'activate'         =>  $activate,
                                             'activateLink'     =>  $activateLink,
                                             'addImagesLink'    =>  $addImagesLink,
                                             'addImagesText'    =>  $addImagesText
                                             );
                        
                    } 
                    
                }

                if ($clientInfo['cFrontImage'] !== '' && $clientInfo['cFrontImage'] !== null) {
                    $imageTool = new image($clientInfo['cFrontImage'],$clientInfo['cName'],'clientfront','landing');
                    $image = $imageTool->resize(true);
                    unset($imageTool);
                } else {
                    $this->originalFile = '';
                    $imageTool = new image();   
                    $image = $imageTool->getNone('splash');
                    unset($imageTool);
                }

                if ($clientInfo['splash'] !== '' && $clientInfo['splash'] !== null) {
                    $imageTool = new image($clientInfo['splash'],$clientInfo['splash'],'splash','landing');
                    $splashimage = $imageTool->resize(true);
                    unset($imageTool);
                } else {
                    $this->originalFile = '';
                    $imageTool = new image();   
                    $splashimage = $imageTool->getNone('splash');
                    unset($imageTool);
                }
                if ($this->seoUrls == 1) {
                    
                    
                    if (!isset($this->seoUrl)) {

			Loader::model('seo/seo');

			$clientInfo['seoUrl'] = seoModel::getUrl($clientInfo['cID']);

			if ($clientInfo['seoUrl'] !== '') {

	                        //see if there is a trailing slash on the url:
	                        if (substr($clientInfo['seoUrl'], strlen($clientInfo['seoUrl']) - 1, 1) == '/') {
        	                    $seoUrl = substr($clientInfo['seoUrl'], 0, strlen($clientInfo['seoUrl']) - 1);
	                        } else {
        	                    $seoUrl = $clientInfo['seoUrl'];   
                	        }
			} else {
				$seoUrl = $this->seoUrl;
			}
                    } else {
                        $seoUrl = $this->seoUrl;   
                    }
                    
                } else {
                    $seoUrl = '';   
                }
                
                
                $clientInfo = array(
                                    'name'          =>  $clientInfo['cName'],
                                    'description'   =>  $clientInfo['description'],
                                    'galleries'     =>  $galleries,
                                    'image'         =>  $image,
                                    'email'         =>  $clientInfo['email'],
                                    'seoUrl'        =>  $seoUrl
                                  );
                
            }

            
            $this->action = $this->admUrl . 'clients/edit/save';
            
            $this->clientInfo = $clientInfo;
            
        }
        
    }