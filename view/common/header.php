<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="ltr" lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php
        if (!empty($css)) {
            foreach ($css as $c) {
                echo '<link rel="stylesheet" type="text/css" media="all" href="' . $c . '"/>';
            }
        }
    if (!empty($js)) {
        foreach ($js as $j) {
            echo '<script type="text/javascript" src="' . $j . '"></script>';   
        }
    }
    ?>
<title><?php echo (isset($metaTitle))?$metaTitle:''; ?></title>
<meta name="robots" content="noindex, nofollow"/>
</head>
<body>
<div class="out">
<div class="in">
    <div class="heading">
        <a href="<?php echo $webSiteUrl; ?>"></a>
    </div>
    <div class="wrap">
        <ul class="topmenu">
            <li><a href="<?php echo $webSiteUrl; ?>">website</a></li>
            <li><a href="<?php echo $blogUrl; ?>">blog</a></li>
            <li><a href="<?php echo $contactUrl; ?>">contact</a></li>
            <li><a href="<?php echo $url; ?>">proofing home</a></li>

<?php
    
    if (!empty($oneOffUrls)) {

        ?>
            <li><ul>
            <?php 
                foreach ($oneOffUrls as $oneOffUrl) {
                
                    echo '<li><a href="' . $oneOffUrl['url'] . '">' . $oneOffUrl['text'] . '</a></li>';
                    
                }
?>
            </ul><span>One Off Payments</span></li>

<?php
        
    }
    
    ?>
    

<?php
    if ($logged) {
    ?>
            <li class="fr">
                <a href="<?php echo $logout; ?>">Logout</a>
            </li>
<?php
    } else {
        ?>
            <li class="fr">
                <a href="<?php echo $login; ?>">Login</a>
            </li>
<?php
    
        
    }
    
    if ((int)$cartItems > 0) {
?>
            <li class="fr">
                <a class="frcart" href="<?php echo $cartUrl; ?>">Cart (<?php echo $cartItems; ?>) items</a>
            </li>
<?php
    
    }
    
    if ((int)$wishListsCountForMenu > 0) {
    ?>
        <li class="fr">
            <a href="<?php echo $wishListsUrlForMenu; ?>">Wishlists (<?php echo $wishListsCountForMenu; ?>)</a>
        </li>
<?php
    }
    
    
    ?>
        </ul>
<script type="text/javascript">/*<![CDATA[*/
Cufon.replace('ul');Cufon.now();
/*]]>*/</script>
		<div class="innerwrap">