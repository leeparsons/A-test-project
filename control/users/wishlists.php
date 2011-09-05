<?php

    class wishlists extends controller {

        public $cError = array();
        
        public function index() {
           
            $this->isLogged();

            
            
            //get all the wish lists for this user!
            Loader::model('user/wish');

            $wishLists = wishModel::getWishLists($_COOKIE['tpproofing_login']);
            
            $this->create = $this->url . 'users/wishlists/create/';
            
            if (!empty($wishLists[0])) {
                
                $this->wishListsArr = array();
                
                foreach ($wishLists as $list) {
                    
                    $share = $this->url . 'users/wishlists/share/?w=' . $list['id'];
                    $delete = $this->url . 'users/wishlists/delete/?w=' . $list['id'];
                    $view = $this->url . 'users/wishlists/view/?w=' . $list['id'];                    

                    $this->wishListsArr[] = array(
                                               'share'      =>  $share,
                                               'delete'     =>  $delete,
                                               'view'       =>  $view,
                                               'name'       =>  $list['name'],
                                               'items'      =>  $list['c']
                                               );
                    
                    
                }

            } else {
                $this->wishListsArr = array();   
            }


        }
        
        
        public function create() {
            $this->isLogged();
            
            $this->template = 'create';
            
            $this->action = $this->url . 'users/wishlists/savenew/';

            $data = $this->request('request');
            
            if (isset($data['referrerUrl'])) {
                
                $this->returnUrl = $data['referrerUrl'];
                
            } else {
                $this->returnUrl = $this->url . 'users/wishlists/';   
            }
            
        }
        
        public function savenew() {
         
            $this->isLogged();
            
            $data = $this->request('post');
            
            if (isset($data['name']) && strlen($data['name']) > 0) {
                
                //see if the name is unique
                
                Loader::model('user/wish');
                if (wishModel::isNameUnique($_COOKIE['tpproofing_login'], $data['name'])) {
                    //name exists!
                    $this->cError['name'] = true;
                    $this->create();
                } else {
                    //name does not exist for this users wishlists so create it!
                    Loader::model('user/status');

                    $uInfo = statusModel::getUserInfo($_COOKIE['tpproofing_login']);
                    $uid = $uInfo['id'];
                    if ((int)$uid > 0) {
                        wishModel::createWishList($_COOKIE['tpproofing_login'], $data['name'], $uid);
                        if (isset($data['referrer']) && strlen($data['referrer'] > 0)) {
                            $this->redirect($data['referrer']);
                        }
                        $this->redirect('users/wishlists/');
                    } else {
                        
                        
                        $this->cError['name'] = true;
                        $this->create();
                    }                        
                }
                
                
                
            } else {

                $this->cError['name'] = true;             
                $this->create();
            }
            
            
        }
        
        public function guestview() {
            $data = $this->request('get');
            $this->wishList = array();   

            if (isset($data['w']) && (int)$data['w'] > 0) {

                Loader::model('user/wish');
                $wishList = wishModel::getWishListFromWID($data['w']);
                
                if (!empty($wishList)) {
                    
                    $this->title = ucwords($wishList[0]['name']);
                    
                    $this->wishList = array();
                    
                    foreach ($wishList as $list) {
                        
                        $delete = $this->url . 'users/wishlists/removeitem/?w=' . $data['w'] . '&amp;p=' . $list['iID'];
                        $view = $this->url . 'photoview/?p=' . $list['iID'] . '&amp;g=' . $list['gid'] . '&amp;c=' . $list['cid'];                    
                        
                        $imageTool = new image($list['iName'], $list['cName'], $list['gName'], 'cart', 'cart');
                        $image = $imageTool->resize(true);
                        $imageName = $imageTool->getImageName();
                        if ($image == '') {
                            $image = $imageTool->getNone('cart');   
                        }
                        
                        unset($imageTool);
                        
                        $this->wishList[] = array(
                                                  'view'        =>  $view,
                                                  'delete'      =>  $delete,
                                                  'name'        =>  ucwords($imageName),
                                                  'image'       =>  $image,
                                                  'gallery'     =>  ucwords($list['gName']),
                                                  'client'      =>  ucwords($list['cName'])
                                                  );
                        unset($image);
                        
                    }
                    
                    
                }
                
                
            }            
            $this->template = 'wishguestview';
        }
        
        public function view() {
            $data = $this->request('get');

            if (isset($data['w']) && (int)$data['w'] > 0) {
                $this->isLogged();
                
                
                
                //get all the wish lists for this user!
                Loader::model('user/wish');
                $wishList = wishModel::getWishList($_COOKIE['tpproofing_login'], $data['w']);

                if (!empty($wishList)) {
                    
                    $this->title = ucwords($wishList[0]['name']);
                    
                    $this->wishList = array();
                    
                    foreach ($wishList as $list) {
                        
                        $delete = $this->url . 'users/wishlists/removeitem/?w=' . $data['w'] . '&amp;p=' . $list['iID'];
                        $view = $this->url . 'photoview/?p=' . $list['iID'] . '&amp;g=' . $list['gid'] . '&amp;c=' . $list['cid'];                    
                        
                        $imageTool = new image($list['iName'], $list['cName'], $list['gName'], 'cart', 'cart');
                        $image = $imageTool->resize(true);
                        $imageName = $imageTool->getImageName();
                        if ($image == '') {
                            $image = $imageTool->getNone('cart');   
                        }

                        unset($imageTool);
                        
                        $this->wishList[] = array(
                                                  'view'        =>  $view,
                                                  'delete'      =>  $delete,
                                                  'name'        =>  ucwords($imageName),
                                                  'image'       =>  $image,
                                                  'gallery'     =>  ucwords($list['gName']),
                                                  'client'      =>  ucwords($list['cName'])
                                                   );
                        unset($image);
                        
                    }

                } else {
                    $this->wishList = array(); 
                    //no items so get the wish list information:

                    $wishInfo = wishModel::getSimpleInformation((int)$data['w']);


                    $this->title = ucwords($wishInfo['name']);
                    
                }
                $this->wishListsLink = $this->url . 'users/wishlists/';
                $this->template = 'wishview';
                
                
            } else {
                $this->redirect();   
            }
            

            
            
        }
        
        
        public function removeitem() {
            $this->isLogged();
            
            $data = $this->request('get');
            
            if (isset($data['w']) && isset($data['p']) && (int)$data['w'] > 0 && (int)$data['p'] > 0) {

                $w = (int)$data['w'];
                $p = (int)$data['p'];
                
                //remove the item
                
                Loader::model('user/wish');
                
                wishModel::removeItem($w, $p);
                
                $this->redirect('users/wishlists/view/?w=' . $w);
                
            } else {
                $this->redirect();    
            }
            
            
        }
        
        public function share() {
            $this->isLogged();
            
            //get the wid
            
            $data = $this->request('get');
            
            
            if (isset($data['w']) && (int)$data['w'] > 0) {
             
                //get the information on this wishlist
                
                Loader::model('user/wish');
                
                $this->populateFieldsForSending($data);
                
            } else {
                $this->redirect();   
            }
            
            
        }
        
        public function send() {
            $this->isLogged();
            $data = $this->request('post');
            
            if (isset($data['name']) && isset($data['email']) && isset($data['w']) && (int)$data['w'] > 0) {

                
                //validate the email address:

                $validEmails = false;
                $extras = '';
                $to = '';
                if (isset($data['stpreowner']) && $data['storeowner'] == 'on') {
                    
                    
                    
                }

                
                $emailsArr = explode(',', $data['email']);
                if (!empty($emailsArr)) {

                    foreach ($emailsArr as $k => $email) {
                        
                        if ($this->validateEmail(trim($email))) {
                            
                            if ($validEmails === false) {
                                $to = trim($email);
                            } else {
                                $extras .= ($extras == '')?trim($email):', ' . trim($email); 
                            }
                            $validEmails = true;
                            
                        }
                    }
                }
                
                if (isset($data['storeowner']) && $data['storeowner'] == 'on') {
                    $validEmails = true;
                    if ($to == '') {
                        $to = $this->systemEmail;
                        
                    } else {
                        $extras .= ($extras == '') ? $this->systemEmail : ', ' . $this->systemEmail; 
                    }
                
                }
                if ($validEmails === true) {



                    //get the user information
                    Loader::model('user/status');
                    
                    
                    
                    $user = statusModel::getUserInfo($_COOKIE['tpproofing_login']);

                    
                    if (!empty($user)) {

                        
                        $message = '';
                        $subject = '';
                        
                        
                        if ($data['name'] !== '') {

                            $message = $data['name'] . ' ';
                            $subject = $message;                            
                        }
                        
                        //get the user name:
                        
                        $uName = $user['name'];
                        
                        $uEmail = $user['email'];

                        if ($this->validateEmail($uEmail)) {
                            $from = $uEmail;
                        } else {
                            $from = $this->systemEmail;
                        }

                        if ($uName !== '') {
                            $urls = new url();
                            $subject .= $uName . ' wants to share their wish list with you.';
                            $message .= $uName . ' wants to share their wish list with you.' . "\n\n" . 'You can view the wish list at this link: ' . $urls->getFullBaseUri() . $this->url . 'users/wishlists/guestview/?w=' . $data['w'];   
                        }
                        
                        if (strlen($data['notes']) > 0) {
                         
                            $message .= "\n\n" . stripslashes($data['notes']);
                            
                        }
                        $headers = 'From: ' . $this->storeName . ' <' . $this->systemEmail . '>';
                        if ($extras !== '') {
                            $headers .= "\r\n" . 'cc: ' . $extras;
                        }
                        
                        mail($to, $subject, wordwrap($message, 70), $headers);
                        
                    }
                    
                    $this->redirect('users/wishlists/');
                    
                    
                } else {                    
                    //emails failed the validation!
                    $this->cError['email'] = 'Please enter a valid email address.';
                    $this->template = 'wishshare';
                    $this->populateFieldsForSending($data);
                }

            } else {
                //nothing was set so presume hack!
                $this->redirect('users/wishlists/');
            }

            
        }
        
        
        public function delete() {
            $this->isLogged();
            
            $data = $this->request('get');
            
            if (isset($data['w']) && (int)$data['w'] > 0) {
                
                Loader::model('user/wish');
                
                wishModel::deleteWishList((int)$data['w']);
                
            }
            $this->redirect('users/wishlists/');
            
        }
        
        private function isLogged() {
            Loader::model('user/status');
            if (!statusModel::isLoggedIn()) {
                $this->redirect('users/login/?redirectUrl=' . $_SERVER['REQUEST_URI'] . $_SERVER['QUERYSTRING']);
            }

        }
        
        protected function validateEmail($email) {
            // First, we check that there's one @ symbol, 
            // and that the lengths are right.
            

            
            if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
                // Email invalid because wrong number of characters 
                // in one section or wrong number of @ symbols.
                return false;
            }
            // Split it into sections to make life easier
            $email_array = explode("@", $email);
            $local_array = explode(".", $email_array[0]);
            for ($i = 0; $i < sizeof($local_array); $i++) {
                if
                    (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&
                           ↪'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$",
                           $local_array[$i])) {
                        return false;
                    }
            }
            // Check if domain is IP. If not, 
            // it should be valid domain name
            if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
                $domain_array = explode(".", $email_array[1]);
                if (sizeof($domain_array) < 2) {
                    return false; // Not enough parts to domain
                }
                for ($i = 0; $i < sizeof($domain_array); $i++) {
                    if
                        (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|
                               ↪([A-Za-z0-9]+))$",
                               $domain_array[$i])) {
                            return false;
                        }
                }
            }
            return true;
        }
        
        protected function populateFieldsForSending($data) {
            Loader::model('user/wish');
            $infoArr = wishModel::getSimpleInformation((int)$data['w']);

            $this->name = $infoArr['name'];
            
            $this->w = (int)$data['w'];
            
            $this->template = 'wishshare';
            
            $this->wishListsLink = $this->url . 'users/wishlists/';
            
            $this->title = ucwords($infoArr['name']);
            
            $this->action = $this->url . 'users/wishlists/send/';   
            
            $this->recipient = (isset($data['name'])) ? $data['name'] : '';
            
            $this->notes = (isset($data['notes'])) ? $data['notes'] : '';
            
            $this->emails = (isset($data['email'])) ? $data['email'] : '';
        }
        
    }