<?php
    
    class viewimages extends controller {
        public $cError = '';
        
        public function index() {
            
            $this->loggedIn();
            $page = (int)1;
            $data = $this->request('get');

            if (isset($data['g']) && isset($data['c']) && (int)$data['g'] > 0 && (int)$data['c'] > 0) {
            
                //get the images for this gallery!
                Loader::model('clients/galleries');
                            
                Loader::model();
                
                
                $totalImageCount = galleriesModel::countImages((int)$data['c'], (int)$data['g']);

                if ((int)$totalImageCount > 0) {                    
                    
                    $nextPageStyle = '';
                    $prevPageStyle = '';
                    
                    
                    if (isset ($data['pg'])) {
                        $page = (int)$data['pg'];
                    } else {
                        $page = (int)1;   
                    }
                    
                    if ($page == 1) {
                        $prevPage = 1;
                        $prevPageStyle = ' style="visibility:hidden;" ';
                        
                        $start = 0;
                        $finish = 100;
                    } else {
                        if ($page <= 5) {
                            $prevPageStyle = ' style="visibility:hidden;" ';

                            $prevPage = 1;

                        } else {
                            $prevPage = $page - 5; 
                        }
                        $start =  (($page - 1) * 100);
                        $finish = 100;
                    }


                    
                    //figure out the max pages = which is the number of images divded by the number of images per page which gives the number of pages
                    
                    $nPages = ceil((int)$totalImageCount/100);
                    //now figure out the next page:
                    
                    if ($page <= 5) {
                        $nextPage = $page + 5;
                    } elseif ($page < $nPages-4) {
                        $multiplier = ceil($page/5);
                        $nextPage = 5*($multiplier) + $page;
                    } elseif ($page < $nPages) {
                        $nextPage = $nPages;
                        $nextPageStyle = ' style="visibility:hidden;" ';
                    } else {
                        $nextPage = $page;
                        $nextPageStyle = ' style="visibility:hidden;" ';
                    }
                    
                    
                    
                    
                    $pagination = '<div class="pagination"><span>Page: </span><a class="arrow" href="' . $this->admUrl . 'clients/viewimages/?c=' . $data['c'] . '&amp;g=' . $data['g'] . '&amp;pg=' . $prevPage . '" ' . $prevPageStyle . '>&lt;</a>';
                    


                    //pagination starts at 1:

                    if ($page <= 5) {
                        for ($x=1; $x<=5; $x++) {
                            if ($x <= $nPages) {
                             
                                $pagination .= '<a ';
                                
                                if ($x == $page) {
                                    $pagination .= 'class="active" ';
                                }
                                
                                
                                $pagination .= 'href="' . $this->admUrl . 'clients/viewimages/?c=' . $data['c'] . '&amp;g=' . $data['g'] . '&amp;pg=' . $x . '">' . $x . '</a>';
                                
                            }
                        }
                    } elseif ($page >= $nPages-4) {
                        for ($x=$nPages-4; $x<=$nPages;$x++) {

                            $pagination .= '<a ';
                            if ($x == $page) {
                                $pagination .= 'class="active" ';
                            }
                            $pagination .= 'href="' . $this->admUrl . 'clients/viewimages/?c=' . $data['c'] . '&amp;g=' . $data['g'] . '&amp;pg=' . $x . '">' . $x . '</a>';
                        }
                    } else {
                        //page is more than 5 but less than $nPages 4
                        
                        for ($x = $page-2; $x<= $page+2; $x++) {
                            $pagination .= '<a ';
                            if ($x == $page) {
                                $pagination .= 'class="active" ';
                            }
                            $pagination .= 'href="' . $this->admUrl . 'clients/viewimages/?c=' . $data['c'] . '&amp;g=' . $data['g'] . '&amp;pg=' . $x . '">' . $x . '</a>';
                        }
                        
                    }
                    
                    

                    $imagesArr = galleriesModel::getImages((int)$data['c'], (int)$data['g'], $start, $finish);

                    $images = array();

                    if (!empty($imagesArr[0])) {
                    
                        $this->client = $imagesArr[0]['cName'];
                        $this->name = $imagesArr[0]['gName'];
                        foreach ($imagesArr as $imageArr) {
                            
                            $imageTool = new image($imageArr['iName'], $imageArr['cName'], strtolower(str_replace(' ', '_', $imageArr['gName'])), 'cart');
                            $img = $imageTool->resize(true);
                            unset($imageTool);
                            $images[] = array(
                                              'name'    =>  $imageArr['iName'],
                                              'img'     =>  $img,
                                              'id'      =>  $imageArr['iID']
                                              );
                            
                        }
                        
                        
                        
                        
                        
                        $pagination .= '<a class="arrow" href="' . $this->admUrl . 'clients/viewimages/?c=' . $data['c'] . '&amp;g=' . $data['g'] . '&amp;pg=' . $nextPage . '" ' . $nextPageStyle . '>&gt;</a></div>';
                        
                        $this->pagination = $pagination;
                    
                        
                    }
                    
                    
                    
                } else {
                    
                    //we still need the client info!
                    
                    $gInfo = galleriesModel::getGalleryInfo((int)$data['g'], (int)$data['c']);

                    $this->client = ucwords($gInfo['cName']);
                    $this->name = ucwords($gInfo['gName']);
                    
                    $images = array();
                }

                
                
                $this->action = $this->admUrl . 'clients/viewimages/delete/';
                $this->cid = (int)$data['c'];
                $this->g = (int)$data['g'];
                $this->p = (int)$page;
                
                $this->images = $images;
                
            }            
                       
        }
        
        
        public function delete() {
            $this->loggedIn();
            
            $data = $this->request('post');

            if (isset($data['p']) && isset($data['g']) && isset($data['c']) && (int)$data['p'] > 0 && (int)$data['c'] > 0 && (int)$data['g'] > 0 && is_array($data['delete']) && !empty($data['delete'])) {
             
                
                
                //remove the selected image!
                
                Loader::model('clients/galleries');

                $info = galleriesModel::getImageInfo($data['delete'], (int)$data['g']);
                
                
                foreach ($info as $imageData) {
                    
                    //remove the old images!
                    $imageTool = new image($info[0]['iName'], $info[0]['cName'], $info[0]['gName'], 'gallery');
                    
                    $imageTool->deleteOld();
                    unset($imageTool);

                    
                    //remove the image from the db!
                    galleriesModel::deleteImage($imageData['iID']);
                    
                    
                }
                
                //now figure out the number of pages for this gallery:
                
                $totalImageCount = galleriesModel::countImages((int)$data['c'], (int)$data['g']);
                if ($totalImageCount > 0) {
                    
                    if ($data['p'] > ceil($totalImageCount/100)) {
                        $p = (int)(ceil($totalImageCount/100));
                    } else {
                        $p = (int)$data['p'];   
                    }
                } else {
                    $p = (int)1;
                }
                
                
                
            } else {
                $p = (int)$data['p'];
            }
            
            if ((int)$p == 0) {
                $p = 1;   
            }
            
            $this->redirectAdm('clients/viewimages/?g=' . $data['g'] . '&c=' . $data['c'] . '&pg=' . $p);
            

            
        }
        
        private function loggedIn() {
            Loader::model('user/loginauth');
            
            
            if (!loginauthModel::isLoggedIn()) {
                
                
                $this->redirectAdm('login/login');
            }
            
        }
        
    }
