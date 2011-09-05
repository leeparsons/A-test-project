<?php

    class addToCart extends controller {

        public function add() {
            
            $this->addToCartNoRender = true;

            //we need to figure out if we are adding to the cart so that we don't get hackings!!          
            $data = $this->request('post');

            //determine if there is an itemID in the data
            //determine if the data contains submission token

            if (empty($data['tkn'])) {
                $this->redirect('/cart/viewcart');
            }
            
            if (!isset($_SESSION)) {
                $this->redirect();
            }
            
            if (!isset($_COOKIE['tppproofing'])) {
                $this->redirect();
            }
            
            $token = $_COOKIE['tppproofing'];

            if (substr($token,5,10) == $data['tkn']) {

                //add it to the cart!

                if (empty($data['cname']) || empty($data['gname'])) {
                    $this->redirect();
                }
                
                //get the itemID from the post data
                
                if (empty($data['itemID'])) {
                    $this->redirect('cart/viewcart');
                } else {
                    
                    $itemID = $data['itemID'];
                    if (empty($data['quantity'])) {
                        $quantity = 1;   
                    } else {
                        $quantity = (int)$data['quantity'];
                    }
                    
                    
                    if (!isset($data['type']) || !is_numeric($data['type'])) {
                        $data['type'] = 1;
                    }
                    
                    $type = $data['type'];
                    
                    if (!isset($data['pname'])) {
                        $descriptiveName = '';
                    } else {
                        $descriptiveName = $data['pname'];
                    }
                    
                    //figure out if there are extras to show:
                    
                    $opns = array();
                    $optionNames = array();
                    if (isset($data['options'])) {
                        foreach ($data['options'] as $type => $parr) {
                            foreach ($parr as $k => $v) {
                                if ($v !== -1 && $v !== '' && $v !== '-1') {
                                    
                                    $optionNames[$type][] = explode('__', $v);
                                    
                                    $opns[$type][$k] = $v;
                                }
                            }
                        }
                    }
                    
                    if (!empty($data['notes'])) {
                        $notes = $data['notes'];   
                    } else {
                        $notes = '';   
                    }
                    

                    
                    Loader::system('cart');

                    if (isset($data['ci'])) {
                        $c = (int)$data['ci'];   
                    } else {
                        $c = 0;
                    }
                    if (isset($data['p'])) {
                        $p = (int)$data['p'];   
                    } else {
                        $p = 0;   
                    }
                    if (isset($data['g'])) {
                        $g = (int)$data['g'];   
                    } else {
                        $g = 0;   
                    }
                    cart::add($itemID,$opns,$optionNames,$quantity,$notes,$type,$descriptiveName,$data['cname'],$data['gname'],$data['imagename'], $c, $p, $g);
                }
 
                
                
            } else {
                $this->redirect('cart/viewcart/');
            }
            $this->redirect('cart/viewcart/');
        }
        
        public function addone() {

            $this->addToCartNoRender = true;
            
            //we need to figure out if we are adding to the cart so that we don't get hackings!!          
            $data = $this->request('get');
            
            //determine if there is an itemID in the data
            //determine if the data contains submission token
            
            if (empty($data['tkn'])) {
                $this->redirect('/cart/viewcart/');
            }
            
            if (!isset($_SESSION)) {
                $this->redirect();
            }
            
            if (!isset($_COOKIE['tppproofing'])) {
                $this->redirect();
            }
            
            $token = $_COOKIE['tppproofing'];
            
            if (substr($token, 5, 10) == $data['tkn']) {

                if (isset($data['s'])) {
                    
                    if (!empty($_SESSION['cart_' . $token][$data['s']])) {
                     
                        $q = (int)$_SESSION['cart_' . $token][$data['s']]['quantity'];
                        
                        //now add the cost!
                        
                        $cost = (float)$_SESSION['cart_' . $token][$data['s']]['cost'];

                        $_SESSION['cart_' . $token][$data['s']]['cost'] = number_format((float)($cost*($q + 1)/$q), 2);
                        
                        $_SESSION['cart_' . $token][$data['s']]['quantity'] = (int)($q + 1);

                    }
                    
                }
                
                
            }
            $this->redirect('cart/viewcart/');   
            
        }
        
        
        public function takeone() {
            
            $this->addToCartNoRender = true;
            
            //we need to figure out if we are adding to the cart so that we don't get hackings!!          
            $data = $this->request('get');
            
            //determine if there is an itemID in the data
            //determine if the data contains submission token
            
            if (empty($data['tkn'])) {
                $this->redirect('/cart/viewcart/');
            }
            
            if (!isset($_SESSION)) {
                $this->redirect();
            }
            
            if (!isset($_COOKIE['tppproofing'])) {
                $this->redirect();
            }
            
            $token = $_COOKIE['tppproofing'];
            
            if (substr($token, 5, 10) == $data['tkn']) {
                
                if (isset($data['s'])) {
                    
                    if (!empty($_SESSION['cart_' . $token][$data['s']])) {
                        
                        $q = (int)$_SESSION['cart_' . $token][$data['s']]['quantity'];
                        
                        if ($q == 1) {

                            unset($_SESSION['cart_' . $token][$data['s']]);
                            
                        } else {
                            //now add the cost!
                        
                            $cost = (float)$_SESSION['cart_' . $token][$data['s']]['cost'];
                        
                            $_SESSION['cart_' . $token][$data['s']]['cost'] = number_format((float)($cost*($q - 1)/$q), 2);
                        
                            $_SESSION['cart_' . $token][$data['s']]['quantity'] = (int)($q - 1);
                        }
                        
                    }
                    
                }
                
                
            }
            $this->redirect('cart/viewcart/');   
            
        }
        
        
    }