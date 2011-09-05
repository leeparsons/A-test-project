<?php

    class action {
        
        public function render($controllers, $env, $controller) {

            
            
            foreach (get_object_vars($controller) as $key => $var) {
                ${$key} = $var;
            }

            $renders = array();
            foreach ($controllers as $k => $c) {

                if (!isset($noRender)) {
                    try {
                        $fileArr = explode('/', $c);

                        switch (count($fileArr)) {
                            default:
                                if ($env->isAdm()) {
                                    //we're in the adm extension

                                    include $env->controllerDir() . $fileArr[count($fileArr) - 2] . '/' . $fileArr[count($fileArr) - 1] . '.php';
                                    
                                    $obj = new $fileArr[count($fileArr) - 1]();
                                    $obj->index();
                                    
                                    if (!isset(${$fileArr[count($fileArr) - 1] . 'NoRender'})) {
                                        if ($obj->template !== '') {
                                            $renders[] = $fileArr[count($fileArr) - 2] . '/' . $obj->template;   
                                        } else {
                                            $renders[] = $fileArr[count($fileArr) - 2] . '/' . $fileArr[count($fileArr) - 1];
                                        }
                                    }
                                    
                                } else {
                                
                                    include $env->controllerDir() . $c . '.php';
                                    $obj = new $fileArr[count($fileArr) - 1]();
                                    $obj->index();
                                    
                                    if (!isset(${$fileArr[count($fileArr) - 1] . 'NoRender'})) {
                                        if ($obj->template !== '') {
                                            $renders[] = $fileArr[count($fileArr) - 2] . '/' . $obj->template;
                                        } else {
                                            $renders[] = $fileArr[count($fileArr) - 2] . '/' . $fileArr[count($fileArr) - 1];
                                        }
                                    }
                                    
                                    
                                }
                                break;                           
                            case 4:
                                
                                if ($env->isAdm()) {
                                    //we're in the adm extension
                                    
                                    include $env->controllerDir() . $fileArr[2] . '/' . $fileArr[3] . '.php';
                                    
                                    $obj = new $fileArr[3]();
                                    $obj->index();
                                    
                                    if (!isset(${$fileArr[3] . 'NoRender'})) {
                                        if ($obj->template !== '') {
                                            $renders[] = $fileArr[2] . '/' . $obj->template;   
                                        } else {
                                            $renders[] = $fileArr[2] . '/' . $fileArr[3];
                                        }
                                    }
                                    
                                } else {
                                    
                                    
                                    include $env->controllerDir() . $fileArr[1] . '/' . $fileArr[2] . '.php';
                                    $obj = new $fileArr[2]();
                                    $obj->$fileArr[3]();
                                    
                                    if (!isset(${$fileArr[2] . 'NoRender'})) {
                                        if ($obj->template !== '') {
                                            $renders[] = $fileArr[1] . '/' . $obj->template;   
                                        } else {
                                            $renders[] = $fileArr[1] . '/' . $fileArr[2];
                                        }
                                        
                                    }
                                }
                                break;
                            case 5:
                                
                                if ($env->isAdm()) {
                                    //we're in the adm extension
                                    
                                    include $env->controllerDir() . $fileArr[2] . '/' . $fileArr[3] . '.php';
                                    
                                    $obj = new $fileArr[3]();
                                    
                                    $obj->$fileArr[4]();
                                    
                                    
                                    if (!isset(${$fileArr[3] . 'NoRender'})) {
                                        
                                        if ($obj->template !== '') {
                                            $renders[] = $fileArr[2] . '/' . $obj->template;   
                                        } else {
                                            $renders[] = $fileArr[2] . '/' . $fileArr[3];
                                        }
                                    }
                                    
                                } else {
                                    die('an execption occured in the action you were trying to do. Please contact parsolee@gmail.com with error code: 1001.');
                                }
                                
                                break;
                        }
                        
                        foreach (get_object_vars($obj) as $key => $var) {
                            if (isset(${$key})) {
                                if (!is_array(${$key})) {
                                    if (is_array($var)) {
                                        if (!empty(${$key})) {
                                            //need to recast key as array:
                                            ${$key} = array(${$key});
                                        }
                                        foreach ($var as $i => $v) {
                                            ${$key}[] = $v;  
                                        }
                                    } else {
                                        ${$key} = $var;
                                    }
                                } else {
                                    if (is_array($var)) {
                                        foreach ($var as $i => $v) {
                                            ${$key}[] = $v;  
                                        }
                                    } else {
                                        ${$key}[] = $var;
                                    }
                                }
                            } else {
                                ${$key} = '';
                                if (!is_array(${$key})) {
                                    ${$key} = $var;
                                } else {
                                    ${$key}[] = $var;   
                                }                    
                            }
                        }
                        
                        
                    } catch (Exception $e) {
                        die('The installation of the proofing parlour is broken!' . "\n\n" . 'error: ' . $e);
                    }
                }
                
                
            }
            
            

            if (!isset($noRender)) {
                foreach ($renders as $c) {
                    include_once $env->viewDir() . $c . '.php';                    
                }
            }
                
                
            
            
        }
    }

