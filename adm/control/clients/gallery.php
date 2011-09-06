<?php
    
    class gallery extends controller {
        public $cError = array();
        
        public function index() {
            
            Loader::model('user/loginauth');
            
            
            if (!loginauthModel::isLoggedIn()) {
            
           
                $this->redirectAdm('login/login');
            }
            if ($this->seoUrls == 1) {
                Loader::model('seo/seo');   
            }
            
                       
            $this->populous();
        }

        public function save() {
            Loader::model('user/loginauth');

            if (!loginauthModel::isLoggedIn()) {
                $this->redirectAdm('login/login');
            }

            if ($this->seoUrls == 1) {
                Loader::model('seo/seo');   
            }

            $this->validate();


            if (!empty($this->cError)) {
                $this->populous();

                $this->gallery['expiry'] = $this->expiry;
                unset($this->expiry);
                
                $this->gallery['noexpiry'] = $this->noexpiry;
                unset($this->noexpiry);
                $this->gallery['activate'] = $this->activate;
                unset($this->blog);
                $this->gallery['blog'] = $this->blog;
                $this->gallery['pword'] = $this->gpword;
            } else {

                
                //save!!!
                
                Loader::model('clients/galleries');


                if ($this->expiry !== '') {
                    $expiry = strtotime((string)$this->expiry);
                } else {
                    $expiry = (int)0;
                }

                
                
                
                if ($this->activate === true) {
                    $activate = 1;
                } else {
                    $activate = 0;
                }

                

                
                galleriesModel::updateGallery($this->blog, $activate, $this->g, $expiry, $this->noexpiry, $this->gpword);
                
                //now update the options

                galleriesModel::linkOptions($this->g, $this->optionsChecked);
                if ($this->seoUrls == 1) {
                    if ($this->seoUrl !== '') {
                        //update the seo url!
                        $params = array(
                                        'c' =>  $this->cidentify,
                                        'g' =>  $this->g
                                        );
                        seoModel::update($this->g, 'splash/gallery/', $params, 'splash/gallery/', $this->seoUrl, 'g');
                    } else {
                        seoModel::deleteUrls($this->g, 'g');   
                    }
                }
                
                $this->redirectAdm('clients/clients/');
                
            }
        }
        

         

        
    
        private function populous() {
            
            $gID = -1;
            $cID = -1;
            
            
            if (isset($_POST['g'])) {
                $gID = (int)$_POST['g'];
            } elseif (isset($_GET['g'])) {
                $gID = (int)$_GET['g'];
            }
            
            if (isset($_POST['c'])) {
                $cID = (int)$_POST['c'];
            } elseif (isset($_GET['c'])) {
                $cID = (int)$_GET['c'];
            }
            
            if ((int)$cID < 1 || (int)$gID < 1) {
                $this->redirectAdm('/clients/clients');
            }
            $this->addMoreImagesLink = $this->admUrl . 'clients/addimages/?g=' . $gID . '&amp;c=' . $cID;
            $this->viewImagesLink = $this->admUrl . 'clients/viewimages/?g=' . $gID . '&amp;c=' . $cID;
            $this->cidentify = $cID;
            
            //get the gallery information
            $this->action = $this->admUrl . 'clients/gallery/save/';
            
            Loader::model('clients/clients');
            
            
            $galleryInformation = clientsModel::getGalleryInformation($gID, $cID);
            
            

            
            if (!empty($galleryInformation['splash']) && $galleryInformation['splash'] !== '') {

                $imageTool = new image($galleryInformation['splash'],$galleryInformation['cName'],$galleryInformation['gName'],'splashadmin');
                $splash = $imageTool->resize(true);
                unset($imageTool);
            } else {
                $imageTool = new image();
                $splash = $imageTool->getNone('splashadmin');
                unset($imageTool);
            }
            

            if (!empty($galleryInformation['landing']) && $galleryInformation['landing'] !== '') {
                
                $imageTool = new image($galleryInformation['splash'],$galleryInformation['cName'],$galleryInformation['gName'],'landingadmin');
                $landing = $imageTool->resize(true);
                unset($imageTool);
            } else {
                $imageTool = new image();
                $landing = $imageTool->getNone('landingadmin');
                unset($imageTool);
            }
            
            //now populate the object:
            
            $this->reScan = $this->admUrl . 'clients/gallery/rescan/?g=' . $gID . '&amp;c=' . $cID;;
            $this->reScanFlat = $this->admUrl . 'clients/gallery/rescan/';
            $this->reScanParams = "g:'" . $gID . "',c:'" . $cID . "',d:'" . time() . "'";
            $this->loader = $this->admUrl . 'images/loader.gif';
            $activate = '';
            $activateLink = '';
            

            
            if ($galleryInformation['active'] == 1) {
                if ((int)$galleryInformation['noexpiry'] == 1) {
                    $activate = 'deactivate (never expires)';
                    $activateLink = $this->admUrl . 'clients/gallery/dact/?g=' . $gID . '&amp;c=' . $cID;                    
                } elseif ((int)$galleryInformation['gExpiry'] < time()) {
                    //expired
                    $activate = 'deactivate (expired)';
                    $activateLink = $this->admUrl . 'clients/gallery/dact/?g=' . $gID . '&amp;c=' . $cID;
                } else {
                    if ((int)$galleryInformation['gExpiry'] > 0) {
                        $activate = 'deactivate';
                    } else {
                        $activate = 'deactivate (expires: ' . date('F M Y', (int)$galleryInformation['gExpiry']) . ')';
                    }
                    $activateLink = $this->admUrl . 'clients/gallery/dact/?g=' . $gID . '&amp;c=' . $cID;
                }
            } else {
                $activate = 'activate';
                $activateLink = $this->admUrl . 'clients/gallery/act/?g=' . $gID . '&amp;c=' . $cID;
            }
            
            

            $deleteLink = $this->admUrl . 'clients/gallery/del/?g=' . $gID . '&amp;c=' . $cID;

            
            if ((int)$galleryInformation['gExpiry'] == 0) {
                $expiry = '';
            } else {
                $expiry = date('j M Y',$galleryInformation['gExpiry']);
            }
            if ((int)$galleryInformation['noexpiry'] == 1) {
                $noexpiry = true;
            } else {
                $noexpiry = false;
            }

            //get which options have been selected on this gallery:
            Loader::model('clients/galleries');
            $cops = galleriesModel::getCostOptions($gID, true);
            $this->optionsChecked = array();
            foreach ($cops as $co) {
                $this->optionsChecked[] = $co['pID'];
            }
            
            if ($this->seoUrls == 1) {
                //get the client url:
                $url = new url();
                $this->seoBase = seoModel::getUrl($this->cidentify);
                $this->seoUrlBase = $url->getFullBaseUri() . $this->url . $this->seoBase;
                if (!isset($this->seoUrl)) {
                    
                    //see if there is a trailing slash on the url:
                    if (substr($galleryInformation['url'], strlen($galleryInformation['url']) - 1, 1) == '/') {
                        $seoUrl = substr($galleryInformation['url'], 0, strlen($galleryInformation['url']) - 1);
                    } else {
                        $seoUrl = $galleryInformation['url'];
                    }
                } else {
                    $seoUrl = $this->seoUrl;
                }
                
                $seoUrl = str_replace($this->seoBase, '', $seoUrl);
                
                
            } else {
                $seoUrl = '';   
            }
            
            

            $this->seoUrl = $seoUrl;
            
            $this->gallery = array(
                                   'name'           =>  $galleryInformation['gName'],
                                   'client'         =>  $galleryInformation['cName'],
                                   'gidentifier'    =>  $gID,
                                   'cidentifier'    =>  $cID,
                                   'splash'         =>  $splash,
                                   'landing'        =>  $landing,
                                   'total'          =>  $galleryInformation['total'],
                                   'activate'       =>  $activate,
                                   'activateLink'   =>  $activateLink,
                                   'deleteLink'     =>  $deleteLink,
                                   'blog'           =>  $galleryInformation['gBlog'],
                                   'expiry'         =>  $expiry,
                                   'noexpiry'       =>  $noexpiry,
                                   'pword'          =>  $galleryInformation['gpword']
                             );
            
            $this->g = $gID;
            //get information about the cart:
            
            $this->produceOptions();
        }
        
        
        private function produceOptions() {
            
            //get the product options:
            
            Loader::model('products/product');
            
            
            $options = productModel::getOptions();
            
            if (!empty($options[0])) {
                
                $this->options = array();
                
                foreach ($options as $option) {
                    
                    $costs = explode('__', $option['cost']);
                    $values = explode('__', $option['value']);
                    
                    $costsArr = array();
                    
                    foreach ($costs as $i => $v) {
                        
                        $costsArr[$values[$i]] = '&pound; ' . number_format((float)$v, 2);
                        
                    }
                    
                    
                    $this->options[] = array(
                                             'type'         =>  $option['tName'],
                                             'name'         =>  $option['pName'],
                                             'costOptions'  =>  $costsArr,
                                             'p'            =>  $option['pID']
                                             );
                    
                    
                }
                
                
                
            }
            
        }
     
        public function rescan() {
            Loader::model('user/loginauth');
            

            if (!loginauthModel::isLoggedIn()) {
                
                
                $this->redirectAdm('login/login');
            }
            
            if (isset($_POST['d']) && isset($_POST['g']) && isset($_POST['c'])) {
                $this->noRender = true;

                if ((int)$_POST['g'] > 0 && (int)$_POST['c'] > 0 && (int)$_POST['d'] < time()) {
                    echo $this->popGalleryImages((int)$_POST['g'],(int)$_POST['c']);   
                } else {
                    echo 'false';
                }
                
            } elseif (isset($_GET['g']) && isset($_GET['c'])) {

                if ((int)$_GET['g'] > 0 && (int)$_GET['c'] > 0) {
                    $this->popGalleryImages((int)$_GET['g'],(int)$_GET['c']);
                    $this->redirectAdm('clients/gallery/?g=' . $_GET['g'] . '&c=' . $_GET['c']);   
                    exit;
                }

                
                $this->redirectAdm('clients/clients');   
                
            } else {

                $this->redirectAdm('clients/clients');   
            }
            
            
        }
        
        
        private function popGalleryImages($g = -1, $c = -1) {
            Loader::model('clients/galleries');
            $galleryInfo = galleriesModel::getGalleryInfo((int)$g, (int)$c);
            if (!empty($galleryInfo)) {
                //need to determine the folder path to the gallery and then rescan the directory!
	        $env = new Environment();
                
                $galleryPath = $env->imageDir() . str_replace(' ','_',strtolower($galleryInfo['cName'])) . '/' . strtolower($galleryInfo['gName']);
                //find all the images in the gallery path and create an array from them!
                
                return galleriesModel::populateImages($galleryPath,$galleryInfo['cName'],$galleryInfo['gName'],(int)$g);
                
            } else {
                return false;   
            }
            
        }
     
        private function validate() {

            if (isset($_POST['g']) && (int)$_POST['g'] > 0 && isset($_POST['c']) && (int)$_POST['c'] > 0) {
                
                $this->g = (int)$_POST['g'];
                $this->cidentify = (int)$_POST['c'];
                
            } else {

                $this->redirectAdm('clients/clients');
                
            }
            
            if (isset($_POST['blog'])) {
                $this->blog = stripslashes($_POST['blog']);
            } else {
                $this->blog = '';   
            }
            
            if (isset($_POST['expiry'])) {
                $this->expiry = stripslashes($_POST['expiry']);   
            } else {
                $this->expiry = '';   
            }
            
            if (isset($_POST['p']) && is_array($_POST['p'])) {
                $this->optionsChecked = $_POST['p'];
            } else {
                $this->optionsChecked = array();   
            }
            
            if (isset($_POST['activate'])) {
                $this->activate = true;   
            } else {
                $this->activate = 'activate';   
            }
            
            if (isset($_POST['never'])) {
                $this->noexpiry = true;
            } else {
                $this->noexpiry = false;
            }
            
            if (isset($_POST['pword']) && strlen($_POST['pword']) > 0) {
                $this->gpword = stripslashes($_POST['pword']);
            } else {
                $this->gpword = null;
            }
            
            if ($this->seoUrls == 1) {
                if (isset($_POST['seourl']) && strlen($_POST['seourl']) > 0) {
                    
                    if (isset($_POST['seobase']) && strlen($_POST['seobase']) > 0) {
                        $seoUrl = seoModel::sanitize($_POST['seobase'] . $_POST['seourl'], $this->url);
                    } else {
                        $seoUrl = seoModel::sanitize($_POST['seourl'], $this->url);                        
                    }
                    if (seoModel::checkIfSeoUrlExists($seoUrl)) {                        
                        $this->cError['seoUrl'] = 'That url is already taken';
                    }
                    $this->seoUrl = $seoUrl;
                } else {
                    $this->seoUrl = '';
                }
            }
        }
        
        
        
        public function del() {
            Loader::model('user/loginauth');
            
            
            if (!loginauthModel::isLoggedIn()) {
                $this->redirectAdm('login/login');
            }
            if (isset($_GET['g']) && $_GET['g'] > 0) {
                //remove the gallery and all its images!
                Loader::model('clients/galleries');
                if ($this->seoUrls == 1) {
                    galleriesModel::deleteGallery((int)$_GET['g'], true);
                } else {
                    galleriesModel::deleteGallery((int)$_GET['g']);
                }
                
            }
            
            $this->redirectAdm('clients/clients');
        }
        
        
        public function act() {
            if (isset($_GET['g']) && (int)$_GET['g'] > 0 && isset($_GET['c']) && (int)$_GET['c'] > 0) {
                
                Loader::model('clients/galleries');
                
                galleriesModel::activate($_GET['g']);
                
            }
            
            $this->redirectAdm('clients/clients/');
            
        }

        
        public function dact() {
            if (isset($_GET['g']) && (int)$_GET['g'] > 0 && isset($_GET['c']) && (int)$_GET['c'] > 0) {
                
                Loader::model('clients/galleries');
                
                galleriesModel::deactivate($_GET['g']);
                
            }
            
            $this->redirectAdm('clients/clients/');
            
        }
        
    }
