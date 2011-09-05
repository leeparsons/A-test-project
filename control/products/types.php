<?php
    
    class types extends controller {
        public $cError = array();
        
        public function index() {
            Loader::model('user/loginauth');
            
            
            if (!loginauthModel::isLoggedIn()) {
            
           
                $this->redirectAdm('login/login');
            }
            //get the list of type:
            
            
            Loader::model('products/product');
            
            $types = productModel::getTypes();
            
            $this->create = $this->admUrl . 'products/types/add/';
            
            $this->types = array();
            
            if (!empty($types)) {
             
                foreach ($types as $type) {
                    
                    if ($type['image'] !== '' && $type['image'] !== null) {
                    
                        $imageTool = new image($type['image'],'../localimages','local','localthumb');
                        $image = $imageTool->resize(true);
                        unset($imageTool);
                        
                    } else {
                        $imageTool = new image();
                        $image = $imageTool->getNone('localthumb');
                        unset($imageTool);
                    }
                    
                    if ($type['linkedProducts'] == 0) {
                        $remove = '<a href="' . $this->admUrl . 'products/types/remove/?t=' . $type['typeID'] . '">remove this type</a>';
                        $notes = '';
                    } else {
                     
                        $remove = '<a href="' . $this->admUrl . 'products/types/remove/?t=' . $type['typeID'] . '">remove this type</a>';
                        $notes = '<span>This type has ' . $type['linkedProducts'] . ' linked to it. Removing this product will remove the options from those products.</span>';
                    }
                    
                    $this->types[] = array(
                                           
                                           'name'           =>  $type['name'],
                                           'image'          =>  $image,
                                           'description'    =>  $type['description'],
                                           'link'           =>  $this->admUrl . 'products/types/view/?t=' . $type['typeID'],
                                           'remove'         =>  $remove,
                                           'notes'          =>  $notes
                                        );
                }
                
            } else {
                $this->create = $this->admUrl . 'products/types/add/';   
            }
            
            
            
        }
        
        
        public function add() {
            Loader::model('user/loginauth');
            
            
            if (!loginauthModel::isLoggedIn()) {
                
                
                $this->redirectAdm('login/login');
            }
            
            $this->action = $this->admUrl . 'products/types/save/';
            
            $this->populous();
            
            
            $this->template = 'addtype';
            
        }
        
        public function save() {
            Loader::model('user/loginauth');
            
            
            if (!loginauthModel::isLoggedIn()) {
                
                
                $this->redirectAdm('login/login');
            }
            
            $this->validate();

            if (!empty($this->cError)) {
                
                $this->action = $this->admUrl . 'products/types/save/';

                $this->template = 'addtype';
                
            } else {
                $this->populous();
                                
                Loader::model('products/product');

                //check that the product type does not already exist!
                
                
                if (!empty($this->file) && is_array($this->file)) {
                    $imageTool = new image($this->file['name'],'../localimages','local','local');
                    $imageTool->setOriginal($this->file['tmp_name']);
                    $imageTool->upload();
                    unset($imageTool);
                } else {
                    $this->file['name'] = '';
                }

                productModel::createType($this->name,$this->description,str_replace(' ','_',strtolower($this->file['name'])),$this->how,$this->section);
                $this->redirectAdm('products/types');
                
            }
            
        }
        
        
        private function validate() {
            Loader::model('products/product');
            
            if (isset($_POST['t'])) {
                $this->t = (int)$_POST['t'];   
            } else {
                $this->t = -1;   
            }
            
            if (!isset($_POST['name']) || $_POST['name'] == '') {
                $this->cError['name'] = 'Please enter a valid name';
                $this->name = stripslashes($_POST['name'],$this->t);
            } else {
                $this->name = stripslashes($_POST['name']);
                if (productModel::typeExists($this->name)) {
                    $this->cError['name'] = 'That name is already taken';
                }
            }
            
            if (isset($_POST['description'])) {
                $this->description = stripslashes($_POST['description']);
            } else {
                $this->description = '';   
            }
            $this->file = array();
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
                            $this->file = $_FILES['image'];   
                        }
                        
                        break;
                    default:
                        $this->cError['image'] = 'Please make sure you only upload a jpg, png or gif image';
                        break;
                        
                }
                
            }
            
            if (isset($_POST['uploadnew']) && $_POST['uploadnew'] == 'on') {
                //uploading a new image
                $this->uploadingNew = true;
            } else {
                $this->uploadingNew = false;   
            }
            
            if (isset($_POST['origfile']) && $_POST['origfile'] !== '') {
                $this->origFile = $_POST['origfile'];   
            } else {
                $this->origFile = '';   
            }
            
            
            if (isset($_POST['section'])) {
                switch ((int)$_POST['section']) {
                    case 2:
                        $this->section = 2;
                        break;
                    default:
                        $this->section = 1;
                        break;
                        
                }
            } else {
                $this->section = 1;
                $this->cError['section'] = 'Please select a section';
            }
            
            
            if (isset($_POST['how'])) {
                switch ((int)$_POST['how']) {
                    case 2:
                        $this->how = 2;
                        break;
                    default:
                        $this->how = 1;
                        break;
                        
                }
            } else {
                $this->how = 1;
                $this->cError['section'] = 'Please select a method';
            }
            

            
        }
        
        
        private function populous() {
         
            if (isset($_POST['name'])) {
                $this->name = $_POST['name'];   
            } else {
                $this->name = '';   
            }

            
            if (isset($_POST['description'])) {
                $this->description = $_POST['description'];   
            } else {
                $this->description = '';   
            }
            
            if (isset($_POST['how'])) {
                switch ((int)$_POST['how']) {
                    case 2:
                        $this->how = 2;
                        break;
                    default:
                        $this->how = 1;
                        break;
                        
                }   
            } else {
                $this->how = 1;
            }

            if (isset($_POST['section'])) {
                switch ((int)$_POST['section']) {
                    case 2:
                        $this->section = 2;
                        break;
                    default:
                        $this->section = 1;
                        break;
                        
                }   
            } else {
                $this->section = 1;
            }
            
            if (isset($_POST['t'])) {
                $this->t = $_POST['t'];
            } elseif (isset($_GET['t'])) {
                $this->t = $_GET['t'];
            } else {
                $this->t = -1;   
            }
            
        }
        
        
        public function view() {
            Loader::model('user/loginauth');
            
            
            if (!loginauthModel::isLoggedIn()) {
                
                
                $this->redirectAdm('login/login');
            }
            
            
            if ((isset($_GET['t']) && (int)$_GET['t'] > 0) || (isset($_POST['t']) && (int)$_POST['t'] > 0)) {
                
                $this->action = $this->admUrl . 'products/types/savetype/';
                if (isset($_POST['t'])) {
                    $t = (int)$_POST['t'];
                } elseif (isset($_GET['t'])) {
                    $t = (int)$_GET['t'];
                }


                Loader::model('products/product');
                
                $type = productModel::getType($t);

                
                if (empty($type)) {
                 
                    $this->redirectAdm('products/types/');
                    exit;
                }
                $this->t = $t;
                $this->cancel = $this->admUrl . 'products/types/';
                
                $this->type = array();
                
                $type = $type[0];
                    
                
                if ($type['image'] !== '' && $type['image'] !== null) {
                    $imageName = $type['image'];
                    $imageTool = new image($type['image'],'../localimages','local','local');
                    $image = $imageTool->resize(true);
                    unset($imageTool);
                    
                } else {
                    $imageName = '';
                    $imageTool = new image();
                    $image = $imageTool->getNone('local');
                    unset($imageTool);
                }
                
                if ($type['linkedProducts'] == 0) {
                    $remove = '<a href="' . $this->admUrl . 'products/types/remove/?t=' . $type['typeID'] . '">remove this type</a>';
                    $notes = '';
                } else {
                    
                    $remove = '<a href="' . $this->admUrl . 'products/types/remove/?t=' . $type['typeID'] . '">remove this type</a>';
                    $notes = '<span>This type has ' . $type['linkedProducts'] . ' linked to it. Removing this product will remove the options from those products.</span>';
                }
                
                switch ($type['section']) {
                    case 2:
                        $section = 'In one off products';
                        $how = '';
                        break;
                    default:
                        $section = 'In galleries';
                        if ($type['how'] == 1) {
                            $how = 'Individually';   
                        } else {
                            $how = 'As a collection';
                        }
                        break;
                }
                
                $this->type = array(
                                    'name'          =>  $type['name'],
                                    'image'         =>  $image,
                                    'description'   =>  $type['description'],
                                    'link'          =>  $this->admUrl . 'products/types/view/?t=' . $type['typeID'],
                                    'remove'        =>  $remove,
                                    'notes'         =>  $notes,
                                    'section'       =>  $section,
                                    'how'           =>  $how,
                                    't'             =>  $t,
                                    'imagename'     =>  $imageName
                                       );
                

                
                $this->template = 'edittype';

            } else {
                $this->redirectAdm('products/types/');   
            }

            
            
            
        }
        
    
        
        public function savetype() {
            
            Loader::model('user/loginauth');
            
            
            
            if (!loginauthModel::isLoggedIn()) {
                
                
                $this->redirectAdm('login/login');
            }

            $this->validate();
            if (!empty($this->cError)) {
                $this->populous();
                $this->template = 'edittype';
            } else {

                Loader::model('products/product');
                
                //check that the product type does not already exist!
                
                //need to figure out if we're uploading a new file
                if (!empty($this->file) && is_array($this->file)) {
                    $imageTool = new image($this->file['name'],'../localimages','local','local');
                    $imageTool->setOriginal($this->file['tmp_name']);
                    $imageTool->upload();
                    unset($imageTool);
                } elseif ($this->uploadingNew === false) {
                    $this->file['name'] = $this->origFile;
                } else {
                    $this->file['name'] = '';   
                }
                productModel::updateType($this->name,$this->description,str_replace(' ','_',strtolower($this->file['name'])),(int)$this->t);

                $this->redirectAdm('products/types');
                
            }   
        }
     
        
        public function remove() {

            Loader::model('user/loginauth');
            
            
            
            if (!loginauthModel::isLoggedIn()) {
                
                
                $this->redirectAdm('login/login');
            }
            
            
            if (isset($_GET['t']) && (int)$_GET['t'] > 0) {
             
                
                Loader::model('products/product');
                
                
                productModel::removeType((int)$_GET['t']);
                
            }
            
            
            $this->redirectAdm('products/types/');
            
            
        }
        
    }