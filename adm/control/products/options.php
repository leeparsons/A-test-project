<?php
    
    class options extends controller {
        public $cError = array();
        
        public function index() {
            
            Loader::model('user/loginauth');
            
            
            if (!loginauthModel::isLoggedIn()) {
            
           
                $this->redirectAdm('login/login');
            }
            //get the list of type:
            

            Loader::model('products/product');
            
            $options = productModel::getOptions();
            
            $this->create = $this->admUrl . 'products/options/add/';
            
            $this->options = array();

            
                       
            if (!empty($options)) {
             
                foreach ($options as $option) {

                    if ($option['image'] !== '' && $option['image'] !== null) {
                    
                        $imageTool = new image($option['image'],'../localimages','local','localthumb');
                        $image = $imageTool->resize(true);
                        unset($imageTool);
                        
                    } else {
                        $imageTool = new image();
                        $image = $imageTool->getNone('localthumb');
                        unset($imageTool);
                    }
                    
                    if ($option['c'] == 0) {
                        $remove = '<a href="' . $this->admUrl . 'products/options/remove/?t=' . $option['tID'] . '&amp;p=' . $option['pID'] . '">remove this option</a>';
                        $notes = '';
                    } else {
                     
                        $remove = '<a href="' . $this->admUrl . 'products/options/remove/?t=' . $option['tID'] .  '&amp;p=' . $option['pID'] . '">remove this option</a>';
                        $notes = '<span>This option has ' . $option['c'] . ' galleries linked to it. Removing this option will remove the option from those products.</span>';
                    }
                    
                    
                    $values = array();
                    
                    if (count($option['value']) == count($option['cost']) && count($option['cost']) == count($option['valueIDs']) && count($option['value']) > 0) {
                     
                        $costArr = explode('__',$option['cost']);
                        $valueArr = explode('__',$option['value']);
                        $valueIDArr = explode('__',$option['valueIDs']);
                        
                        foreach ($costArr as $i => $cost) {
                         
                            $values[] = array(
                                           'cost'       =>  '&pound; ' . $cost,
                                           'valueID'    =>  $valueIDArr[$i],
                                           'value'      =>  $valueArr[$i]
                                           );
                            
                            
                        }
                        
                    }
                                                         
                                                         
                                                         
                    
                    
                    $this->options[] = array(
                                           
                                             'name'             =>  $option['tName'] . ': ' . $option['pName'],
                                             'image'            =>  $image,
                                             'description'      =>  $option['pDescription'],
                                             'link'             =>  $this->admUrl . 'products/options/edit/?t=' . $option['tID'] . '&amp;p=' . $option['pID'],
                                             'remove'           =>  $remove,
                                             'values'           =>  $values,
                                             'notes'            =>  $notes
                                        );
                }
                
            }
            
            
            
        }
        
        
        public function add() {
            Loader::model('user/loginauth');
            
            
            if (!loginauthModel::isLoggedIn()) {
                
                
                $this->redirectAdm('login/login');
            }
            
            $this->action = $this->admUrl . 'products/options/save/';
            
            $this->populous();
            
            
            $this->p = 'new';
            $this->template = 'addoptions';
            
        }
        
        public function save() {
            Loader::model('user/loginauth');
            
            if (!loginauthModel::isLoggedIn()) {
                
                
                $this->redirectAdm('login/login');
            }
            

            $this->validate();
            if (!empty($this->cError)) {

                $this->action = $this->admUrl . 'products/options/save/';
                $this->populous();
                $this->template = 'addoptions';
                
            } else {

                Loader::model('products/product');
                productModel::createProductValues($this->t, $this->name, $this->description, $this->editOptions);
                $this->redirectAdm('products/options');
                
            }
            
        }
        
        
        private function validate() {

            if (isset($_POST['description'])) {
                $this->description = stripslashes($_POST['description']);
            } else {
                $this->description = '';   
            }
            
            if (isset($_POST['p'])) {
                if ((int)$_POST['p'] > 0) {
                    $this->p = (int)$_POST['p'];
                } else {
                    $this->p = -1;
                }
            } else {
                $this->redirectAdm('product/options/');
            }
            
            
            if (isset($_POST['t']) && (int)$_POST['t'] > 0) {
                $this->t = (int)$_POST['t'];                
            } else {
                $this->redirectAdm('products/options/');
            }
            
            
            $this->editOptions = array();

            
            if (isset($_POST['countoptions']) && (int)$_POST['countoptions'] > 0) {

                if (isset($_POST['option-value-i']) && isset($_POST['option-cost']) && isset($_POST['option-value'])) {

                    foreach ($_POST['option-value-i'] as $k => $i) {
                    
                        if (isset($_POST['option-value'][$i])) {
                         
                            $this->editOptions[] = array(
                                                         'name'    =>  stripslashes($_POST['option-value'][$i]),
                                                         'cost'    =>  number_format((float)stripslashes($_POST['option-cost'][$i]),2)
                                                         );
                            
                        }
                        
                    }
                    
                    
                }
                    
                
                
            }
            //now cycle through all the new ones and add them in too!

            if (isset($_POST['new-countoptions']) && (int)$_POST['new-countoptions'] > 0) {
                
                if (isset($_POST['new-option-value-i']) && isset($_POST['new-option-cost']) && isset($_POST['new-option-value'])) {
                    
                    foreach ($_POST['new-option-value-i'] as $k => $i) {
                        
                        if (isset($_POST['new-option-value'][$i])) {
                            
                            $this->editOptions[] = array(
                                                         'name'    =>  stripslashes($_POST['new-option-value'][$i]),
                                                         'cost'    =>  number_format((float)stripslashes($_POST['new-option-cost'][$i]),2)
                                                         );
                            
                        }
                        
                    }
                    
                    
                }
                
                
                
            }
            
            if (isset($_POST['name']) && $_POST['name'] !== '')  {
                $this->name = stripslashes($_POST['name']);
            } else {
                $this->name = '';
                $this->cError['name'] = 'Please enter a name';   
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
            
            
            Loader::model('products/product');

            
            $types = productModel::getTypes();
            $this->types = array();            

            if (empty($types)) {
             
                $this->message = '<a href="' . $this->admUrl . 'products/types/add/">Create product types first</a>';
                
            } else {
                
                foreach ($types as $type) {
            
                    
                    
                    
                    $this->types[] = array(
                                           'name'   =>  $type['name'],
                                           't'      =>  $type['typeID']
                                           );
                    
                    
                }
                
            }
            
            
            
        }
    
        
        public function edit() {
            
            
            
            if (isset($_GET['p']) && isset($_GET['t']) && (int)$_GET['p'] > 0 && (int)$_GET['t'] > 0) {
                
                
                $this->p = $p = (int)$_GET['p'];    
                
                $this->t = $t = (int)$_GET['t'];
                
            } else {
                
                $this->redirectAdm('products/options/');   
            }
            $this->action = $this->admUrl . 'products/options/save/';
            $this->template = 'editoptions';
            
            Loader::model('products/product');
            
            $options = productModel::getOption($p,$t);
            
            
            $this->options = array();
            

            
            if (!empty($options)) {
                
                foreach ($options as $option) {
                    //should only be one option!
                    if ($option['image'] !== '' && $option['image'] !== null) {
                        
                        $imageTool = new image($option['image'],'../localimages','local','local');
                        $image = $imageTool->resize(true);
                        unset($imageTool);
                        
                    } else {
                        $imageTool = new image();
                        $image = $imageTool->getNone('local');
                        unset($imageTool);
                    }
                    
                    
                    $values = array();
                    
                    if (count($option['value']) == count($option['cost']) && count($option['cost']) == count($option['valueIDs']) && count($option['value']) > 0) {
                        
                        $costArr = explode('__',$option['cost']);
                        $valueArr = explode('__',$option['value']);
                        $valueIDArr = explode('__',$option['valueIDs']);
                        
                        foreach ($costArr as $i => $cost) {
                            
                            $values[] = array(
                                              'cost'       =>  '&pound; ' . $cost,
                                              'valueID'    =>  $valueIDArr[$i],
                                              'value'      =>  $valueArr[$i]
                                              );
                            
                            
                        }
                        
                    }
                    
                    
                    
                    
                    
                    $this->option = array(
                                          
                                          'name'            =>  $option['tName'] . ': ' . $option['pName'],
                                          'image'           =>  $image,
                                          'description'     =>  $option['pDescription'],
                                          'link'            =>  $this->admUrl . 'products/options/',
                                          'values'          =>  $values,
                                          'p'               =>  $p,
                                          't'               =>  $t
                                             );
                }
                
  
            }
            
            
            
        }
        
        public function remove() {
         
            if (isset($_GET['t']) && isset($_GET['p'])) {
                if ((int)$_GET['t'] > 0 && (int)$_GET['p'] > 0) {
                    
                    //we need to make sure we delete only the certain values!
                    
                    Loader::model('products/product');
                    
                    productModel::removeProduct((int)$_GET['t'],(int)$_GET['p']);
                    
                }
            }
            
            
            $this->redirectAdm('products/options/');
            
        }
        
        
        
    }