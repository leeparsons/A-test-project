<?php

    class viewCart extends controller {

        public function index() {           

            $this->title = 'View your cart';
            
            $this->cart = array();
            Loader::system('cart');
            $this->action = $this->url . 'cart/viewcart/shipping/';

            
            
            $total = (float)0.00;
            $cartDetail = '';
            $this->metaTitle = 'View Your Cart';

            foreach (cart::getCart() as $k => $c) {
                $imageTool = new image($c['imageName'], $c['client'], $c['gallery'], 'cart');
                $image = $imageTool->resize(true);
                unset($imageTool);
                $detail = '';
                $this->generateCartDetailFromOptions($c, $detail, $cartDetail);                                
                
                if (!isset($_COOKIE['tppproofing']) || !isset($_SESSION['cart_' . $_COOKIE['tppproofing']])) {
                    Loader::system('cart');                    
                    $token = cart::generateSessionToken('token');

                } else {
                    $token = substr($_COOKIE['tppproofing'], 5, 10);
                }
                
                
                $plus = $this->url . 'cart/addtocart/addone/?tkn=' . urlencode($token) . '&amp;s=' . urlencode($k);
                $minus = $this->url . 'cart/addtocart/takeone/?tkn=' . urlencode($token) . '&amp;s=' . urlencode($k);
                
                $url = $this->url . 'photoview/?c=' . $c['c'] . '&amp;g=' . $c['g'] . '&amp;p=' . $c['p'];

                $this->cart[] = array(
                                      'image'       =>  $image,
                                      'name'        =>  $c['descriptiveName'],
                                      'description' =>  $detail,
                                      'quantity'    =>  $c['quantity'],
                                      'cost'        =>  $c['cost'],
                                      'identitier'  =>  $k,
                                      'notes'       =>  $c['notes'],
                                      'url'         =>  $url,
                                      'plus'        =>  $plus,
                                      'minus'       =>  $minus
                                    );
                
                
                $total += (float)$c['cost'];
                
            }
            $this->cartDescription = $cartDetail;
            
            
            $this->total = '&pound; ' . number_format((float)$total,2);
            $this->unformattedTotal = number_format((float)$total,2);
            $this->vatRate = '0.00%';
            
            $this->vatTotal = false;
            
            $this->subTotal = '&pound; ' . number_format((float)$total,2);
            
            $this->cards = $this->url . 'images/cards.gif';
            
            $this->message = 'You currently have no items in your cart';
        }

        
        public function shipping() {
            Loader::system('cart');
            $cartStuff = cart::getCart();
            if (empty($cartStuff)) {
                $this->redirect('cart/viewcart/');
            }
            $cartDetail = '';
            $total = (float)0.00;
            foreach ($cartStuff as $k => $c) {
            
                $detail = '';            
                $this->generateCartDetailFromOptions($c, $detail, $cartDetail);
                $total += (float)$c['cost'];

            }

            $this->cartDetail = htmlentities($cartDetail);

            $this->metaTitle = 'Shipping Options';
                
            $this->backUrl = $this->url . 'cart/viewcart/';                
            $this->total = number_format((float)$total, 2);
            $this->cards = $this->url . 'images/cards.gif';
            $this->action = $this->url . 'cart/process/proc/';
            $this->title = 'Shipping Options';
            $this->template = 'shipping';
            
            
            $data = $this->request('get');
            
            $this->rName = '';
            $this->rAddress = '';
            $this->sInstructions = '';
            $this->nameError = '';
            $this->addressError = '';
            $this->specialError = '';
            
            if (isset($data['e'])) {
                if (isset($data['raddress']) && strlen($data['raddress']) > 0) {
                    $this->rAddress = stripslashes($data['raddress']);
                } else {
                    $this->addressError = 'Please enter the recipient&rsquo;s address';   
                }
                
                if (isset($data['rname']) && strlen($data['rname']) > 0) {
                    $this->rName = stripslashes($data['rname']);   
                } else {
                    $this->nameError = 'Please enter the recipient&rsquo;s name';   
                }

                if (isset($data['special'])) {
                    $this->sInstructions = stripslashes($data['special']);   
                }
                
            }
            
            
        }
        
        protected function generateCartDetailFromOptions($cObj, &$detail, &$cartDetail) {
            if (!empty($cObj['options'])) {
                
                foreach ($cObj['options'] as $oName => $opnArr) {
                    
                    if (!empty($opnArr)) {
                        $detail .= ($detail == '')?'<b>' . ucwords($oName) . ':</b><br/>':'<br/><b>' . ucwords($oName) . ':</b><br/>';
                        foreach ($opnArr as $opn => $val) {
                            $spaces = '';
                            for ($x = 0; $x < strlen($oName); $x++) {
                                $spaces .= '&nbsp;';
                            }
                            $detail .= $spaces;
                            $opn_detail = explode('__', $val);
                            
                            $detail .= ucwords($opn) . ' ' . $opn_detail[0] . ' &ndash; &pound;' . $opn_detail[1] . '<br/>';
                        }
                    }
                    $cartDetail .= 'Client: ' . $cObj['client'] . ', Gallery: ' . $cObj['gallery'] . "\n\n" . $cObj['quantity'] . 'x Product:' . "\n\n" . 'Image: ' . $cObj['imageName'] . ', options: ' . $detail .  ' cost: &pound; ' . $cObj['cost'] . "\n\n" . 'Notes:' . $cObj['notes'];
                    
                }
            }
            
        }
        
        
    }

