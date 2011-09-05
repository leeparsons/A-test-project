<?php

class reminder extends controller {

        public $error = array();
        
        public function index() {

            if (isset($_REQUEST['r'])) {
                $this->redirectUrl = urlencode(stripslashes($_REQUEST['r']));
            } else {
                $this->redirectUrl = '';   
            }
            $this->action = $this->url . 'users/reminder/question/';

        }        
    
    public function question() {
        if (isset($_REQUEST['r'])) {
            $this->redirectUrl = urlencode(stripslashes($_REQUEST['r']));
        } else {
            $this->redirectUrl = '';   
        }

        //see if the email is set and if it exists:
        
        $data = $this->request('post');
        Loader::model('user/status');
                      

        if (isset($data['email']) && $id = statusModel::doesEmailExist($data['email'])) {
            $this->action = $this->url . 'users/reminder/answer/';

            $this->template = 'answer';
            
            $this->hash = $id['hash'];
            
            $this->question = statusModel::getQuestionByID($id['id']);
            
            
        } else {
            $this->action = $this->url . 'users/reminder/question/';
            $this->error['email'] = 'That email addres is not recognised. Please make sure you have entered your email address correctly.';
            $this->template = 'reminder';
        }
    }
    
    public function answer() {
        $data = $this->request('post');

        if (isset($data['r'])) {
            $this->redirectUrl = $data['r'];
        } else {
            $this->redirectUrl = '';   
        }
        
        if (isset($data['answer']) && isset($data['h']) && strlen($data['h']) > 0 && isset($data['q'])) {
            
            $this->hash = $data['h'];
            Loader::model('user/status');
            
            //see if the answer given is correct?
            
            if (statusModel::checkAnswerCorrect($data['answer'], $data['h'])) {
                
                //get the user information:
                
                $userInfo = statusModel::getUserInfo($data['h']);

                //got the answer correct so set up the mailer
                
                $mail = new Mailer();
                $mail->setTo($userInfo['email']);
                $controller = new controller();
                $mail->setSubject('You requested a password reminder from ' . $controller->getVar('storeName'));
                $url = new url();
                $mail->setMessage('Dear ' . $userInfo['name'] . "\n\n" . 'Your password was requested. Your password is: ' . $userInfo['normalpassword'] . '.' . "\n\n" . 'You can log in here: ' . $url->getFullBaseUri() . $this->url . 'users/login/');
                $mail->send();
                $this->template = 'sentreminder';  
                $this->redirectUrl = $this->url . urldecode(urldecode($this->redirectUrl));
            } else {
                $this->error['answer'] = 'That is not the correct answer.';
                $this->template = 'answer';
                $this->question = $data['q'];
                $this->answer = $data['answer'];
            }
            
            
        } else {
         
            $this->redirect('users/login');            
        }
        
        
    }
    
    
}