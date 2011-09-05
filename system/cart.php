<?php

    class cart {
    
        
        public function generateSessionToken($return = '', $force = false) {
            
            //we need to check if the user is logged in
            
            if (!isset($_COOKIE['tppproofing']) || $_COOKIE['tppproofing'] == '' || $force === true) {
                
                if (!empty($_SESSION)) {
                    $keys = array();
                    foreach ($_SESSION as $key => $s) {
                        if (stripos($key,'art_') > 1) {
                            $keys[] = $key;
                        }
                    }
                    foreach ($keys as $k) {
                        unset($_SESSION[$k]);
                    }
                }
                
                $randomcookie = '';
                
                $aplhaBeta = '1234567890-=_+qwertyuiopasdfghjklzxcvbnm';
                for ($x = 0; $x < 25; $x++) {
                    $randomcookie .= $aplhaBeta[rand(0,39)];
                }
                
                //generate the coookie:
            } else {
                $randomcookie = $_COOKIE['tppproofing'];   
            }
            global $controller;
            setcookie('tppproofing',$randomcookie,time()+60*60*24*60,$controller->getVar('url'));
            $_SESSION['cart_' . $randomcookie] = array();
            switch ($return) {
                case 'token':       
                    return substr($randomcookie,5,10);
                    break;
                case 'full':
                    return $randomcookie;
                    break;
                default:
                    break;
            }
        }
        
        
        //add to cart
        //remove from cart
        
        //update cart
        
        public function add($itemID = -1,$optionValues = array(), $optionNames = array(),$quantity = 1,$notes = '',$type = 1,$descriptiveName = '',$cName = '',$gName = '',$imageName = '', $c = 0, $p = 0, $g = 0) {

            if ($itemID > -1) {
                $cookiename = $_COOKIE['tppproofing'];
                
                //check if the cart session exists
                
                if (!isset($_SESSION['cart_' . $cookiename])) {

                    
                    //we need to create the cart session with the user id - and if the user is not logged in then create it with a random token from a cookie
                    $_SESSION['cart_' . $cookiename] = array();
                } else {
                 
                    //check it has not been submitted to the checkout!
                    Loader::model('cart/orders');
                    
                    $sessSubmitted = ordersModel::checkSession($cookiename);
                    
                    if ($sessSubmitted === true) {
                     
                        unset($_SESSION['cart_' . $cookiename]);
                        $cookiename = cart::generateSessionToken('full', true);
                        
                        $_SESSION['cart_' . $cookiename] = array();

                        
                    }
                    
                }

                
                
                $item = '';
                

                //now we got to check if the current thing being added already exists - if it does then just update the cart

                
                
                if (!empty($optionNames) && !empty($optionValues)) {
                    
                    $item = str_replace(array('"', '{', '}'), array('-', '?', '!'), $itemID . '_' . (string)serialize($optionNames));

                    
                    $optionArr = $optionValues;
                } else {
                    $item = $itemID;   
                    $optionArr = '';
                }
                
                if ($notes !== '') {
                    $item .= '_n';
                }

                $item .= '_t';

                if (empty($_SESSION['cart_' . $cookiename][$item])) {
                    $cost = (float)0.00;
                    if (!empty($optionArr) && is_array($optionArr)) {
                        //figure out the cost of this item!
                        foreach ($optionArr as $k => $op) {
                            foreach ($op as $o) {
                                
                                $tmpArr = explode('__', $o);
                                
                                $cost += (float)($tmpArr[1] * $quantity);
                            }
                        }
                    }
                    
                    $_SESSION['cart_' . $cookiename][$item] = array(
                                                                    
                                                                    'itemId'            =>  $itemID,
                                                                    'options'           =>  $optionArr,
                                                                    'notes'             =>  $notes,
                                                                    'quantity'          =>  $quantity,
                                                                    'descriptiveName'   =>  $descriptiveName,
                                                                    'type'              =>  $type,
                                                                    'client'            =>  $cName,
                                                                    'gallery'           =>  $gName,
                                                                    'cost'              =>  number_format($cost,2),
                                                                    'imageName'         =>  $imageName,
                                                                    'c'                 =>  (int)$c,
                                                                    'p'                 =>  (int)$p,
                                                                    'g'                 =>  (int)$g,
                                                                    );
                    

                    
                } else {

                    //if notes are not set then just increase the quantity of this cart item!

                    
                    
                    if (substr(strrev($item), 2, 1) == 'n') {
                        $count = 1;
                        $item_new = '';
                        $count += count($_SESSION['cart_' . $cookiename]);
                        
                        
                        //figure out the new cost:
                        $cost = (float)0.00;
                        if (!empty($optionArr) && is_array($optionArr)) {
                            
                            //figure out the cost of this item!
                            foreach ($optionArr as $k => $op) {
                                foreach ($op as $o) {
                                    $tmpArr = explode('__', $o);
                                    $cost += (float)($tmpArr[1] * $quantity);
                                }
                            }
                        }
                        
                        $_SESSION['cart_' . $cookiename][$item . '_' . $count] = array(
                                                                                       'itemId'            =>  $itemID,
                                                                                       'options'           =>  $optionArr,
                                                                                       'notes'             =>  $notes,
                                                                                       'quantity'          =>  $quantity,
                                                                                       'descriptiveName'   =>  $descriptiveName,
                                                                                       'type'              =>  $type,
                                                                                       'client'            =>  $cName,
                                                                                       'gallery'           =>  $gName,
                                                                                       'cost'              =>  number_format($cost,2),
                                                                                       'imageName'         =>  $imageName,
                                                                                       'c'                 =>  (int)$c,
                                                                                       'p'                 =>  (int)$p,
                                                                                       'g'                 =>  (int)$g,
                                                                                       );
                        
                    
                    } else {
                        //figure out the new cost:
                        $cost = (float)0.00;
                        if (!empty($optionArr) && is_array($optionArr)) {
                            
                            //figure out the cost of this item!
                            foreach ($optionArr as $k => $op) {
                                foreach ($op as $o) {
                                    $tmpArr = explode('__', $o);
                                    $cost += (float)($tmpArr[1] * $quantity);
                                }
                            }
                        }
                       
                        $_SESSION['cart_' . $cookiename][$item]['quantity']+= $quantity;

                        $cost += (float)$_SESSION['cart_' . $cookiename][$item]['cost'];

                        $_SESSION['cart_' . $cookiename][$item]['cost'] = number_format($cost, 2);
                        
                    }

                }

            }
        }
        
        
        static function getCart() {
            $returnArr = array();
            
            if (!isset($_COOKIE['tppproofing'])) {
                return $returnArr;
            }
            
            $token = $_COOKIE['tppproofing'];
            

            if (isset($_SESSION['cart_' . $token]) && !empty($_SESSION['cart_' . $token])) {
                //check it has not been submitted to the checkout!
                Loader::model('cart/orders');
                
                $sessSubmitted = ordersModel::checkSession($token);
                
                if ($sessSubmitted === true) {
                    
                    unset($_SESSION['cart_' . $token]);
                    $cookiename = cart::generateSessionToken('full', true);
                    
                    $_SESSION['cart_' . $cookiename] = array();
                    return array();
                    
                }
                
                
                return $_SESSION['cart_' . $token];
            }
            
            
            return $returnArr;
            
        }
        
        public function remove($itemID = '-1',$quantity = 1) {
            $cookiename = $_COOKIE['tppproofing'];
            if ($itemID !== '-1') {
                $cookiename = 'cart_' . $cookiename;
                
                if ($quantity == 1) {
                
                    if (!empty($_SESSION[$cookiename][$itemID])) {
                        if ($_SESSION[$cookiename][$itemID]['quantity'] > 1) {
                            $_SESSION[$cookiename][$itemID]['quantity']--;   
                        }
                    } else {
                        unset($_SESSION[$cookiename][$itemID]);   
                    }
                    
                
                } else {
                    unset($_SESSION[$cookiename][$itemID]);   
                }
            }
        }
        
        
    
    }

