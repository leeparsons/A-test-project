<?php

    class ordersModel {

    

        public function createOrder($total = 0, $vat = 0, $detail = '', $user = 0, $sessID = '') {

            $db = data::instantiate();
            
            $sql = "INSERT INTO orders (total, vat, detail, frontUserID, sessID, oDate) VALUES ('?', '?', '?', '?', '?', '?')";
            
            $id = $db->create($sql, array('?', '?', '?', '?', '?', '?'), array(number_format((float)$total, 2), number_format((float)$vat, 2), $detail, $user, $sessID, time()), true);
            $db->close();
            return $id;
            
            
        }
    
        
        public function checkSession($sessID = '') {
            
            
            
            $db = data::instantiate();
            
            
            $arr = $db->query("SELECT * FROM orders WHERE sessID = '?'", (string)$sessID, 'ARRAY_A');
            $db->close();
            if (empty($arr)) {
            
                return false;
                
            }

            return true;
            
            
        }
        
        public function getDetail($id = -1) {

            if ((int)$id > 0) {
                $db = data::instantiate();
                
                $sql = "SELECT detail FROM orders WHERE id = ?";
                
                $arr = $db->query($sql, (int)$id, 'ARRAY_A');
                $db->close();
                if (!empty($arr[0])) {
                    return $arr[0]['detail'];   
                }
                
                
            }
            return '';
            
        }
        
        public function updateOrder($id = -1, $fields, $data) {
            if ((int)$id > 0 && (!empty($fields) && !empty($data)) && count($data) == count($fields)) {
             
                $db = data::instantiate();
                
                
                $sql = "UPDATE orders SET ";
                $question = array();
                
                foreach ($fields as $k => $field) {
                    if ($field !== '') {
                        $sql .= " " . $field . " = '?',";
                        $question[] = '?';
                    }
                }
                
                $sql .= " oDate = '" . time() . "'";
                $question[] = '?';
                $data[] = $id;
                

                $sql .= " WHERE id = ?";
                $db->create($sql, $question, $data);
                
                $db->close();
                
            }
            
        }
        

    }