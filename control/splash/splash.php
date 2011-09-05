<?php

    class splash extends controller {

        public function index() {

            Loader::model('splash/splash');
            
            $clientArr = splashModel::getClients((int)$_GET['c']);

            if ($clientArr !== false) {

                foreach($clientArr[0] as $k => $v) {
                    $this->{$k} = stripslashes($v); 

                }
                $this->metaTitle = $this->cName . ' Splash Page';

                $imageTool = new image($this->splash, $this->cName, 'splash', 'splash');
                
                
                $this->mainImage = $imageTool->resize(true);

                if ($this->mainImage == '') {
                    $this->mainImage = $imageTool->getNone('splash');   
                }
                
                
                unset($clientArr);
                                
                $galleriesArr = splashModel::getClientGalleries((int)$_GET['c']);
                
                if ($galleriesArr === false) {
                    $this->noGalleries = '<p>There are currently no galleries to display</p>';
                } else {
                    $this->noGalleries = '';
                    foreach ($galleriesArr as $key => $v) {
                        if ($this->seoUrls == 1 && $v['url'] !== '' && $v['url'] !== null) { 
                            $url = $this->url . $v['url'];
                        } else {
                            $url = $this->url . 'splash/gallery/?g=' . $v['gID'] . '&amp;c=' . $v['cID'];
                        }
                        
                        if ($v['gBlog'] == null) {
                            
                            $this->gallery[$key] = array(
                                                         $v['gID'],
                                                         $v['cID'],
                                                         $v['gName'],
                                                         '',
                                                         $v['c'],
                                                         'url'  =>  $url
                                                         );

                        } else {
                            $this->gallery[$key] = array(
                                                         $v['gID'],
                                                         $v['cID'],
                                                         $v['gName'],
                                                         '<a href="' . $v['gBlog'] . '"> &nbsp;&nbsp;&nbsp;&ndash;&ndash;&nbsp;&nbsp;{view the blog post}</a>',
                                                         $v['c'],
                                                         'url'  =>  $url
                                                         );
                        }
                    }
                }
                
            } else {
                header('location: /');
            }
                
        
        }

        
        
        
        
    }