<?php

    class ordersModel {

        public function getOrdersByTimeLimit($start = 0, $finish = 0) {
         
            $db = data::instantiate();
        
            
            if ($start == 0) {
                $start = time() - 30*24*60*60;
            }
            
            if ($finish == 0) {
                $finish = time();
            }
            
            $sql = "SELECT * FROM orders WHERE oDate < ? AND oDate > ?";
            
            
            $arr = $db->query($sql, array($finish, $start), 'ARRAY_A');
            $db->close();
            return $arr;
            
            
        }
        
        public function getOrderTotalsByMonthForCurrentYear($start = 0) {
            $db = data::instantiate();
            if ($start == 0) {
                $start = strtotime('01 January ' . date('Y', time()));
            }
            
            $finish = strtotime('31 December ' . date('Y', time()));
            
            $sql = "SELECT MONTH(FROM_UNIXTIME(oDate)) AS m, SUM(total) AS c FROM orders WHERE oDate < ? AND oDate > ? GROUP BY MONTH(FROM_UNIXTIME(oDATE)) ORDER BY oDate DESC";

            $arr = $db->query($sql, array($finish, $start), 'ARRAY_A');
            $db->close();
            return $arr;


        }
    }