<?php
    
    class clients extends controller {
        public $cError = array();
        
        public function index() {
            
            Loader::model('user/loginauth');
            
            
            if (!loginauthModel::isLoggedIn()) {
            
           
                $this->redirectAdm('login/login');
            }
            
            
            //need to get a list of clients now, with their galleries:
            
            
            Loader::model('clients/clients');
            

            
            $this->clientsList = array();
            
            $cArr = clientsModel::getClients();
            
            
            foreach ($cArr as $k => $arr) {
                
                $galleries = array();
                if ($arr['gIDs'] !== '') {

                    
                    $gNames = explode(',', $arr['gName']);
                    $gSplash = explode(',', $arr['gName']);
                    $gLanding = explode(',', $arr['gName']);
                    $gActivates = explode(',', $arr['active']);
                    
                    foreach (explode(',',$arr['gIDs']) as $gk => $i) {
                        
                        if ($gSplash[$gk] !== '' && $gSplash[$gk] !== null) {
                            $imageTool = new image($gSplash[$gk],$arr['gName'],'splash','splashadmin');
                            $image = $imageTool->resize(true);
                            if ($image == '') {
                                $image = $imageTool->getNone('splashadmin');   
                            }
                            unset($imageTool);
                        } else {
                            $image = '';   
                        }
                        

                        if (!empty($gActivates[$gk]) && (int)$gActivates[$gk]['active'] == 1) {
                            $actLink = $this->admUrl . 'clients/gallery/dact/?g=' . $i . '&amp;c=' . $arr['cID'];
                            $actText = 'deactivate';
                        } else {
                            $actText = 'activate';
                            $actLink = $this->admUrl . 'clients/gallery/act/?g=' . $i . '&amp;c=' . $arr['cID'];
                        }
                        
                        $addImagesLink = $this->admUrl . 'clients/addimages/?g=' . $i . '&amp;c=' . $arr['cID'];
                        $addImagesText = 'add more images';
                        
                        $galleries[] = array(
                                             'gLink'            =>  $this->admUrl . 'clients/gallery/?g=' . $i . '&amp;c=' . $arr['cID'],
                                             'name'             =>  $gNames[$gk],
                                             'image'            =>  $image,
                                             'delLink'          =>  $this->admUrl . 'clients/gallery/del/?g=' . $i . '&amp;c=' . $arr['cID'],
                                             'activeLink'       =>  $actLink,
                                             'activeText'       =>  $actText,
                                             'addImagesLink'    =>  $addImagesLink,
                                             'addImagesText'    =>  $addImagesText
                                          );
                    }
                }

                $imageTool = new image($arr['cFrontImage'],$arr['cName'],'clientfront','clientfrontadmin');
                $image = $imageTool->resize(true);
                
                if ($image == '') {
                    $image = $imageTool->getNone('clientfrontadmin');
                }
                
                
                $this->clientsList[] = array(
                                             'cLink'        =>  $this->admUrl . 'clients/edit/?c=' . $arr['cID'],
                                             'galleries'    =>  $galleries,
                                             'name'         =>  $arr['cName'],
                                             'deleteLink'   =>  $this->admUrl . 'clients/clients/del/?c=' . $arr['cID'],
                                             'image'        =>  $image,
                                             'createg'      =>  $this->admUrl . 'clients/clients/gcreate/?c=' . $arr['cID']
                                       );
                unset($imageTool);
                
            }

        }
        
        
        
        public function add() {
            $this->action = $this->admUrl . 'clients/clients/save';
            
            $this->template = 'add';

            $this->name = '';
            $this->description = '';
            $this->email = '';
            if ($this->seoUrls == 1) {
                $this->seoUrl = '';   
            }
            
        }
        
        public function save() {
            $this->name = '';
            $this->description = '';
            $this->email = '';
            if ($this->seoUrls == 1) {
                $this->seoUrl = '';
                Loader::model('seo/seo');
            }
            $this->validateClient();
            if (empty($this->cError)) {
             
                Loader::model('clients/clients');
                
                
                if (!empty($_FILES['image']) && empty($this->cError['image'])) {
                    $file = str_replace(' ','_',$_FILES['image']['name']);
                } else {
                    $file = '';   
                }

                
                if (!empty($_FILES['splash']) && empty($this->cError['splash'])) {
                    $splash = str_replace(' ','_',$_FILES['splash']['name']);
                } else {
                    $splash = '';   
                }
                
                $cID = clientsModel::create($this->name, $this->description, $this->email, $file, $splash);                


                //save the seo url if set
                
                if ($this->seoUrls == 1) {
                    $params = serialize(array('c'  =>  $cID));
                    seoModel::update($cID, 'splash/', $params, '/splash/', $this->seoUrl);                    
                }
                
                
                //try to move the file!

                if ($file !== '') {
                    $imageTool = new image($file,$this->name,'clientfront','clientfront');
                    
                    $imageTool->resize(false,false,$_FILES['image']['tmp_name']);
                    $imageTool->updateSystem('clientfrontadmin');
                    $imageTool->updateSection('clientfrontadmin');
                    $imageTool->resize(false,false,$_FILES['image']['tmp_name']);
                    unset($imageTool);
                }

                if ($splash !== '') {
                    $imageTool = new image($splash, $this->name, 'splash', 'splash');
                    
                    $imageTool->resize(false,false,$_FILES['splash']['tmp_name']);
                    $imageTool->updateSystem('splashadmin');
                    $imageTool->updateSection('splashadmin');
                    $imageTool->resize(false,false,$_FILES['splash']['tmp_name']);
                    unset($imageTool);
                }

                $this->redirectAdm('clients/edit/?c=' . $cID);
                exit;

            }
            $this->action = $this->admUrl . 'clients/clients/save';

            $this->template = 'add';
            
            
        }
        
        
        private function validateClient() {

            
            if (!isset($_POST['name']) || $_POST['name'] == '') {

                $this->cError['name'] = 'Please enter the client&lsquo;s name';
                
            } elseif (isset($_POST['name'])) {
                $this->name = stripslashes($_POST['name']);
            }
                      
            
            if (isset($_POST['email'])) {
                $this->email = stripslashes($_POST['email']);
            }

            if (isset($_POST['description'])) {
                $this->description = stripslashes($_POST['description']);
            }

            if ($this->seoUrls == 1) {
                
                if (isset($_POST['seourl']) && strlen($_POST['seourl']) > 0) {

                    $seoUrl = seoModel::sanitize($_POST['seourl'], $this->url);

                    if (seoModel::checkIfSeoUrlExists($seoUrl)) {
                     
                        $this->cError['seoUrl'] = 'That url is already taken';
                    }
                    $this->seoUrl = $seoUrl;

                }
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
            
        
        }
        
        public function del() {
            
            Loader::model('user/loginauth');
            
            
            if (!loginauthModel::isLoggedIn()) {
                
                
                $this->redirectAdm('login/login');
            }

            
            if (isset($_GET['c']) && (int)$_GET['c'] > 0) {
                
                Loader::model('clients/clients');
                if ($this->seoUrls == 1) {
                    clientsModel::deleteClient((int)$_GET['c'], true);
                } else {
                    clientsModel::deleteClient((int)$_GET['c']);   
                }
            }
            
            $this->redirectAdm('clients/clients/');
            
        }
    
        
        
        public function gcreate() {
         
            Loader::model('user/loginauth');
            
            
            if (!loginauthModel::isLoggedIn()) {
                
                
                $this->redirectAdm('login/login');
            }
            
            
            if (isset($_GET['c']) && (int)$_GET['c'] > 0) {
                
                //create the client gallery:
                
                //get the client information
                
                
                Loader::model('clients/clients');
                
                $cArr = clientsModel::getClients((int)$_GET['c']);
                
                if (!empty($cArr[0])) {

                    
                    
                    $this->template = 'creategallery';

                    $this->action = $this->admUrl . 'clients/clients/saveg/';
                    
                    $this->name = ucwords($cArr[0]['cName']);
                
                    $this->cid = $cArr[0]['cID'];
                    
                    $this->produceOptions();
                        
                    $this->gname = '';
                    $this->blog = '';
                    $this->pword = '';
                    $this->activate = '';
                    $this->expiry = '';
                    $this->indef = '';
                    $this->reScanFlat = '';
                    $this->reScanParams = '';
                    $this->loader = '';
                    $this->reScan = '';
                    if ($this->seoUrls == 1) {
                        //get the client url:
                        Loader::model('seo/seo');
                        $url = new url();
                        $this->seoBase = seoModel::getUrl($this->cid);
                        $this->seoUrlBase = $url->getFullBaseUri() . $this->url . $this->seoBase;
                        $this->seoUrl = '';
                        
                    }
                    
                    
                } else {
                    $this->redirectAdm('clients/clients');   
                }
                

                
                
                
                
            } else {
                $this->redirectAdm('clients/clients');   
            }
            
        }
        
     
        public function saveg() {

            Loader::model('user/loginauth');
            
            
            if (!loginauthModel::isLoggedIn()) {
                
                
                $this->redirectAdm('login/login');
            }
            if ($this->seoUrls == 1) {
                Loader::model('seo/seo');
            }
                
                
            $this->validateGallery();
            
            if (!empty($this->cError)) {
                $this->template = 'creategallery';
                $this->action = $this->admUrl . 'clients/clients/saveg/';

                Loader::model('clients/clients');
                
                $client = clientsModel::getClients($this->cid);
                if (!empty($client[0])) {
                    
                    $this->name = ucwords($client[0]['cName']);
                } else {
                    $this->redirectAdm('clients/clients');   
                }
                if ($this->seoUrls == 1) {
                    //get the client url:
                    $url = new url();
                    $this->seoBase = seoModel::getUrl($this->cid);
                    $this->seoUrlBase = $url->getFullBaseUri() . $this->url . $this->seoBase;                   
                }
                
                $this->produceOptions();
            } else {

                
                
                Loader::model('clients/galleries');
                
                if ($this->expiry !== '') {
                    $this->expiry = strtotime($this->expiry);
                } else {
                    $this->expiry = 0;   
                }
                
                //get the client name!
                
                Loader::model('clients/clients');
                
                $client = clientsModel::getClients((int)$this->cid);
                
                if (!empty($client[0])) {
                
                    $gID = galleriesModel::createGallery($this->gname, $this->blog, $this->activate, $this->cid, $this->expiry, $this->indef, true, $client[0]['cName'], $this->gpword);

                    
                    //now insert the product options:
                    
                    galleriesModel::linkOptions((int)$gID, $this->optionsChecked);

                    /*
                     
                     No longer populating via zip
                     
                    global $env;
                    
                    $path = $env->imageDir() . strtolower(str_replace(' ', '_', $client[0]['cName'])) . '/' . strtolower(str_replace(' ', '_', $this->gname)) . '/';
                    //now unzip into the directory!

                    
                    if (!empty($this->zip)) {
                        $err = $this->decompressZip($this->zip, $path);
                        if ($err !== true) {
                            $this->cError['file'] = $err;
                            
                        }
                        galleriesModel::populateImages($path, $client[0]['cName'], $this->gname, $gID);
                    }
                    
                    */
                    
                
                    
                    //save the seoUrl:
                    
                    if ($this->seoUrls == 1) {
                        $seoUrl = $this->seoBase . $this->seoUrl;
                        $params = serialize(array('c'   =>  $this->cid, 'g'   =>  $gID));
                        seoModel::update($gID, 'splash/gallery', $params, '/splash/gallery/', $seoUrl, 'g');

                    }
                }
                
                $this->redirectAdm('clients/addimages/?g=' . $gID . '&c=' . $this->cid);   
                
            }
        }
        
        
        private function validateGallery() {

            //see if the clientID is set:
            if (isset($_POST['c']) && (int)$_POST['c'] > 0) {
                $this->cid = (int)$_POST['c'];   
            } else {
                $this->redirectAdm('clients/clients/');   
            }
            
            
            if (isset($_POST['name']) && $_POST['name'] !== '') {
                $this->gname = stripslashes($_POST['name']);
            } else {
                $this->gname = '';
                $this->cError['name'] = 'Please enter the gallery name';   
            }
            
            if (isset($_POST['blog'])) {
                $this->blog = stripslashes($_POST['blog']);
            } else {
                $this->blog = '';
            }
            
            if (isset($_POST['p']) && is_array($_POST['p'])) {
                $this->optionsChecked = $_POST['p'];   
            } else {
                $this->optionsChecked = array();   
            }
            
            
            if (isset($_POST['indef'])) {
             
                $this->indef = true;
                
            } else {
             
                $this->indef = false;
                
            }
            
            if (isset($_POST['expiry'])) {
                $this->expiry = stripslashes($_POST['expiry']);   
            } else {
                $this->expiry = '';   
            }
            
            if (isset($_POST['active'])) {
                $this->activate = 1;
            } else {
                $this->activate = 0;   
            }
            /*
             No longer populating by zip
            
            if (isset($_FILES['zip']) && !empty($_FILES['zip']['name'])) {
             
                
                $this->zip = $_FILES['zip'];
                
            }
             
             */
            
            if ($this->seoUrls == 1) {
                if (isset($_POST['seourl']) && strlen($_POST['seourl']) > 0) {
                    if (seoModel::checkIfSeoUrlExists($_POST['seourl'], $this->cid)) {
                        $this->cError['seoUrl'] = 'That url is already taken.';
                        $this->seoUrl = $_POST['seourl'];   
                    } else {
                        $this->seoUrl = seoModel::sanitize($_POST['seourl'], $this->cid);
                    }
                } else {
                    $this->seoUrl = '';   
                }
                
                if (isset($_POST['seobase']) && strlen($_POST['seobase']) > 0) {
                    $this->seoBase = $_POST['seobase'];   
                }
                
            }
            
            
            if (isset($_POST['pword']) && strlen($_POST['pword']) > 0) {
                $this->gpword = stripslashes($_POST['pword']);
            } else {
                $this->gpword = null;
            }
            
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
                
    }