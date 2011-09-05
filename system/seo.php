<?php

    class seo {
    
        protected $uri = '';
        protected $fullUrl = '';
        
        public function __construct($controller, $urls) {
            //only constructed when SEO is on!
            if ($controller->seoUrls == 1) {

                //get the url            
                $baseUri = $urls->getBaseUri();

                if ($baseUri !== '/' && $baseUri !== '' && $baseUri !== $controller->url) {

                    if (substr($baseUri, 0, strlen($controller->url)) == $controller->url) {
                        $baseUri = substr($baseUri, strlen($controller->url));  
                    }

                    //see if the baseUri exists in the db
                    Loader::model('seo/seo');

                    $paramsArr = seoModel::getParams($baseUri);
                    
                    if (!empty($paramsArr[0])) {

                        
                        $params = unserialize((string)$paramsArr[0]['params']);
                        
                        if (is_array($params)) {
                            $paramStr = '';
                            foreach ($params as $k => $param) {
                                $paramStr .= ($paramStr == '') ? $k . '=' . $param : '&' . $k . '=' . $param;
                            }
                            unset($params);
                            $params = $paramStr;
                        }
                        
                        $this->uri = $paramsArr[0]['path'];
                        

                        
                        
                        //$controller->setVar('url', $paramsArr[0]['originalUrl']);

                        $this->fullUrl = $controller->url . $this->uri . '?' . $params;
                        
                        $urls->reindex($this->fullUrl);

                        foreach (explode('&', $params) as $param) {
                            $bits = explode('=', $param);
                            $_GET[$bits[0]] = $bits[1];
                        }

                        return true;
                    }
                }
                
                //populate all the variables needed for interpreting the site!
                
            }
            
            return false;
        }
        
        
        public function getUrl() {
            return $this->fullUrl;   
        }
        
        public function uri() {
            return $this->uri;   
        }
        
    }