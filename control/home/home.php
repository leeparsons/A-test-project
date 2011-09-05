<?php

    class home extends controller {

        public function index() {
            
            Loader::model('home/home');
        
            if ($this->seoUrls == 1) {
                Loader::model('seo/seo');                
            }
            
            
            $results = homeModel::getClientGalleries();

            $this->metaTitle = 'Online Proofing Parlour Home';
            
            if (is_array($results)) {
                
                
                $this->homeHtml = '<table class="imagegalleryt" cellspacing="0"><tbody>';
                
                $i = (int)1;
                
                $c = count($results);
                
                foreach($results as $k => $v) {

                    if ($this->seoUrls == 1) {
                        
                        //get the seo url!
                        $urls = seoModel::getSeoUrl($v['cID'], 'c');
                        if (!empty($urls)) {
                            $url = $this->url . $urls[0]['url'];
                        } else {
                            $url = $this->url . 'splash/?c=' . $v['cID'];                            
                        }
                    } else {
                        $url = $this->url . 'splash/?c=' . $v['cID'];
                    }

                    
                    $name = ((string)$v['cName'] == '0')?'':$v['cName'];
                    
                    if ($i == 1) {
                        $this->homeHtml .= '<tr>';
                    }

                    $this->homeHtml .= '<td onclick="window.location = \'' . $url . '\';"><div class="inside">';

                    $imageTool = new image($v['cFrontImage'], $v['cName'], 'clientfront', 'gallery');
                    $newImage = $imageTool->resize(true);
                    if ($newImage == '') {
                        $newImage = $imageTool->getNone();
                    }
                    $this->homeHtml .= '<a href="' . $url . '">' . $newImage . '<span>' . $name . '</span></a>';
                    
                    
                    unset($newImage);
                    $this->homeHtml .= '</div></td>';
                    
                    if ($i == 2) {
                        $this->homeHtml .= '</tr>';
                        $i = (int)0;
                    }
                    flush();
                    $i++;
                }

                if ($i > 1) {                   
                    $this->homeHtml .= '<td onmouseover="$(this).css({background:\'none\'});"></td></tr>';
                }
                
                
                $this->homeHtml .= '</tbody></table>';

            } else {
                
                $this->homeHtml = '<p>Sorry, there are currently no client galleries to show.</p>';
                
                
            }
                        
        
        }

        
        
        
        
    }