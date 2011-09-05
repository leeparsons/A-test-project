<div class="title">
<?php echo $title; ?>
</div>
<div class="relativewrapper">
	<div class="border"></div>
<div class="bigpicleft">
    <div class="bigpic tc">
        <a href="<?php echo $fancy; ?>" class="mag"></a>
        <?php echo $img; ?>
    </div>
	<div class="picoptions">
		<ul class="optnul">
			<li>
				<a class="active" id="orderprint">Order Print</a>
			</li>
			<li>
				<a id="orderdownload">Order Download</a>
			</li>
			<li class="fr">
				<a href=""></a>
			</li>
		</ul>
        <form method="post" id="addcart" action="<?php echo $cartAddLink; ?>">
		<div class="orderprint ordershowhide">
			<div class="w75">
				<div>
					<label for="quantity">Qnty</label>
					<input type="text" id="quantity" name="quantity" value="1" size="3"/>
				</div>
                    <?php 

                        if (!empty($options)) {
                            echo '<div class="right">';
    
                            foreach ($options as $kName => $op) {
                                echo '<label for="option' . $kName . '">' . $kName . '</label>';

                                if (!empty($op)) {
                                    
                                    echo '<select name="options[' . $kName . ']" id="option' . $kName . '">';
                                    foreach ($op['options'] as $tag => $v) {

                                        echo '<option value="' . $tag . '__' . $v . '">' . $tag . ' &pound;' . $v . '</option>';
                                    }
                                    
                                    echo '</select>';
                                    
                                }
                            }
                            echo '</div>';
                            
                        }
                        
                        ?>
			</div>
			<div class="w75">
				<label for="notes">Notes</label>
				<textarea cols="39" rows="5" id="notes" name="notes"></textarea>
			</div>
			<div class="w25">
				<input type="submit" value="Add to cart" href="/addtocart/" class="addtocart"/>
				<a href="" class="gotocheckout">Go to Checkout</a>
			</div>
        </div>
        <input type="hidden" value="<?php echo $token; ?>" name="tkn" id="tkn"/>
        <input type="hidden" value="<?php echo $itm; ?>" name="itemID" id="itemID"/>
        <input type="hidden" value="<?php echo $descriptiveName; ?>" name="pname" id="pname"/>
        <input type="hidden" value="<?php echo $cName; ?>" name="cname" id="cname"/>
        <input type="hidden" value="<?php echo $gName; ?>" name="gname" id="gname"/>
        <input type="hidden" value="<?php echo $type; ?>" name="type" id="type"/>
        <input type="hidden" value="<?php echo $imageName; ?>" name="imagename" id="imagename"/>
        </form>
        <form method="post" id="adddownload" action="<?php echo $cartDownloadLink; ?>">
		<div class="orderdownload ordershowhide" style="display:none;">
			<div class="w75">
				<p>To order a download of this image you need to add the image to the downloads basket. When you have added all the images you wish to download, proceed to checkout where, after paying you will receive your download link.</p>
			</div>
			<div class="w25">
				<input type="submit" class="addtocart" value="Add to downloads"/>
				<a href="" class="gotocheckout">Go to Checkout</a>
			</div>
        </div>
        <input type="hidden" value="<?php echo $token; ?>" name="tkn" id="tkn2"/>
        <input type="hidden" value="<?php echo $itm; ?>" name="itemID" id="itemID2"/>
        </form>
    </div>
	<script type="text/javascript">/*<![CDATA[*/
						$('.optnul').find('a').click(function () {
							if (!$(this).hasClass('aactive')) {
								$('.optnul').find('a').removeClass('active');
								$(this).addClass('active');
								$('.ordershowhide').css('display','none');
								$('.' + $(this).attr('id')).css('display','block');
							}
						});
	/*]]>*/</script>
</div>
<div class="bigpicright">
	<div class="galleryinner">
		<ul class="agaller" id="gallerynav">
           <?php
                               
                                foreach ($imgNav as $key => $val) {
                                    
                                    echo '<li ' . $val['class'] . '><a ' . $val['class'] . ' href="' . $val['href'] . '" style="background:url(' . $val['img'] . ') no-repeat center center;"></a></li>';
                                }
                              
           ?>
		</ul>
	</div>
	<div class="gallerynavigation">
		<a href="<?php echo $firstGallery; ?>" class="beginning"></a>
		<a href="<?php echo $prevImg; ?>" class="prev"></a>
		<a href="<?php echo $dash; ?>" class="dashboard"></a>
		<a href="<?php echo $nextImg; ?>" class="next"></a>
		<a href="<?php echo $lastGallery; ?>" class="end"></a>
	</div>
	<!--div class="cartcurrent">
		<ul class="cartpreview">
            <li><a href="">Cart Preview</a></li>
			<li class="fr"><a></a></li>
        </ul>
		<div class="cartpreview">
			<div class="thead">
				<span class="image">image</span>
				<span class="detail">detail</span>
				<span class="q">quantity</span>
				<span class="cost">cost</span>
			</div>
            <div class="tbody scroll-pane">
                <div class="fl">
					<span class="b1"></span>
					<span class="b2"></span>
					<span class="b3"></span>
					<span class="image"><img src="http://www.rosieparsons.com/images/galleries/Harry-and-Laura/eco%20wedding%20devon%200013.jpg" alt="" width="100"/></span>
					<span class="detail">Some information</span>
					<span class="q">1</span>
					<span class="cost">&pound;15.00</span>
				</div>
			</div>
		</div>
	</div-->
</div>
<script type="text/javascript">/*<![CDATA[*/
    $(document).ready(function() {
                      $("a.mag").fancybox({'width':500,'height':500});
                      });
/*]]>*/</script>