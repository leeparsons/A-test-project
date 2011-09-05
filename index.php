<?php

    include 'system/environment.php';
    $env = new environment();

    include $env->systemDir() . 'includes.php';

    $session = new session();
    
    $db = new data();
    $db->index($env);

    $urls = new url();
    $controller = new controller();


    
    if ((int)$controller->seoUrls == 1) {
        $seo = new seo($controller, $urls);
    }
    
    $action = new action();

    $action->render($urls->getIncludes(),$env,$controller);
    
    
    