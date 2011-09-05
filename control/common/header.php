<?php

    class header extends controller {

        public function index() {
            $this->siteDescription = '';
            
            $this->js[] = $this->url . 'js/jquery.js';
            $this->js[] = $this->url . 'js/scroll.js';
            $this->js[] = $this->url . 'js/mouse.js';
            $this->css[] = $this->url . 'css/scroll.css';
            $this->css[] = $this->url . 'css/cart.css?v=1';
            $this->js[] = '/js/font.js';
            
            
            Loader::model('user/status');
            
            if (statusModel::isLoggedIn()) {
                $this->logout = $this->url . 'users/logout/?redirect=' . urlencode(str_replace($this->url, '/', $_SERVER['REQUEST_URI']));
                $this->logged = true;
            } else {
                $this->logged = false;   
                $this->login = $this->url . 'users/login/?redirect=' . urlencode(str_replace($this->url, '/', $_SERVER['REQUEST_URI']));
            }
            
            Loader::system('cart');
            $this->cartUrl = $this->url . 'cart/viewcart/';
            $this->cartItems = count(cart::getCart());
            
            $this->wishListsCountForMenu = (int)0;
            $this->wishListsUrlForMenu = '';
            
            $this->wishListsUrlForMenu = $this->url . 'users/wishlists/';
            
            Loader::model('user/status');
            if (statusModel::isLoggedIn()) {
                Loader::model('user/wish');
                $this->wishListsCountForMenu = wishModel::countWishlists($_COOKIE['tpproofing_login']);
            }

            //determine if any oneoff products have been set:
            Loader::model('products/product');
            
            $oneOffs = productModel::getOneOffs();

            if (!empty($oneOffs[0])) {
                $oneOffArr = array();
                foreach ($oneOffs as $k => $oneOff) {
                    $oneOffArr[] = array('url'  =>  $this->url . 'products/standalone/?p=' . $oneOff['urlID'],
                                         'text' =>  $oneOff['name']
                                         );
                }
                $this->oneOffUrls = $oneOffArr;
            } else {
                $this->oneOffUrls = array();   
            }
        
        }

        
        
    }