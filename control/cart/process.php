<?php

    class process extends controller {

        public function index() {
            
            $this->redirect();

        }

        public function successful() {
            try {
                
            $data = $_REQUEST;

            $this->rbsInstallationID = '244018';
            
            if (isset($data['instId']) && $data['instId'] == $this->rbsInstallationID) {

                if (isset($data['cartId']) && strlen($data['cartId']) > 0) {
                    
                    if (isset($data['msgType']) && $data['msgType'] == 'authResult') {
                     
                        $address = (isset($data['address']))?$data['address']:'Address not filled in';

                        $postcode = (isset($data['postcode']))?$data['postcode']:'Postcode not filled in';
			if (isset($data['email'])) {
				$email = $data['email'];
				$sendCustomer = true;
			} else {
				$sendCustomer = false;
				$email = 'Not filled in';
			}	
                    
                        
                        //now get the information from the database
                        
                        Loader::model('cart/orders');
                        
                        $cartBits = explode('::', $data['cartId']);

                        ordersModel::updateOrder((int)$cartBits[1], array('status', 'cName', 'email'), array(1, $data['name'], $data['email']));
                        
                        //get the detail for this order
                        
                        $detail = ordersModel::getDetail((int)$cartBits[1]);
                        
                        
                        
                        
                        $detail = preg_replace('#<br\s*/?>#i', "\n", $detail);
                        $detail = preg_replace('#<b\s*/?>#i', "\n", $detail);

                        
                        
                        
                        
                        //now send an email out to the owner:
                        
                        $message = 'You have received a completed order through your proofing parlour. The details are as follows:' . "\n\r";

                        $message .= 'Name: ' . $data['name'] . "\n\r";

                        $message .= 'Address: ' . nl2br($data['address']) . "\n" . $data['postcode'] . "\n\r";
                        
                        $message .= 'Email: ' . $data['email'] . "\n\r";

                        $message .= 'Detail:' . "\n\r" . $detail . "\n\r";

                        $message .= 'Amount: ' . $data['amount'] . $data['currency'] . "\n\r";
                        
                        $to = 'rosie@rosieparsons.com';
                        
                        $from = 'admin@' . str_replace('www.', '', getenv("HTTP_HOST"));

                        $headers = 'From: ' . $from;
                        
                        $subject = 'You have received payment for an order.';

                        mail($to, $subject, $message, $headers);

			$subject = 'Thank you for your order at ' . str_replace('www.', '', getenv("HTTP_HOST"));

			$message = 'Thank you for your order, we have received instruction to take payment of: ' . $data['amount'] . $data['currency'] . ' from the payment details provided.' . "\n\r";

			$message .= 'Here is a summary of your order: ' . "\n\r" . $detail;

			mail($email, $subject, $message, $headers);
                        
                    }
                    
                    
                }
                
                
            }

            } catch (Exception $x) {
             
                mail('rosie@rosieparsons.com', 'error', print_r($x, true));
                
            }
                
            die();
        }
        
                
        public function proc() {
            //check that the form is submitted by post:

            Loader::system('cart');
            $cartStuff = cart::getCart();
            if (empty($cartStuff)) {
                $this->redirect('cart/viewcart/');
            }
                
            $data = $this->request('post');

            $this->cError = '';
            $specialInstructions = '';
            $rName = '';
            $rAddress = '';
            
            if (isset($data['shipping'])) {
            
                if (!isset($data['rname']) || strlen($data['rname']) == 0) {
                    $this->cError .= '&name=';
                } else {
                    $rName = $data['rname'];   
                }
                
                if (!isset($data['rAddress']) || strlen($data['rAddress']) == 0) {
                    $this->cError .= '&address=';
                } else {
                    $rAddress = $data['rAddress'];   
                }
                
                if (isset($data['sinstructions'])) {
                    $specialInstructions = $data['sinstructions'];   
                }
            }
            
            if ($this->cError !== '') {

                $this->redirect('cart/viewcart/shipping/?e=1' . $this->cError . '&rname=' . $rName . '&raddress=' . rawurlencode($rAddress) . '&special=' . rawurlencode($specialInstructions));
            } else {

                //insert into the orders and use this as the cartID
                Loader::model('cart/orders');
                
                if (isset($_COOKIE['tppproofing'])) {
                    $sessID = $_COOKIE['tppproofing'];
                } else {
                    $sessID = '';
                }
                
                
                if (isset($_COOKIE['tpproofing_login'])) {
                    $userHash = $_COOKIE['tpproofing_login'];

                    //now get the user id:

                    Loader::model('user/status');

                    $idArr = statusModel::getUserInfo($userHash);

                    if (!empty($idArr)) {
                        if ((int)$idArr['id'] > 0) {
                            $userID = (int)$idArr['id'];
                        }
                    }

                } else {
                    $userID = '';
                }

		$data['desc'] = html_entity_decode($data['desc']);

                $cartID = ordersModel::createOrder($data['amount'], $data['vat'], $data['desc'], $userID, $sessID);

                $sessID .= '::' . $cartID;

                //now pass forward the url

                $to = 'rosie@rosieparsons.com';
                $subject = 'You have received the beginning of an order on your online proofing parlour.';
                $message = 'You have received the beginning of an order.' . "\n\r" . 'You have you not received payment for this order. Please wait for confirmation from your payment provider before sending the products.';

                $message .= "\n\r";

                $message .= 'Details:';

                $message .= "\n\r";

                $message .= 'Amount: ' . $data['amount'] . ' ' . $data['currency'];

                $message .= "\n\r";

                $message .= stripslashes($data['desc']);

                $message .= "\n\r";

                if (isset($data['shipping'])) {

                    $message .= 'Delivery Details:';

                    $message .= "\n\r";

                    $message .= 'Recipient Name: ' . $rName;

                    $message .= "\n\r";

                    $message .= 'Recipient Address: ' . $rAddress;

                    $message .= "\n\r";

                    $message .= 'Special Delivery Instructions: ' . $specialInstructions;
                }

                $headers = 'From: admin@' . str_replace('www.', '', $_SERVER['SERVER_NAME']);

                //Loader::model('logs/logs');
                //logsModel::insertLog('Sending Email to : ' . $to . "\n\r" . 'Subject: ' . $subject . "\n\r" . ' Message: ' . $message);

                mail($to, $subject, stripslashes(strip_tags($message)), $headers);
                
                $data['sessID'] = $sessID;
                
                
                
                $this->cartRedirect($data);
                
            }

        }

        public function standalone() {
    
            $data = $this->request('post');
            
            if (isset($data['tkn'])) {
                
                if (!isset($data['name']) || strlen($data['name']) == 0) {
                    //invalid entry!
                    $this->cError = 'name';
                    
                    $this->produceStandalone($data);
                    if (!isset($data['amount']) || !is_numeric(str_replace(',', '', $data['amount'])) || !intval($data['amount']) > 0) {
                        $this->customAmountError = 'true';
                        $this->customAmount = $data['amount'];
                    } else {
                        $this->customAmount = $data['amount'];
                        $this->customAmountError = '';
                    }
                    return false;
                }
                $this->cError = '';
                
                if (isset($data['amount']) && is_numeric(str_replace(',', '', $data['amount'])) && intval($data['amount']) > 0) {
                    $data['amount'] = $data['amount'];
                } else {
                    if (isset($data['amount'])) {
                        $this->customAmount = $data['amount'];
                    }
                    $this->clientName = $data['name'];
                    $this->customAmountError = 'true';
                    $this->produceStandalone($data);
                    return false;
                }


                Loader::model('cart/orders');
                
                if (isset($_COOKIE['tppproofing'])) {
                    $sessID = $_COOKIE['tppproofing'];
                } else {
                    $sessID = '';
                }
                
                
                if (isset($_COOKIE['tpproofing_login'])) {
                    $userHash = $_COOKIE['tpproofing_login'];
                    
                    //now get the user id:
                    
                    Loader::model('user/status');
                    
                    $idArr = statusModel::getUserInfo($userHash);
                    if (!empty($idArr)) {
                        if ((int)$idArr['id'] > 0) {
                            $userID = (int)$idArr['id'];
                            
                        }
                    }
                    
                } else {
                    $userID = '';
                }
                
                $escapedDate = array();
                
                foreach ($data as $k => $d) {
                    $escapedData[$k] = stripslashes($d);
                }
                
                $data = $escapedData;

                
                //get the information:
                
                Loader::model('products/product');
                
                

                if ($prod = productModel::getOneOffByURL($data['i'])) {

                    if (isset($data['amount'])) {
                        $vat = (int)0;
                        $prod['cost'] = str_replace(',', '', $data['amount']);
                    } else {
                        $vat = (int)0 * $prod['cost'];
                    }
                    
                    $dataMessage = 'Name: ' . $data['name'] . "\n\n" . 'Description' . $data['description'];
                    $cartID = ordersModel::createOrder($prod['cost'], $vat, $dataMessage . "\n\n" . $data['notes'], $userID, $sessID);
                    
                    $sessID .= '::' . $cartID;
                    
                    $to = 'rosie@rosieparsons.com';
                    $subject = 'You have received the beginning of an order on your online proofing parlour.';
                    $message = 'You have received the beginning of an order from: ' . $data['name'] . "\n\r" . 'You have you not received payment for this order. Please wait for confirmation from your payment provider before sending the products.';
                    
                    $message .= "\n\r";
                    
                    $message .= 'Details:';
                    
                    $message .= "\n\r";
                    
                    $message .= 'Amount: ' . $prod['cost'] . ' ' . $data['currency'];
                    
                    $message .= "\n\r";
                    
                    $message .= strip_tags($data['description']) . "\n\n" . strip_tags($data['notes']);
                    
                    $headers = 'From: admin@' . str_replace('www.', '', $_SERVER['SERVER_NAME']);
                    
                    Loader::model('logs/logs');
                    logsModel::insertLog('Sending Email to : ' . $to . "\n\r" . 'Subject: ' . $subject . "\n\r" . ' Message: ' . $message);

                    
                    mail($to, $subject, strip_tags($message), $headers);
                    
                    $data['amount'] = $prod['cost'];
                    $data['sessID'] = $sessID;
                    
                    
                    

                    
                    $this->cartRedirect($data);
                }
                
            } else {
                $this->redirect();   
            }
            
        }


        protected function cartRedirect($data) {
            
            $test = false;
            
            if ($test === true) {
                $url = 'https://secure-test.wp3.rbsworldpay.com/wcc/purchase?';
            } else {
                $url = 'https://secure.wp3.rbsworldpay.com/wcc/purchase?';
            }
            
            $url .= 'instId=' . '244018';
    
            $url .= '&cartId=' . $data['sessID'];
    
            $url .= '&amount=' . $data['amount'];
    
            $url .= '&currency=' . $data['currency'];
    
            if ($test === true) {
                $url .= '&testMode=100';
            }
    
            $url .= '&name=' . $data['name'];
    
            header('location: ' . $url);
     
            die();
        }



	protected function produceStandalone($data) {
			$this->template = '../products/standalone';
			Loader::model('products/product');
                	if (isset($data['i'])) {
				$p = $data['i'];
			} else {
				$this->redirect();
			}
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
			return false;
	}

    }