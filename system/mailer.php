<?php

    class Mailer {
    
        
        private $to;
        private $from;
        private $headers = array();
        private $message;
        private $subject;
        
        public function setTo($to = '') {
         
            $this->to = stripslashes($to);
            
        }
    
        
        public function setFrom($from = '') {
            $this->from = stripslashes($from);
        }
        
        
        public function setHeaders($key, $val) {

            $this->headers[$key] = stripslashes($val);
            
        }
        
        
        public function setSubject($subject = '') {
            $this->subject = stripslashes($subject);
        }
        
        public function setMessage($message = '') {
            $this->message = stripslashes($message);
        }
        
        public function send() {
            if (!$this->validateEmail($this->to)) {
                return false;   
            }
            if ($this->message !== '' && $this->subject !== '') {
                $controller = new Controller();
                
                if (!$this->validateEmail($this->from)) {
                    
                    $this->from =  $controller->getVar('systemEmail');
                    
                }
                
                if (!isset($this->headers['From'])) {
                    if (isset($this->from) && $this->from !== '') {
                        $this->headers['From'] = $this->from;
                    } else {
                        $this->headers['From'] = $controller->getVar('storeName');
                    }
                }
                
                $headers = '';
                foreach ($this->headers as $key => $header) {
                    $headers .= ($headers == '') ? $key . ': ' . $header : "\r\n" . $key . ': ' . $header; 
                }
                
                Loader::model('logs/logs');

                $log = 'Sending emails: ';
                $log .= "\n";
                $log .= $headers;
                $log .= "\n";
                $log .= 'To: ' . $this->to;
                $log .= "\n";
                $log .= 'Subject: ' . $this->subject;
                $log .= "\n";
                $log .= 'Sending reminder password via email';
                
                
                logsModel::insertLog($log);
                
                mail($this->to, $this->subject, wordwrap($this->message, 70), $headers);
                
                
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
        
    }

