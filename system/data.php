<?php

    
    class data {
    
        protected $link;
        protected $dbu;
        protected $dbp;
        protected $host;
        protected $dbn;
        
        public function index($env = '') {
            
            if (!is_object($env)) {
                $env = new Environment();   
            }
            
            include $env->configDir() . 'config.php';
            $this->dbu = $dbu;
            $this->dbp = $dbp;
            $this->host = $host;
            $this->dbn = $dbn;
            $this->linkUp();
        }
        
        
        public static function instantiate() {

            $db = new data();
            $db->index();
            return $db;
        }
        
        public function linkUp() {
            if (!$this->link) {
                $this->link = mysql_connect($this->host,$this->dbu,$this->dbp) or die('Unable to access the database. Please make sure you installation has run through the setup process!');            
                $this->db = mysql_select_db($this->dbn) or die('Unable to find the ' . $this->dbn . ' database. Please contact parsolee@gmail.com with erro code: dbf-1101');
            } else {
                mysql_close($this->link);   
                $this->link = mysql_connect($this->host,$this->dbu,$this->dbp) or die('Unable to access the database. Please make sure you installation has run through the setup process');            
                $this->db = mysql_select_db($this->dbn) or die('Unable to find the ' . $this->dbn . ' database. Please contact parsolee@gmail.com with erro code: dbf-1101');

            }
        }
        
        public function query($sql = '') {
            if ($sql == '') {
                return false;
            }
            
            $extras = func_get_args();

            if (count($extras) == 2) {
                switch ($extras[1]) {
                    case 'ARRAY_A':
                    case 'NUM':
                    case 'BOTH';
                        $arrayType = $extras[1];
                        $args = '';
                        break;
                    default:
                        $args = '';
                        $arrayType = 'ARRAY_A';
                        break;
                }
            } elseif (count($extras) == 1) {
                $args = $extras[0];
                $arrayType = '';
            } elseif (count($extras) == 3) {
                switch ($extras[2]) {
                    case 'ARRAY_A':
                    case 'NUM':
                    case 'BOTH';
                        $arrayType = $extras[2];
                        $args = $extras[1];
                        break;
                    default:
                        $args = $extras[2];
                        $arrayType = 'ARRAY_A';
                        break;
                }
     
            } else {
                $args = '';
                $arrayType = 'ARRAY_A';
            }
                      


            
            $replace = '';
            if (!empty($args)) {

             
                if (is_array($args)) {
                    for ($x = 0; $x < count($args); $x++) {
                        $replace[] = '?';
                    }
                } elseif (strlen($args) > 0) {
                    $replace = '?';
                }
            }
            $result = $this->Exec($sql,$replace,$args);

            
            if (!$result) {

                return false;
            } elseif ($result === true) {
                return true;   
            }

            $returnarr = array();
            switch ($arrayType) {
                case 'ARRAY_A':
                    $arrayType = MYSQL_ASSOC;
                    break;
                case 'NUM':
                    $arrayType = MYSQL_NUM;
                    break;
                default:
                    $arrayType = MYSQL_BOTH;
                    break;
            }
            
            $i = (int)0;

            while ($row = mysql_fetch_array($result,$arrayType)) {
                foreach ($row as $k => $v) {
                    $returnarr[$i][$k] = $v;
                }
                $i++;
            }
            mysql_free_result($result);
            

            return $returnarr;
            
            
        }
        
        public function hashQuery($sql = '',$replaces = '',$arrayType = 'ARRAY_A',$replaceToHash = '',$hashTypes = '') {

            if ($sql == '') {
                return array();   
            }
            
            switch ($arrayType) {
                case 'ARRAY_A':
                    $arrayType = MYSQL_ASSOC;
                    break;
                case 'NUM':
                    $arrayType = MYSQL_NUM;
                    break;
                default:
                    $arrayType = MYSQL_BOTH;
                    break;
            }

            $replace = array();

            if (is_array($replaces) && !empty($replaces)) {
                for ($x = 0; $x < count($replaces); $x++) {
                    $replace[] = '?';
                }
            } elseif ($replaces !== '' && strlen($replace) > 0) {
                $replace[] = '?';
            } else {
                $replace[] = '';   
            }
            

            $hashes = array();

            
            if (is_array($replaceToHash) && !empty($replaceToHash)) {
                foreach ($replaceToHash as $k => $v) {
                    if ($v === true) {
                        $hashes[] = true;
                    } else {
                        $hashes[] = false;   
                    }
                }
            } elseif ($replaceToHash !== '') {
                if ($replaceToHash === true) {
                    $hashes[] = true;   
                } else {
                    $hashes[] = false;   
                }
            } else {
                $hashes[] = false;   
            }
            
            if (!is_array($hashTypes)) {
                $hashTypesArr[] = $hashTypes;   
            } else {
                $hashTypesArr = $hashTypes;   
            }
            
            
            if (!is_array($replaceToHash)) {
                $replaceToHashArr[] = $replaceToHash;   
            } else {
                $replaceToHashArr = $replaceToHash;   
            }
            
            
            $result = $this->ExecHashes($sql, $replace, $replaces, $replaceToHashArr, $hashTypesArr);
            
            if (!$result) {
                return false;
            } elseif ($result === true) {
                return true;   
            }
            
            $returnarr = array();
                        
            $i = (int)0;
            
            while ($row = mysql_fetch_array($result, $arrayType)) {
                foreach ($row as $k => $v) {
                    $returnarr[$i][$k] = $v;
                }
                $i++;
            }
            
            mysql_free_result($result);
            
            return $returnarr;
            
            
        }
        
        
        private function ExecHashes($sql = '',$thingsToReplace = array(),$replaceMentString = array(),$replaceToHash = array(),$hashTypes = array()) {
            if ($sql !== '') {
                
                if (is_array($thingsToReplace) && !empty($thingsToReplace)) {

                    if (count($thingsToReplace) == count($replaceMentString)) {
                        
                        //figure out if the count of hashes are the same:
                        
                        if (count($replaceToHash) == count($hashTypes) && count($replaceToHash) == count($replaceMentString)) {
                            
                            //do the query. other wise throw exception
                            
                            //replace the strings
                            
                            foreach ($replaceMentString as $k => $r) {
                             
                                $replaceMentString[$k] = mysql_real_escape_string($r,$this->link); 
                                
                            }
                            
                            
                            //now figure out the hashes:
                            
                            foreach ($replaceToHash as $k => $v) {

                                
                                if ($v === true) {

                                    $replaceMentString[$k] = call_user_func((string)$hashTypes[$k],$replaceMentString[$k]);
                                    
                                }
                                
                                $sql = $this->replaceOne($replaceMentString[$k],$thingsToReplace[$k],$sql);
                                
                            }

                            return mysql_query($sql);
                            
                        }
                        
                    }
                }
                
                
            }
            
            
            
            Throw new Exception('There was an error processing your request. Please contact parsolee@gmail.com with code: E_1010 hashes');
            
            
        }
        
        private function Exec($sql = '',$replace = array(),$args = array()) {          
            
            if ($sql !== '') {
                if (is_array($replace) && is_array($args)) {
                    $newArgs = array();

                    if (count($args) == count($replace) && count($args) > 0) {
                        foreach ($args as $str) {
                            $newArgs[] = mysql_real_escape_string($str, $this->link);
                        }
                        
                    } else {
                        $replace = array();
                    }
                } elseif (!is_array($replace) && !is_array($args)) {
                    if ($replace !== '' && $args !== '') {
                        $newArgs = mysql_real_escape_string($args,$this->link);
                    } else {
                        $newArgs = '';   
                    }
                } else {
                    $newArgs = array();
                    $replace = array();
                }

                if (is_array($newArgs) && !empty($newArgs)) {
                    foreach ($newArgs as $k => $arg) {
                        $sql = $this->replaceOne($arg,$replace[$k],$sql);

                    }
                } elseif ($newArgs !== '' && $replace !== '') {
                    $sql = str_replace($replace,$newArgs,$sql);
                }
                
                $result = mysql_query($sql, $this->link);
                return $result;
                
            }
            
        }
        
        private function replaceOne($replacement,$look,$string) {

            
            if (strpos($string, $look) !== false){
                $occurrence = strpos($string, $look);
                return substr_replace($string, $replacement, strpos($string, $look), strlen($look));
            }
                

            return $string;
        }
        
        public function close() {
            if (!$this->link) {
                
            } else {
                mysql_close($this->link);
            }
        }
        
        public function create($sql = '',$replace = array(),$args = array(),$return = false) {

            if ($sql == '') {
                return false;
            }
            
            $result = $this->Exec($sql,$replace,$args);
            
            if (!$result) {
                return false;
            }
            
            if ($return === true) {
                return mysql_insert_id($this->link);
            }
            
            return true;
            
            
            
            
        }

        
        public function delete($sql = '',$replace = array(),$args = array()) {
            
            if ($sql == '') {
                return false;
            }
            
            $result = $this->Exec($sql,$replace,$args);
            
        }
        
        
    }

