<?php
    
    class oneoffs extends controller {
        public $cError = array();
        
        public function index() {
            $this->loggedIn();
            //get the list of type:
            
            
            Loader::model('products/product');
            
            $types = productModel::getOneOffs();
            $this->types = array();

            if (!empty($types)) {
                
                foreach ($types as $type) {
                    
                    $edit = $this->admUrl . 'products/oneoffs/edit/?i='  . $type['id'];
                    $delete = $this->admUrl . 'products/oneoffs/delete/?i='  . $type['id'];
                    $link = $this->fullUrl . 'products/standalone/?p=' . $type['urlID'];
                    
                    if ($type['image'] !== '') {
                        $imageTool = new image($type['image'], '../localimages', 'local', 'localthumb');
                        $image = $imageTool->resize(true);
                        unset($imageTool);
                    } else {
                        $imageTool = new image();
                        $image = $imageTool->getNone('localthumb');
                        unset($imageTool);
                    }
                    
                    if ($type['cost'] > -1) {
                        $cost = $type['cost'];
                    } else {
                        $cost = 'N/A';   
                    }
                    
                    $this->types[] = array(
                                           'name'           =>  $type['name'],
                                           'cost'           =>  $cost,
                                           'image'          =>  $image,
                                           'edit'           =>  $edit,
                                           'delete'         =>  $delete,
                                           'description'    =>  nl2br($type['description']),
                                           'link'           =>  $link,
                                           'nocost'         =>  $nocost
                                           );
                    
                }

            }
            
            
            
            $this->create = $this->admUrl . 'products/oneoffs/add/';
            $this->nocost = false;
            
            
            
        }
        
        
        public function add() {
            $this->loggedIn();
            
            $this->action = $this->admUrl . 'products/oneoffs/create/';
            
            $this->template = 'createoneoff';
            $this->editing = false;   
        }
        
        public function edit() {
            $this->loggedIn();
            
            $data = $this->request('get');

            if (isset($data['i']) && (int)$data['i'] > 0) {
                
                Loader::model('products/product');

                if ($prod = productModel::getOneOff((int)$data['i'])) {
                    
                    $this->template = 'createoneoff';
                    $this->editing = true;   
                    $this->action = $this->admUrl . 'products/oneoffs/ammend/';
                    $this->name = $prod['name'];
                    $this->description = $prod['description'];
                    $this->id = $prod['id'];
                    $this->cost = $prod['cost'];
                    $this->origFile = $prod['image'];
                    
                    if ($this->cost == -1) {
                        $this->nocost = true;
                        $this->cost = '';
                    } else {
                        $this->nocost = false;   
                    }
                    
                    if ($this->origFile !== '') {
                        
                        $imageTool = new image($this->origFile, '../localimages', 'local', 'local');
                        $this->pImage = $imageTool->resize(true);
                        unset($imageTool);
                        
                        
                    } else {
                        $imageTool = new image();
                        $this->pImage = $imageTool->getNone('local');
                        unset($imageTool);
                    }

                    
                    
   
                } else {
                    $this->redirectAdm('products/oneoffs/');
                }
                
                
            } else {
                $this->redirectAdm('products/oneoffs/');
            }

            
            
            
        }
        
        public function ammend() {
            $this->loggedIn();
            
            $this->validate();
            $this->template = 'createoneoff';
            $this->editing = true;   
            $this->action = $this->admUrl . 'products/oneoffs/ammend/';
            
            if (empty($this->cError)) {

                Loader::model('products/product');
                if (productModel::checkOneOffUniqueById((int)$this->id, $this->name)) {

                    //update the product!
                    
                    if ($this->uploadingNew && is_array($this->image) && !empty($this->image)) {
                        
                        $imageTool = new image(strtolower(str_replace(' ', '_', $this->image['name'])), '../localimages', 'local', 'local');
                        $imageTool->setOriginal($this->image['tmp_name']);
                        $imageTool->upload();
                        unset($imageTool);
                        if ($this->origFile !== '') {
                            $imageTool = new image(strtolower(str_replace(' ', '_', $this->origFile)), '../localimages', 'local', 'local');                        
                            $imageTool->deleteOld();
                            unset($imageTool);
                        }
                        $image = $this->image['name'];
                    } elseif ($this->origFile !== '') {
                        $image = $this->origFile;
                    } else {
                        $imageTool = new image();
                        $image = $imageTool->getNone('localthumb');
                        unset($imageTool);
                    }
                    
                    
                    productModel::updateOneOff((int)$this->id, $this->name, $this->description, strtolower($image), $this->cost);
                    $this->redirectAdm('products/oneoffs/');
                } else {
                    $this->cError['name'] = 'That name is already taken.';
                }
                
                
                
            }

            if ($this->origFile !== '') {
                
                $imageTool = new image($this->origFile, '../localimages', 'local', 'local');
                $this->pImage = $imageTool->resize(true);
                unset($imageTool);
                
                
            } else {
                $imageTool = new image();
                $this->pImage = $imageTool->getNone('local');
                unset($imageTool);
            }
            
            
        
        }
        
        
        public function create() {
         
            $this->loggedIn();
            
            $this->validate();
            $this->action = $this->admUrl . 'products/oneoffs/create/';
            
            $this->template = 'createoneoff';
            $this->editing = false;
            
            if (empty($this->cError)) {
             
                
                Loader::model('products/product');

                if (productModel::checkOneOffUnique($this->name) === true) {
                
                    
                    if (is_array($this->image) && !empty($this->image)) {
                        
                        $imageTool = new image($this->image['name'], '../localimages', 'local', 'local');
                        $imageTool->setOriginal($this->image['tmp_name']);
                        $imageTool->upload();
                        unset($imageTool);
                        $image = $this->image['name'];
                    } elseif ($this->origFile !== '') {
                        $image = $this->origFile;
                    } else {
                        $imageTool = new image();
                        $image = $imageTool->getNone('localthumb');
                        unset($imageTool);
                    }
                    
                    
                    productModel::createOneOff($this->name, $this->description, strtolower(str_replace(' ', '_', $image)), $this->cost);
                    
                    
                    $this->redirectAdm('products/oneoffs/');
                    
                    
                } else {
                    $this->cError['name'] = 'That name is already taken.';   
                }
                
                
            }
            
            
        }
        
        private function validate() {
            
            $data = $this->request('post');
            
            if (isset($data['i'])) {
            
                if ((int)$data['i'] > 0) {
                    $this->id = (int)$data['i'];
                } else {
                    $this->redirectAdm('products/oneoffs');   
                }
                
            }
            
            if (isset($data['name']) && strlen($data['name']) > 0) {
             
                $this->name = stripslashes($data['name']);
                
            } else {
                $this->data['name'] = '';
                $this->cError['name'] = 'Please enter a name.';
            }
            
            if (isset($data['description']) && strlen($data['description']) > 0) {
                
                $this->description = stripslashes($data['description']);
                
            } else {
                $this->data['description'] = '';
            }
            
            $this->image = array();

            
            
            if (isset($data['newimage'])) {
                //uploading a new image
                $this->uploadingNew = true;
            } else {
                $this->uploadingNew = false;   
            }

            if (isset($data['origfile']) && $data['origfile'] !== '') {
                $this->origFile = $data['origfile'];   
            } else {
                $this->origFile = '';   
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
                        } else {
                            $this->image = $_FILES['image'];   
                        }
                        
                        break;
                    default:
                        $this->cError['image'] = 'Please make sure you only upload a jpg, png or gif image';
                        break;
                        
                }
                
            }
            
            if (isset($data['nocost'])) {
                $this->nocost = true;
                $this->cost = -1;
            } else {
                $this->nocost = false;
                if (isset($data['cost']) && (int)$data['cost'] > 0) {
                    $this->cost = number_format((float)$data['cost'], 2);
                } else {
                    $this->cost = 0.00;
                }
            }
        }
        
        
        
        protected function loggedIn() {
            Loader::model('user/loginauth');
            
            
            if (!loginauthModel::isLoggedIn()) {
                
                
                $this->redirectAdm('login/login');
            }   
        }
        
        
    }