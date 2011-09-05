<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="ltr" lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php
        if (!empty($css)) {
            foreach ($css as $c) {
                echo '<link rel="stylesheet" type="text/css" media="all" href="' . $c . '"/>';
            }
        }
    ?>
    <?php
        if (!empty($js)) {
            foreach ($js as $j) {
                echo '<script type="text/javascript" src="' . $j . '"></script>';   
            }
        }
    ?>
</head>
<body>
<div class="out">
<div class="in">
    <div class="heading">
        <a href="<?php echo $url; ?>"><img src="<?php echo $logo; ?>" alt="<?php echo $siteDescription; ?>"/ ></a>
    </div>
    <div class="wrap">
        <div class="innerwrap">