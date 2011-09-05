<?php
    
    class photoview extends controller {

        public function index() {

            
            
            
            //check to see if the cId, and pID are set:
            
            if (isset ($_GET['p']) && isset($_GET['c']) && isset($_GET['g'])) {
                $c = (int)$_GET['c'];
                $g = (int)$_GET['g'];
                $p = (int)$_GET['p'];
                
                Loader::model('splash/gallery');
                Loader::model('splash/splash');
                
                //determine if the current gallery has  a password associated with it:
                
                $galleryInformation = galleryModel::getGalleryInformation($g);
                
                if ($galleryInformation === false) {
                    $this->redirect();
                }
                
                
                if (($galleryInformation['gExpiry'] <= time() && $galleryInformation['noexpiry'] == 0) || $galleryInformation['active'] == 0) {
                    $this->message = 'This gallery has expired';
                    $this->displayForm = false;
                    $this->template = 'galleryentry';
                    $this->gallery = $galleryInformation;
                    
                    return;
                }
                
                $clientInfo = splashModel::getClients($c);
                
                
                $this->title = (!stripos($galleryInformation['gName'], 'gallery'))?$clientInfo[0]['cName'] . '&rsquo;s ' . ucwords($galleryInformation['gName']) . ' Gallery':$clientInfo[0]['cName'] . ' &ndash; ' . ucwords($galleryInformation['gName']);                    
                $this->redirectUrl = urlencode('photoview/?c=' . $c . '&g=' . $g . '&p=' . $p);
                $this->metaTitle = $this->title;

                
                if ($galleryInformation['gpword'] !== '' && $galleryInformation['gpword'] !== null) {
                    
                    
                    //determine if this client has entered the password in the past 24 hours
                    if (!isset($_COOKIE['tpproofing_gallery_' . $g])) {
                        //redirect to the password page
                        $this->template = '../splash/galleryentry';
                        $this->gallery = $galleryInformation;
                        $this->gid = $g;
                        
                        
                        
                        $imageTool = new image($clientInfo[0]['splash'], $clientInfo[0]['cName'], 'splash', 'splash');
                        
                        
                        $this->mainImage = $imageTool->resize(true);
                        
                        if ($this->mainImage == '') {
                            $this->mainImage = $imageTool->getNone('splash');   
                        }
                        unset($imageTool);                        
                        
                        $this->cid = $c;
                        $this->message = 'This gallery is password protected.<br/><br/>Please enter the gallery password to view the images.<br/><br/>';
                        $this->action = $this->url . 'splash/gallery/entry/';
                        $this->displayForm = true;
                        return;
                    } else {
                        //readjust the cookie timer!
                        setcookie('tpproofing_gallery_' . $g, 1, time() + 60*60*24, $this->url);
                    }
                }
                
                

                if (!isset($_COOKIE['tppproofing']) || !isset($_SESSION['cart_' . $_COOKIE['tppproofing']])) {
                    Loader::system('cart');

                    $this->token = cart::generateSessionToken('token');

                    $this->redirect('photoview/?' . $_SERVER['QUERY_STRING']);

                }
                
                $this->loginLink = $this->url . 'users/login/';

                $this->cartLink = $this->url . 'cart/viewcart/';
                $this->template = 'original';



                $this->p = $p;
                $this->g = $g;
                $this->ci = $c;

                Loader::model('photoview/photoview');

                $imageDetail = photoviewModel::getImage($c, $g, $p);


                if (!$clientInfo || !$imageDetail) {
                    header('location: ' . $this->url);
                }
                $this->type = 1;
                $this->cName = $clientInfo[0]['cName'];
                $this->gName = $imageDetail['gName'];
                $imageTool = new image($imageDetail['iName'], $clientInfo[0]['cName'], $imageDetail['gName'], 'photoview');
                $this->img = $imageTool->resize(true);



                $imageTool->updateSystem('fancy');

                $this->fancy = $imageTool->resize(false);
 
                $imageName = $imageTool->getImageName();
                unset($imageTool);

		$this->metaTitle .= ' viewing: ' . $imageName;

                $this->js[] = $this->url . 'js/jquery.fancybox-1.3.4.js';
                $this->css[] = $this->url . 'css/fancybox.css';
                $prev = -1;
                $next = -1;
                $prevID = 0;
                $startCounting = (int)0;

                $images = photoviewModel::getGalleryNav($_GET['p'], $g, $c);
                $tImages = count($images) - 1;
                $this->token = '';
                $this->descriptiveName = $imageName;

                foreach ($images as $k => $im) {

                    if ($k == 0) {
                        $firstArr = photoviewModel::getFirstGallery($im['sID'],$g);
                        //$firstArr = photoviewModel::getPrevGallery($im['sID'],$g);
                        $lastArr = photoviewModel::getLastGallery($g);
                        //$lastArr = photoviewModel::getNextGallery($im['sID'],$g);
                        $firstGallery = $firstArr['p']; 
                        $lastGallery = $lastArr['p'];
                        $next = photoviewModel::getNextImageSetImage($im['sID'],$g);
                        $prev = photoviewModel::getPreviousImageSetImage($im['sID'],$g);

                    }
                    
                    $imageTool = new image($im['iName'], $clientInfo[0]['cName'], $imageDetail['gName'], 'navigation');
                    /*
                    if ($k < $tImages) {
                        //not yet reached the maximum so figure out the pagination
                        if ($k == 0 && $im['iID'] == $p) {
                            //this is problematic because the previous link can go to the previous gallery page   
                            //assume not for now

                            //get the image set after this one

                            $prev = photoviewModel::getPreviousImageSetImage($im['sID'], $g);
                            
                            $startCounting = (int)1;
                        } elseif ($p == $im['iID']) {
                            //normal activity
                            $prev = $prevID;
                            $startCounting = (int)1;
                        }
                    } elseif ($prev == -1) {
                        //normal stuff if k is the last image!
                        $prev = $prevID;
                        //figure out the next link also
                        $next = photoviewModel::getNextImageSetImage($im['sID'], $g);

                    
                    
                    
                    }
                    */
                    
                    

                    
                    if ($startCounting == 2) {
                       // $next = $im['iID'];         
                        $startCounting = 0;
                    }
                    if ($startCounting == 1) {
                        $startCounting = 2;
                    }
                    
                    if ($im['iID'] == $p) {
                        $class = 'class="activeslide"';
                    } else {
                        $class = '';
                    }
                    


                    $prevID = $im['iID'];                  
                    
                    $this->imgNav[] = array(
                                            'img'   =>  $imageTool->resize(false),
                                            'class' =>  $class,
                                            'href'  =>  $this->url . 'photoview/?c=' . $c . '&amp;p=' . $im['iID'] . '&amp;g=' . $g
                                            );
                    unset($imageTool);
                }

                
                //figure out the dashboard link"
                $url = $this->url;
                $this->dash =  $url . 'splash/gallery/?c=' . $c . '&amp;g=' . $g;
                $this->prevImg = ($prev > 0)?$url  . 'photoview/?c=' . $c . '&amp;g=' . $g . '&amp;p=' . $prev:$url  . 'photoview/?c=' . $c . '&amp;g=' . $g . '&amp;p=' .$p;
                $this->nextImg = ($next > 0)?$url  . 'photoview/?c=' . $c . '&amp;g=' . $g . '&amp;p=' . $next:$url  . 'photoview/?c=' . $c . '&amp;g=' . $g . '&amp;p=' .$p;;
                $this->firstGallery = $url  . 'photoview/?c=' . $c . '&amp;g=' . $g . '&amp;p=' .$firstGallery;
                $this->lastGallery = $url  . 'photoview/?c=' . $c . '&amp;g=' . $g . '&amp;p=' .$lastGallery;
                
                $this->cartAddLink = $url . 'cart/addtocart/add';
                $this->cartDownloadLink = $url . 'cart/adddownload/add';
                

                
                if ($this->token == '') {
                    $this->token = substr($_COOKIE['tppproofing'],5,10);
                }
                
                $this->itm = $p;

                $this->imageName = $imageDetail['iName'];
                
                Loader::model('products/product');

                $options = productModel::getOptionsByGallery($g);
                
                $this->options = array();

                $this->li = array();
                
                if (!empty($options)) {
                    
                    $tid = -1;

                    foreach($options as $o => $parr) {
                        $pOptions = array();
                        if ($tid < 1) {
                            $tid = $parr['tid'];
                            $this->options[$parr['type']] = array();
                            $this->li[] = $parr['type'];
                        } else if ($tid !== $parr['tid']) {
                            $tid = $parr;
                            $this->options[$parr['type']] = array();
                            $this->li[] = $parr['type'];
                        }

                        $costs = explode('__', $parr['cost']);
                        $names = explode('__', $parr['name']);
                        
                        foreach ($costs as $i => $v) {
                            $pOptions[$names[$i]] = $v;
                        }

                        $this->options[$parr['type']][$parr['product']] = $pOptions;
                    }

                }
                Loader::model('user/status');
                if (statusModel::isLoggedIn()) {
                    $this->loggedIn = true;   
                    $this->wishUrl = $this->url . 'users/wishlists/create/?referrerUrl=' . urlencode($this->url . 'photoview/?c=' . $c . '&g=' . $g . '&p=' . $p);
                    
                    //get the wish lists
                    
                    Loader::model('user/wish');

                    $wishLists = wishModel::getWishListsWithProducts($_COOKIE['tpproofing_login']);
                    $this->wishList = array();
                    $this->wishLists = array();
                    
                    if (!empty($wishLists)) {
                        
                        foreach ($wishLists as $list) {
                            $imageCount = $list['c'];
                            $imageArr = explode(',', $list['images']);
                            if (in_array($this->p, $imageArr)) {
                                $inList = true;   
                            } else {
                                $inList = false;   
                            }
                            
                            $this->wishLists[] = array(
                                                       'name'   =>  $list['name'],
                                                       'id'     =>  $list['id'],
                                                       'inList' =>  $inList,
                                                       'count'  =>  $imageCount,
                                                       'view'   =>  $this->url . 'users/wishlists/view/?w=' . $list['id'],
                                                       'delete' =>  $this->url . 'users/wishlists/delete/?w=' . $list['id']
                                                       );
                            

                            
                        }
                        
                    }
                    

                    
                    $this->wishAction = $this->url . 'photoview/wishadd/';
                } else {
                    $this->wishAction = '';
                    $this->loggedIn = false;
                }
            } else {
                $this->redirect();
            }

        
        }

        
        
        
        
    }