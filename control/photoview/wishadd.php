<?php
    
class wishadd extends controller {

    public function index() {

        
        
        $data = $this->request('post');

        if (!isset($data['redirectUrl'])) {
            $this->redirect();
        }
        


        if ((isset($data['wishes']) || isset($data['removewishes'])) && (isset($data['itemID']) && (int)$data['itemID'] > 0)) {

         
            $this->isLogged($data['requestUrl']);

            
            //add the item into the wish lists for this user
        
            Loader::model('user/wish');
            
            wishModel::add($_COOKIE['tpproofing_login'], $data['wishes'], $data['itemID']);
         

            //figure out if we're removing any!
            
            if (isset($data['removewishes'])) {

                wishModel::remove($_COOKIE['tpproofing_login'], $data['removewishes'], $data['itemID']);
                
            }
            
            
        }
        
        $this->redirect(urldecode($data['redirectUrl']));
        
    }
    
    
    private function isLogged($data) {
        Loader::model('user/status');
        if (!statusModel::isLoggedIn()) {
            $this->redirect('users/login/?redirectUrl=' . $data['redirectUrl']);
        }
        
    }
    
    
    
}