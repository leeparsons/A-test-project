<?php

    class standalone extends controller {

	var $cError = '';
	var $customAmountError = '';

        public function index() {


            //check to see if the gId, and cID are set:
            
            if (isset ($_GET['p']) && strlen($_GET['p']) > 0) {

                $p = (string)$_GET['p'];
                if (!isset($_COOKIE['tppproofing']) || !isset($_SESSION['cart_' . $_COOKIE['tppproofing']])) {
                    Loader::system('cart');
                    
                    $this->token = cart::generateSessionToken('token');
                    
                    $this->redirect('products/standalone/?' . $_SERVER['QUERY_STRING']);
                    
                }
		$this->clientName = '';


          
                
		Loader::model('products/product');
                
                if ($prod = productModel::getOneOffByURL($p)) {
                    $this->action = $this->url . 'cart/process/standalone/';
                    $this->name = $prod['name'];
                    $this->description = nl2br($prod['description']);
                    $this->id = $prod['urlID'];
                    $this->cost = $prod['cost'];
                    $this->image = $prod['image'];
                    $this->title = $this->name;

                    if ($this->cost == -1) {
                        $this->nocost = true;
                    } else {
                        $this->nocost = false;
                    }
                    
                    $this->tkn = '';

                    $this->tkn = substr($_COOKIE['tppproofing'], 5, 10);
                    
                    
                    if ($this->origFile !== '') {
                        
                        $imageTool = new image($this->image, '../localimages', 'local', 'local');
                        $this->image = $imageTool->resize(true);
                        unset($imageTool);
                        
                        
                    } else {
                        $imageTool = new image();
                        $this->image = $imageTool->getNone('local');
                        unset($imageTool);
                    }
                    
                    
                    
                    
                } else {
                    $this->redirect();   
                }                
            } else {
                
                
                $this->redirect();                
                
            }
            
        
        }



    }