<?php
    
    class orders extends controller {

        
        
        public function index() {
            
            //figure out if the user is logged in, otherwise show them the login page!

            Loader::model('user/loginauth');
            
            
            if (!loginauthModel::isLoggedIn()) {
                
                
                $this->redirectAdm('login/login');
            }

            
            Loader::model('cart/orders');
            

           

            
            $orders = ordersModel::getOrdersForSpecifiedPeriod();
            

            $this->orderArr = $orders;
            
            $this->action = $this->admUrl . 'adm/orders/filter/';
            
        }
    
        
        
        
        public function filter() {
         
            $data = $this->request('post');
            
            if (isset($data['filter'])) {

                switch ($data['filter']) {
                    default:
                        break;
                    case 2:
                        //year to date
                        $start = strtotime('01 January ' . date('Y', time()));
                        $finish = strtotime('31 December ' . date('Y', time()));
                        break;
                        
                }
                $this->action = $this->admUrl . 'adm/orders/filter/';
                Loader::model('cart/orders');
                $orders = ordersModel::getOrdersForSpecifiedPeriod($start, $finish);
                $this->ordersArr = $orders;

                
                
            } else {
                $this->redirectAdm('adm/orders/');
            }
            
        }
        
    }