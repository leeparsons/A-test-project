<?php


    if (isset ($_GET['g']) && intval($_GET['g'])) {
        $gallery = $_GET['g'];        
    } else {
        $gallery = (int)1;
    }
    
    if ($gallery == 1) {
        $images = array("/images/galleries/weddings/creative_editorial_wedding_photographers_maunsel_house_somerset.jpg",
                    "/images/galleries/weddings/creative_wedding_photographers_bristol_clifton.jpg",
                    "/images/galleries/weddings/creative_wedding_photographers_devon_rosie_parsons.jpg",
                    "/images/galleries/weddings/creative_wedding_photography_bristol_rosie_parsons.jpg",
                    "/images/galleries/weddings/creative-devon-wedding-photographers.jpg",
                    "/images/galleries/weddings/creative-editorial-wedding-photos-rosie-parsons.jpg",
                    "/images/galleries/weddings/creative-relaxed-wedding-photography-devon-rosie.jpg",
                    "/images/galleries/weddings/creative-relaxed-wedding-photography-devon.jpg",
                    "/images/galleries/weddings/eco_friendly_weddings.jpg",
                    "/images/galleries/weddings/editorial_creative_wedding_photography_devon.jpg",
                    "/images/galleries/weddings/editorial_creative_wedding_photography_rosie_parsons.jpg",
                    "/images/galleries/weddings/creative_editorial_wedding_photographers_maunsel_house_somerset.jpg",
                    "/images/galleries/weddings/creative_wedding_photographers_bristol_clifton.jpg",
                    "/images/galleries/weddings/creative_wedding_photographers_devon_rosie_parsons.jpg",
                    "/images/galleries/weddings/creative_wedding_photography_bristol_rosie_parsons.jpg",
                    "/images/galleries/weddings/creative-devon-wedding-photographers.jpg",
                    "/images/galleries/weddings/creative-editorial-wedding-photos-rosie-parsons.jpg",
                    "/images/galleries/weddings/creative-relaxed-wedding-photography-devon-rosie.jpg",
                    "/images/galleries/weddings/creative-relaxed-wedding-photography-devon.jpg",
                    "/images/galleries/weddings/eco_friendly_weddings.jpg",
                    "/images/galleries/weddings/editorial_creative_wedding_photography_devon.jpg",
                    "/images/galleries/weddings/editorial_creative_wedding_photography_rosie_parsons.jpg",
                    "/images/galleries/weddings/creative_editorial_wedding_photographers_maunsel_house_somerset.jpg",
                    "/images/galleries/weddings/creative_wedding_photographers_bristol_clifton.jpg",
                    "/images/galleries/weddings/creative_wedding_photographers_devon_rosie_parsons.jpg",
                    "/images/galleries/weddings/creative_wedding_photography_bristol_rosie_parsons.jpg",
                    "/images/galleries/weddings/creative-devon-wedding-photographers.jpg",
                    "/images/galleries/weddings/creative-editorial-wedding-photos-rosie-parsons.jpg",
                    "/images/galleries/weddings/creative-relaxed-wedding-photography-devon-rosie.jpg",
                    "/images/galleries/weddings/creative-relaxed-wedding-photography-devon.jpg",
                    "/images/galleries/weddings/eco_friendly_weddings.jpg",
                    "/images/galleries/weddings/editorial_creative_wedding_photography_devon.jpg",
                    "/images/galleries/weddings/editorial_creative_wedding_photography_rosie_parsons.jpg",
                    "/images/galleries/weddings/creative_editorial_wedding_photographers_maunsel_house_somerset.jpg",
                    "/images/galleries/weddings/creative_wedding_photographers_bristol_clifton.jpg",
                    "/images/galleries/weddings/creative_wedding_photographers_devon_rosie_parsons.jpg",
                    "/images/galleries/weddings/creative_wedding_photography_bristol_rosie_parsons.jpg",
                    "/images/galleries/weddings/creative-devon-wedding-photographers.jpg",
                    "/images/galleries/weddings/creative-editorial-wedding-photos-rosie-parsons.jpg",
                    "/images/galleries/weddings/creative-relaxed-wedding-photography-devon-rosie.jpg",
                    "/images/galleries/weddings/creative-relaxed-wedding-photography-devon.jpg",
                    "/images/galleries/weddings/eco_friendly_weddings.jpg",
                    "/images/galleries/weddings/editorial_creative_wedding_photography_devon.jpg",
                    "/images/galleries/weddings/editorial_creative_wedding_photography_rosie_parsons.jpg");
    } else {
        $images = array(
                        "/images/galleries/Robyn-and-Marc/Devon wedding photographer 0001.jpg",
                        "/images/galleries/Robyn-and-Marc/Devon wedding photographer 0002.jpg",
                        "/images/galleries/Robyn-and-Marc/Devon wedding photographer 0003.jpg",
                        "/images/galleries/Robyn-and-Marc/Devon wedding photographer 0004.jpg",
                        "/images/galleries/Robyn-and-Marc/Devon wedding photographer 0005.jpg",
                        "/images/galleries/Robyn-and-Marc/Devon wedding photographer 0006.jpg",
                        "/images/galleries/Robyn-and-Marc/Devon wedding photographer 0007.jpg",
                        "/images/galleries/Robyn-and-Marc/Devon wedding photographer 0008.jpg",
                        "/images/galleries/Robyn-and-Marc/Devon wedding photographer 0009.jpg",
                        "/images/galleries/Robyn-and-Marc/Devon wedding photographer 0010.jpg",
                        "/images/galleries/Robyn-and-Marc/Devon wedding photographer 0011.jpg",
                        "/images/galleries/Robyn-and-Marc/Devon wedding photographer 0012.jpg",
                        "/images/galleries/Robyn-and-Marc/Devon wedding photographer 0013.jpg",
                        "/images/galleries/Robyn-and-Marc/Devon wedding photographer 0014.jpg",
                        "/images/galleries/Robyn-and-Marc/Devon wedding photographer 0015.jpg",
                        "/images/galleries/Robyn-and-Marc/Devon wedding photographer 0016.jpg",
                        "/images/galleries/Robyn-and-Marc/Devon wedding photographer 0017.jpg",
                        "/images/galleries/Robyn-and-Marc/Devon wedding photographer 0018.jpg",
                        "/images/galleries/Robyn-and-Marc/Devon wedding photographer 0019.jpg",
                        "/images/galleries/Robyn-and-Marc/Devon wedding photographer 0020.jpg",
                        "/images/galleries/Robyn-and-Marc/Devon wedding photographer 0021.jpg",
                        "/images/galleries/Robyn-and-Marc/Devon wedding photographer 0022.jpg",
                        "/images/galleries/Robyn-and-Marc/Devon wedding photographer 0023.jpg",
                        "/images/galleries/Robyn-and-Marc/Devon wedding photographer 0024.jpg",
                        "/images/galleries/Robyn-and-Marc/Devon wedding photographer 0025.jpg"
        );
    }
    
    
    
    if (isset($_GET['page']) && intval($_GET['page'])) {
        $start = $_GET['page'] * 16;
    } else {
        $start = 0;
    }

    $finish = $start + 25;
    
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="ltr" lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="http://jscrollpane.kelvinluck.com/script/jquery.jscrollpane.min.js"></script>
<script type="text/javascript" src="http://jscrollpane.kelvinluck.com/script/jquery.mousewheel.js"></script>
<link type="text/css" href="/css/scroll.css" rel="stylesheet" media="all" />
<link type="text/css" href="/css/cart.css" rel="stylesheet"/>
</head>
<body>
<div class="out">
	<div class="in">
		<div class="heading">
			<a href=""><img src="http://www.rosieparsons.com/images/logo.gif" alt="rosie parsons wedding photography"/ ></a>
		</div>
		<div class="wrap">
			<ul class="topmenu">
				<li><a href="http://www.rosieparsons.com/">website</a></li>
				<li><a href="http://www.rosieparsons.com/">blog</a></li>
				<li><a href="http://www.rosieparsons.com/">contact</a></li>
                <li><a href="/">Proofing Home</a></li>
                <li><a href="/splash.php">Splash</a>
				<li class="fr">
					<a href="">Cart (0) items</a>
				</li>
			</ul>
			<div class="innerwrap">
				<div class="title">
					Wedding Gallery One &ndash; Page One
				</div>
				<div class="relativewrapper">
                    <div class="imagegallery">
                        <?php

                            if (!empty($images)) {
                            
                                
                                echo '<table class="imagegalleryt" cellspacing="0">
                                
                                
                                <tbody>';
                                
                                
                                $i = (int)1;
                                
                                foreach ($images as $k => $v) {
                            
                                    if ($i == 1) {
                                        echo '<tr>';
                                    }
                                    
                                    if ($k+1 > $start && $k+1 <= $finish) {
                                        
                                        if ($k+1 >= $finish - 4) {
                                            echo '<td class="bottom"><a href="/photos.php?g=' . $gallery . '&amp;p=' . $k . '"><img src="http://www.rosieparsons.com' . $v . '" alt="" width="200"/></a></td>';                                            
                                        } else {
                                            if ($i == 5) {
                                                echo '<td class="last"><a href="/photos.php?g=' . $gallery . '&amp;p=' . $k . '"><img src="http://www.rosieparsons.com' . $v . '" alt="" width="200"/></a></td>';    
                                            } else {
                                                echo '<td><a href="/photos.php?g=' . $gallery . '&amp;p=' . $k . '"><img src="http://www.rosieparsons.com' . $v . '" alt="" width="200"/></a></td>';
                                            }
                                        }
                                    
                                    }
                                    
                                    
                                    if ($i == 5) {
                                        echo '</tr>';
                                        $i = (int)0;
                                    }
                                
                                    $i+=1;
                                
                                }
                                
                                if ($i > 1 && $i < 5) {
                                    
                                    for ($x = 1; $x <= 5; $x++) {
                                        echo '<td>&nbsp;</td>';
                                    }
                                }
                                
                                echo '</tbody>
                                
                                </table>';
                                
                            }
                            
                        ?>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>