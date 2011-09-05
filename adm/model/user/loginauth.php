<?php

    class loginauthModel {

        public function isUser($u = '',$p = '') {
            $db = data::instantiate();

            $results = $db->hashQuery("SELECT id FROM admusers WHERE uname = '?' AND pword = '?' LIMIT 1",array($u,$p),'ARRAY_A',array(false,true),array(false,'MD5'));
            $db->close();

            if (!empty($results)) {
            
                return $results[0];
            }
            
            return false;
            
        }
        
        public function hello($uid = -1) {

            if ($uid > 0) {


                unset($_SESSION['loggedIn']);
                
                
                $randHash1 = '';
                $randHash = '';
                $strArr = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                $strArr2 = strrev('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz');
                for($k=0;$k<30;$k++) {
                    
                    if ($k % 2) {
                        $randHash .= $strArr[rand(0,47)];
                    } else {
                        $randHash .= $strArr2[rand(0,47)];
                    }
                }
                
                for($k=0;$k<30;$k++) {
                    
                    if ($k % 2) {
                        $randHash1 .= $strArr[rand(0,47)];
                    } else {
                        $randHash1 .= $strArr2[rand(0,47)];
                    }
                }
                
                $_SESSION['loggedIn']['hs2'] = $randHash1;
                
                $_SESSION['loggedIn']['i'] = $randHash . $uid . $randHash1;
                
                $db = data::instantiate();
                $db->create("UPDATE admusers SET loggedIn = 1, expirytime = '?', identifier1 = '?', identifier2 = '?' WHERE id = ?", array('?','?','?','?'), array((time() + 60*60),$randHash,$randHash1,$uid));
                //now we told the db that the person is logged in, we need to update the session:
                $db->close();

                $_SESSION['loggedIn']['hs1'] = $randHash;
                
                $_SESSION['loggedIn']['hs2'] = $randHash1;
                
                $_SESSION['loggedIn']['i'] = $randHash . $uid . $randHash1;                

                

            
            } else {
                return false;   
            }
            
        }
        
        public function isLoggedIn() {
            $db = data::instantiate();

                //see if the session is set - if it is it contains the user id!

                //match against the db:
                if (!empty($_SESSION['loggedIn']) && count($_SESSION['loggedIn']) == 3) {

                    if (!empty($_SESSION['loggedIn']['i']) && !empty($_SESSION['loggedIn']['hs1']) && !empty($_SESSION['loggedIn']['hs2'])) {
                        //check against the db to make sure that the session is not expired!

                        
                        $arr = $db->query("SELECT id FROM admusers WHERE identifier1 = '?' AND identifier2 = '?' AND id = '?' AND expirytime > ?", array($_SESSION['loggedIn']['hs1'], $_SESSION['loggedIn']['hs2'], str_replace(array($_SESSION['loggedIn']['hs1'], $_SESSION['loggedIn']['hs2']), array('', ''), $_SESSION['loggedIn']['i']), time()), 'ARRAY_A');

                        if (is_array($arr) && !empty($arr)) {
                            $db->create("UPDATE admusers SET loggedIn = 1, expirytime = ?, identifier1 = '?', identifier2 = '?' WHERE id = ?", array('?', '?', '?', '?'), array((time() + 60*60), $_SESSION['loggedIn']['hs1'], $_SESSION['loggedIn']['hs2'], $arr[0]['id']));
                            $db->close();
                            return true;            
                        } else {
                            
                            
                            //the db does not match so the session has possibly expired - need to check that is is the case - because if it is, then there will be something in the db with the current session:

                            //check if the session had been entered but the expirytimetime has passed:
                            
                            
                            $arr = $db->query("SELECT id FROM admusers WHERE identifier1 = '?' AND identifier2 ='?' AND expirytime < '?'", array($_SESSION['loggedIn']['hs1'],$_SESSION['loggedIn']['hs2'],$_SESSION['loggedIn']['i'],time()),'ARRAY_A');

                            if (empty($arr)) {
                                //then the session has never existed so unset it 
                                
                                unset($_SESSION['loggedIn']);
                                
                            } else {
                                //the session has once existed but has run out so reset the expirytimetime in the db:
                                $db->create("UPDATE admusers SET loggedIn = 1, expirytime = ?, identifier1 = '?', identifier2 = '?' WHERE id = ?", array('?','?','?','?'), array((time() + 60*60),$_SESSION['loggedIn']['i'],$_SESSION['loggedIn']['hs1'],$_SESSION['loggedIn']['hs2']));
                                $db->close();

                                return true;
                            }
                            

                        }
                        
                        
                        
                    }
                    
                    
                } else {

                    //there was a problem with the session so unset it!
                    unset($_SESSION['loggedIn']);

                }
                
            $db->close();

            return false;
        }
    
        public function logout() {
            unset($_SESSION['loggedIn']);
        }

    }