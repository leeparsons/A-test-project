<?php

    class statusModel {

        public function isLoggedIn() {
            //check the cookie against the hash in the db
            if (isset($_COOKIE['tpproofing_login'])) {

                $hashStr = $_COOKIE['tpproofing_login'];
                
                $db = data::instantiate();
                $sql = "SELECT id FROM users WHERE hashStr = '?'";
            
                $arr = $db->query($sql, $hashStr, 'ARRAY_A');
                $db->close();

                if (!empty($arr[0])) {
                    $controller = new controller();;
                    setcookie('tpproofing_login', $hashStr, time() + 60*60*8, $controller->url);
                    return true;
                }
                
            } else {
                return false;
            }
        
        }
    
        
        public function checkedDetails($email = '', $password = '') {
            
            $db = data::instantiate();
            
            $sql = "SELECT id FROM users WHERE email = '?' AND password = '?'";
            
            $arr = $db->hashQuery($sql, array($email, $password), 'ARRAY_A', array(false, true), array(false, 'MD5'));
            $db->close();
            if (empty($arr)) {
                return false;
            }
            
            return $arr;
            
        }

        
        public function setLogin($id = -1) {
            if ((int)$id > 0) {
             
                //set the login cookie!
                
                
                if (function_exists(uniqid)) {
                    $randStr = uniqid;
                } else {
                    $randStr = '';
                }
                $str = 'abcdefghijklmnopqrstuvwxyz1234567890';
                
                for ($x = 0; $x < 25; $x++) {
                    
                    $randStr .= $str[rand(0, 35)];
                    
                }
                
                                
                $controller = new controller();;

                
                setcookie('tpproofing_login', $randStr, time() + 60*60*8, $controller->url);
                //add this to the db
                
                $db = data::instantiate();
                
                $sql = "UPDATE users SET hashstr = '?' WHERE id = '?'";

                
                $db->create($sql, array('?', '?'), array($randStr, (int)$id));
                $db->close();

            }
        }
        
        public function create($name = '', $password1 = '', $password2 = '', $reminderq = '', $remindera = '', $email = '') {
            
            if ($name !== '' && $password1 !== '' && $password2 !== '' && $reminderq !== '' && $remindera !== '' && $email !== '') {
             
                
                
                //insert the data but check the user name does not exist!
                
                $db = data::instantiate();
                
                $sql = "SELECT id FROM users WHERE email = '?'";
                
                $arr = $db->query($sql, $email, 'ARRAY_A');
                
                
                if (!empty($arr[0])) {
                    $db->close();

                    return false;   
                }
                $sql = "INSERT INTO users (name, password, normalpassword, reminder, reminderanswer, email) VALUES ('?', '?', '?', '?', '?', '?')";
                
                $id = $db->create($sql, array('?', '?', '?', '?', '?', '?', '?'), array($name, $password1, $password2, $reminderq, $remindera, $email), true);
                $db->close();

                return (int)$id;
            }
            
            
            return false;
            
        }
        
        public function getUserInfo($hash = '') {
            if (strlen($hash) > 0) {
                $db = data::instantiate();
                
                $sql = "SELECT * FROM users WHERE hashstr = '?'";

                $arr = $db->query($sql, (string)$hash, 'ARRAY_A');
                $db->close();

                return $arr[0];
                
            }
            
            return array();
        }
        
        public function doesEmailExist($email = '') {
        
            if ($email !== '') {

                $db = Data::instantiate();
                
                $sql = "SELECT hashStr, id FROM users WHERE email = '?'";
                
                $arr = $db->query($sql, $email, ARRAY_A);

                if (!empty($arr[0])) {
                    if ($arr[0]['hashStr'] == null || $arr[0]['hashStr'] == '') {
                        //generate hash   
                        
                        
                        if (function_exists(uniqid)) {
                            $randStr = uniqid;
                        } else {
                            $randStr = '';
                        }
                        $str = 'abcdefghijklmnopqrstuvwxyz1234567890';
                        
                        for ($x = 0; $x < 25; $x++) {
                            
                            $randStr .= $str[rand(0, 35)];
                            
                        }
                        $sql = "UPDATE users SET hashstr = '?' WHERE id = '?'";
                        
                        
                        $db->create($sql, array('?', '?'), array($randStr, (int)$arr[0]['id']));
                        
                        $db->close();
                        
                        return $randStr;
                        
                    } else {
                        
                        $db->close();
                        
                        return array('hash' =>  $arr[0]['hashStr'],
                                     'id'   =>  $arr[0]['id']
                                     );
                    }
                }
                $db->close();

                
            }
            return false;
            
        }
        
        
        public function getQuestionByID($id = -1) {
         
            if ((int)$id > 0) {
                
                $db = data::instantiate();
                
                $sql = "SELECT reminder FROM users WHERE id = ?";
                
                $arr = $db->query($sql, (int)$id, ARRAY_A);
                $db->close();
                if (!empty($arr[0])) {
                    return $arr[0]['reminder'];
                }
            }
            
            
            return '';
            
        }
        
        public function checkAnswerCorrect($answer = '', $hash = '') {
            
            if (strlen($hash) > 0) {
                $db = data::instantiate();
            
            
            
                $sql = "SELECT id FROM users WHERE hashStr = '?' AND reminderanswer = '?'";
                
                
                $arr = $db->query($sql, array($hash, $answer), ARRAY_A);
                $db->close();
                if (!empty($arr[0])) {
                    
                    return true;
                }
                
            }
            return false;
            
        }
        
    }