<?php

    class gallery extends controller {

        public function index() {


            //check to see if the gId, and cID are set:
            
            if (isset ($_GET['g']) && isset($_GET['c'])) {
                $this->cid = (int)$_GET['c'];
                $this->gid = (int)$_GET['g'];
                
                Loader::model('splash/gallery');
                Loader::model('splash/splash');
                
                //determine if the current gallery has  a password associated with it:
                
                $galleryInformation = galleryModel::getGalleryInformation($this->gid);


                if ($galleryInformation === false) {
                    $this->redirect();
                }
                

                $clientInfo = splashModel::getClients((int)$_GET['c']);
                
                
                $this->title = (!stripos($galleryInformation['gName'], 'gallery'))?$clientInfo[0]['cName'] . '&rsquo;s ' . ucwords($galleryInformation['gName']) . ' Gallery':$clientInfo[0]['cName'] . ' &ndash; ' . ucwords($galleryInformation['gName']);                    
                

                $this->metaTitle = $this->title;

                if (($galleryInformation['gExpiry'] <= time() && $galleryInformation['noexpiry'] == 0) || $galleryInformation['active'] == 0) {
                    $this->message = 'This gallery has expired';
                    
                    return;
                }

                if ($galleryInformation['gpword'] !== '' && $galleryInformation['gpword'] !== null) {
                
                    //determine if this client has entered the password in the past 24 hours
                    if (!isset($_COOKIE['tpproofing_gallery_' . $this->gid])) {
                        //redirect to the password page
                        $this->template = 'galleryentry';
                        $this->gallery = $galleryInformation;
                        
                        $imageTool = new image($clientInfo[0]['splash'], $clientInfo[0]['cName'], 'splash', 'splash');
                        
                        
                        $this->mainImage = $imageTool->resize(true);
                        
                        if ($this->mainImage == '') {
                            $this->mainImage = $imageTool->getNone('splash');   
                        }
                        
                        unset($imageTool);
                        
                        
                        
                        $this->message = 'This gallery is password protected.<br/><br/>Please enter the gallery password to view the images.<br/><br/>';
                        $this->action = $this->url . 'splash/gallery/entry/';
                        $this->displayForm = true;
                        return;
                    } else {
                        //readjust the cookie timer!
                        setcookie('tpproofing_gallery_' . $this->gid, 1, time() + 60*60*24, $this->url);
                    }
                }
                
                
                $this->totalImageCount = galleryModel::countImages($_GET['c'], $_GET['g']);                

                $prevPageStyle = '';
                $nextPageStyle = '';
                
                if (!$this->totalImageCount || !$clientInfo) {                  
                    $this->redirect();
                } else {
                    $this->nextPageStyle = '';
                    $this->prevPageStyle = '';

                    
                    if (isset ($_GET['pg'])) {
                        $this->page = (int)$_GET['pg'];
                    } else {
                        $this->page = (int)1;   
                    }

                    if ($this->page == 1) {
                        $prevPage = 1;
                        $this->prevPageStyle = ' style="visibility:hidden;" ';

                        $this->start = 0;
                        $this->finish = 25;
                    } else {
                        $prevPage = $this->page - 1; 
                        $this->start =  (($this->page - 1) * 25);
                        $this->finish = 25;
                    }


                    

                    $images = galleryModel::getImages($_GET['c'], $_GET['g'], $this->start, $this->finish);
                    

                    
                    //figure out the number of files per row:
                    
                    
                    $nImages = count($images);
                    
                    if ($nImages % 5 !== 0) {
                        //not even fit!
                        
                        $this->nRows = (int)floor($nImages/5) + 1;
                        

                        
                        $this->nFinal = 5 - (($this->nRows*5) - $nImages);
                    } else {
                        $this->nFinal = 5;   
                        $this->nRows = $nImages/5;
                    }

                    //figure out the max pages = which is the number of images divded by the number of images per page which gives the number of pages
                    
                    $nPages = ceil((int)$this->totalImageCount/25);
                    //now figure out the next page:
                    $this->nPages = $nPages;
                    
                    if ($this->page < $nPages) {
                        $nextPage = $this->page + 1;
                    } else {
                        $nextPage = $this->page;
                        $this->nextPageStyle = ' style="visibility:hidden;" ';
                    }
                    
                    $this->prevPage = $prevPage;
                    $this->nextPage = $nextPage;
                    
                    
                    foreach ($images as $k => $im) {

                        

                        $imageTool = new image($im['iName'], $clientInfo[0]['cName'], strtolower(str_replace(' ', '_', $im['gName'])), 'gallery');
                        $newImage = $imageTool->resize(true);

                        unset($imageTool);                        
                        $this->images[] = array(
                                                'html'   =>     $newImage,
                                                'href'   =>     $this->url . 'photoview/?c=' . $im['cID'] . '&amp;g=' . $im['gID'] . '&amp;p=' . $im['iID']
                        );

                        unset($newImage);
                    }
                    

                    
                    $this->totalImages = $nImages;
                    

                    
                    $this->nImages = $nImages;
                    
                    if ($this->totalImageCount > $nImages) {
                        
                        $this->paginationTop = '<div class="paginationtop"><a ' . $prevPageStyle . ' href="?c=' . $_GET['c'] . '&amp;g=' . $_GET['g'] . '&amp;pg=' . $prevPage . '">&lt;</a>Page: <strong>' . $this->page . '</strong> of <strong>' . $nPages . '</strong><a ' . $nextPageStyle . ' href="?c=' . $_GET['c'] . '&amp;g=' . $_GET['g'] . '&amp;pg=' . $nextPage . '">&gt;</a></div>';
                        $this->paginationBottom = '<div class="paginationbottom"><a ' . $prevPageStyle . ' href="?c=' . $_GET['c'] . '&amp;g=' . $_GET['g'] . '&amp;pg=' . $prevPage . '">&lt;</a>Page: <strong>' . $this->page . '</strong> of <strong>' . $nPages . '</strong><a ' . $nextPageStyle . ' href="?c=' . $_GET['c'] . '&amp;g=' . $_GET['g'] . '&amp;pg=' . $nextPage . '">&gt;</a></div>';
                    } else {
                        $this->pagination = '';
                        $this->paginationBottom = '';
                    }
                    
                }
                

            } else {
                
                
                $this->redirect();
                
                
            }
            

        }

        
        public function entry() {
         

            $data = $this->request('post');

            
            if (!isset($data['g']) || !isset($data['c']) || (int)$data['g'] < 1 || (int)$data['c'] < 1) {
                $this->redirect();
            }

            $g = (int)$data['g'];
            $c = (int)$data['c'];
            $this->gid = $g;
            $this->cid = $c;
            
            Loader::model('splash/gallery');
            Loader::model('splash/splash');
            
            if (isset($data['redirect'])) {
                $this->redirectUrl = $data['redirect'];   
            } else {
                $this->redirectUrl = '';   
            }
            
            
            
            //determine if the current gallery has  a password associated with it:
            
            $galleryInformation = galleryModel::getGalleryInformation($g);

            if ($galleryInformation === false) {
                $this->redirect();
            }
            

            $clientInfo = splashModel::getClients($c);
            
            $imageTool = new image($clientInfo[0]['splash'], $clientInfo[0]['cName'], 'splash', 'splash');
            
            
            $this->mainImage = $imageTool->resize(true);
            
            if ($this->mainImage == '') {
                $this->mainImage = $imageTool->getNone('splash');   
            }

            
            $this->title = (!stripos($galleryInformation['gName'], 'gallery'))?$clientInfo[0]['cName'] . '&rsquo;s ' . $galleryInformation['gName'] . ' Gallery':$clientInfo[0]['cName'] . ' &ndash; ' . $title;                    
            
            if (($galleryInformation['gExpiry'] <= time() && $galleryInformation['noexpiry'] == 0) || $galleryInformation['active'] == 0) {


                $this->message = 'This gallery has expired';
                $this->action = $this->url . 'splash/gallery/entry/';
                $this->displayForm = false;
                $this->template = 'galleryentry';
                return;
            }

            //now determine if this gallery has the password match
            if ($galleryInformation['gpword'] == $data['pw']) {
                setcookie('tpproofing_gallery_' . $g, 1, time() + 60*60*24, $this->url);



                $this->redirect('splash/gallery/?c=' . $c . '&g=' . $g);
            } else {

                $this->template = 'galleryentry';
                $this->gallery = $galleryInformation;
                $this->message = 'This gallery is password protected.<br/><br/>Please enter the gallery password to view the images.<br/><br/><span class="error">Please enter the correct password.</span>';
                $this->action = $this->url . 'splash/gallery/entry/';
                $this->displayForm = true;
                return;
            }
            
            
            
            
        }
        
        
        
    }