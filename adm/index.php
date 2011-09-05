<?php
    
    
    include '../system/environment.php';

    
    
    $env = new environment();

    include $env->systemDir() . 'includes.php';

    $session = new session();

    $urls = new url();
    $controller = new controller();
    $action = new action();


    
    $action->render($urls->getIncludes(),$env,$controller);
    
    
    