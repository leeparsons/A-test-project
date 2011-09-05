<?php
    
    class dashboard extends controller {

        
        public function index() {
            
            Loader::model('user/loginauth');
            
            
            if (!loginauthModel::isLoggedIn()) {
            
           
                $this->redirectAdm('login/login');
            }
            
            $this->js[] = $this->admUrl . 'js/jquery.jqplot.min.js';
            $this->js[] = $this->admUrl . 'js/jquery.jqplot.highlighter.min.js';
            $this->js[] = $this->admUrl . 'js/jquery.jqplot.cursor.min.js';
            
            $this->js[] = $this->admUrl . 'js/jqplot.dateAxisRenderer.min.js';
            
            //get the number of orders this month:

            $timeStr = strtotime('01 January' . date('Y', time()));

            Loader::model('cart/orders');

            $orderArr = ordersModel::getOrdersByTimeLimit($timeStr, time());

            $this->countOrdersYTD = count($orderArr);

            $ordersByMonth = array();
            
            $ordersByMonth = ordersModel::getOrderTotalsByMonthForCurrentYear($timeStr);
            
            
            

            for ($x=0; $x<12; $x++) {
                $monthlyOrders[$x] = (int)0;    
            }

            
            
            if (!empty($ordersByMonth)) {
            
                foreach ($ordersByMonth as $order) {

                    $monthlyOrders[$order['m']-1] = (int)$order['c'];
                    
                }
                
            }
            
            $year = date('Y', time());
            
            $finalMonthlyOrders = array();
            
            foreach ($monthlyOrders as $m => $order) {
                $finalMonthlyOrders[$year . '-' . ($m+1) . '-01'] = $order;
            }
            
            
            $this->ordersByMonth = $finalMonthlyOrders;
            
            
            $this->monthStr = date('M, Y', time());
            

            
            
        }
        
    
    }