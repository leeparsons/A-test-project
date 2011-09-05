<?php

    class viewCart extends controller {

        public function index() {           

            $this->title = 'View your cart';
            
            $this->cart = array();
            Loader::system('cart');
            $this->action = $this->url . 'cart/process/proc/';
            $total = (float)0.00;
            foreach (cart::getCart() as $k => $c) {

                if (!empty($c['type'])) {

                    switch ($c['type']) {
                        case 1:
                            //an image
                            $imageTool = new image($c['imageName'], $c['client'], $c['gallery'], 'cart');
                            $image = $imageTool->resize(true);
                            unset($imageTool);
                            break;
                        case 2:
                            //a download
                            $imageTool = new image($c['imageName'], $clientInfo[0]['client'], $imageDetail['gallery']);
                            $image = $imageTool->resize(true);
                            unset($imageTool);
                            break;
                        default:
                            $image = '<img width="50" src="' . $this->url . 'images/none.png"/>';
                            //not known
                            continue;
                            break;
                    }
                    
                    
                    
                    
                } else {
                    $image = '<img width="50" src="' . $this->url . 'images/none.png"/>';
                    $type = '';
                    continue;
                }

                $detail = '';
                $cartDetail = '';
                if (!empty($c['options'])) {

                    foreach ($c['options'] as $oName => $opnArr) {

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
                        $cartDetail .= 'Client: ' . $c['client'] . ', Gallery: ' . $c['gallery'] . "\n\n" . 'Product:' . "\n\n" . 'Image: ' . $c['imageName'] . ', options: ' . $detail .  ' cost: &pound; ' . $c['cost'] . "\n\n";
                        
                    }
                }
                
                
                $url = $this->url . 'photoview/?c=' . $c['c'] . '&amp;g=' . $c['g'] . '&amp;p=' . $c['p'];

                $this->cart[] = array(
                                      'image'       =>  $image,
                                      'name'        =>  $c['descriptiveName'],
                                      'description' =>  $detail,
                                      'quantity'    =>  $c['quantity'],
                                      'cost'        =>  $c['cost'],
                                      'identitier'  =>  $k,
                                      'notes'       =>  $c['notes'],
                                      'url'         =>  $url
                                    );
                
                
                $total += (float)$c['cost'];
                
            }
            $this->cartDescription = $cartDetail;
            
            $this->total = '&pound; ' . number_format((float)$total,2);
            $this->unformattedTotal = number_format((float)$total,2);
            $this->vatRate = '0.00%';
            
            $this->vatTotal = false;
            
            $this->subTotal = '&pound; ' . number_format((float)$total,2);
            
            $this->paymenticons = $this->url . 'images/paymenticons.gif';
        }

    }