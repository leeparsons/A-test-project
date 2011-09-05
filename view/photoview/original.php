<div class="title">
    <?php echo $title; ?>
</div>
<div class="relativewrapper">
	<div class="border"></div>
    <div class="bigpicleft">
        <div class="bigpic tc">
            <div class="imageover"><div></div><span>&copy;</span></div>
            <?php echo $img; ?>
            <div class="mag"><a href="<?php echo $fancy; ?>"></a></div>
            <p class="name"><?php echo $descriptiveName; ?></p>
        </div>
        <div class="picoptions">
            <ul class="optnul">
                <li><a <?php if (empty($li)) { ?>class="active"<?php } ?> href="" id="orderwish">Wish List</a></li>
                <?php if (!empty($li)) {


                        foreach ($li as $i => $liItem) {

                            ?><li><a href="" id="order<?php echo strtolower(str_replace(' ', '-', $liItem)); ?>"<?php if ($i == 0) {echo ' class="active"';} ?>>Order <?php echo $liItem; ?></a></li><?php
                        
                            }
                        }
                    ?>
                <li class="fr">
                    <a href="<?php echo $cartLink; ?>"></a>
                </li>
            </ul>
            <form method="post" action="<?php echo $wishAction; ?>">
                <div class="optionshowhide orderwish <?php if (!empty($li)) { echo 'dn';} ?>">
                    <div class="w100 fl">
                        <?php

                            //figure out if the users logged in, if not then they have to log in!

                            if (!$loggedIn) {
                            
                            ?>
                        <p>To add items to your wish list you must be logged in.</p>
                        <p><a href="<?php echo $loginLink; ?>?redirectUrl=<?php echo $redirectUrl; ?>">Click here to login</a></p>
                        <?php
    
                            } else {
                                
                             
                                //cycle through their wish lists.
                                
                                
                                if (empty($wishLists)) {
                                    
                                    echo '<p>You currently have no wish lists. <a href="' . $wishUrl . '">Click here to create one.</a></p>';   
                                    
                                } else {

                                    ?>
                                    <p>Select a wish list to add this item to:</p>
                                <div class="wishpreview">
                                <?php
                                    
                                    foreach ($wishLists as $list) {
                                     
                                        ?>
                                        <div class="fl w100">
                                            <input type="checkbox" id="check<?php echo $list['name']; ?>" name="<?php if ($list['inList']) { echo 'remove'; } ?>wishes[]" value="<?php echo $list['id']; ?>"/>
                                            <label for="check<?php echo $list['name']; ?>"><?php echo stripslashes($list['name']); ?> <?php if ($list['inList']) { ?>(This image is already in this list. Select to remove.)<?php }?></label>
                                        </div>
                                    <?php
                                        
                                    }
                                            
                                        ?>
                                    <div class="w100 fl">
                                        <input type="submit" value="update wish lists" />
                                        <input type="hidden" value="<?php echo $itm; ?>" name="itemID" id="itemID"/>
                                        <input type="hidden" value="<?php echo $redirectUrl; ?>" name="redirectUrl"/>
                                    </div>
                                </div>
                                        <?php
                                    
                                }
                                
                            }
    
                            ?>

                    </div>
                </div>
            </form>
            <?php 
                if (!empty($li)) {

                    $oCount = (int)0;
                foreach ($options as $pType => $option) {
                    if ($oCount > 0) {
                        $extraClass = ' dn';   
                    } else {
                        $oCount = (int)1;
                        $extraClass = '';   
                    }
                    $tag = strtolower(str_replace(' ', '-', $pType));
                    
?>
            <form method="post" id="addcart<?php echo $tag; ?>" action="<?php echo $cartAddLink; ?>">
            <div class="order<?php echo $tag . $extraClass; ?> optionshowhide">
                <div class="w75">
                    <div>
                        <label for="quantity">Quantity:</label>
                        <input type="text" id="quantity" name="quantity" value="1" size="3"/>
                    </div>
                    <div class="right">
                    <?php
                        foreach ($option as $prod => $products) {
                            ?>
                        <label for="<?php echo $prod; ?>"><?php echo $prod; ?>:</label>
                        <select name="options[<?php echo $pType; ?>][<?php echo $prod; ?>]]" id="<?php echo $prod; ?>">
                            <?php foreach ($products as $n => $product) { ?>
                                <option value="<?php echo $n . '__' . $product; ?>"><?php echo $n . ': &pound;' . $product; ?></option>
                            <?php } ?>
                        </select>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="w75">
                    <label for="notes">Notes:</label>
                    <textarea cols="39" rows="5" id="notes" name="notes"></textarea>
                </div>
                <div class="w25">
                    <input type="submit" value="Add to cart" href="/addtocart/" class="addtocart"/>
                    <a href="<?php echo $cartLink; ?>" class="gotocheckout">Go to Checkout</a>
                </div>
            </div>
                <input type="hidden" value="<?php echo $pType; ?>" name="pname" id="pname"/>
                <input type="hidden" value="<?php echo $token; ?>" name="tkn" id="tkn"/>
                <input type="hidden" value="<?php echo $itm; ?>" name="itemID" id="itemID"/>
                <input type="hidden" value="<?php echo $descriptiveName; ?>" name="pname" id="pname"/>
                <input type="hidden" value="<?php echo $ci; ?>" name="ci" id="ci"/>
                <input type="hidden" value="<?php echo $g; ?>" name="g" id="g"/>
                <input type="hidden" value="<?php echo $p; ?>" name="p" id="p"/>
                <input type="hidden" value="<?php echo $cName; ?>" name="cname" id="cname"/>
                <input type="hidden" value="<?php echo $gName; ?>" name="gname" id="gname"/>
                <input type="hidden" value="<?php echo $type; ?>" name="type" id="type"/>
                <input type="hidden" value="<?php echo $imageName; ?>" name="imagename" id="imagename"/>
            </form>
                
<?php
                    
                }
            }
                ?>
    </div>
	<script type="text/javascript">/*<![CDATA[*/
						$('.optnul').find('a').click(function () {
							if (!$(this).hasClass('active')) {
								$('.optnul').find('a').removeClass('active');
								$(this).addClass('active');
								$('.optionshowhide').css('display','none');
								$('.' + $(this).attr('id')).css('display','block');
							}
                                                     return false;
						});
	/*]]>*/</script>
</div>
<div class="bigpicright">
	<div class="galleryinner">
		<ul class="agaller" id="gallerynav">
           <?php
                               
                                foreach ($imgNav as $key => $val) {
                                    
                                    echo '<li ' . $val['class'] . '><a ' . $val['class'] . ' href="' . $val['href'] . '" style="background:url(\'' . str_replace(' ', '%20', $val['img']) . '\') no-repeat center center;"></a></li>';
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

<?php
    
    //if the user is logged in and has wish lists show the wish lists
    
    if ($loggedIn) {
        if (!empty($wishLists)) {
     ?>   
    <div class="cartcurrent">
        <ul class="cartpreview">
            <li><a href="">Your Wish Lists</a></li>

        </ul>
        <div class="cartpreview">
            <div class="thead">
                <span class="image">Name</span>
                <span class="detail">Detail</span>
                <span class="q">&nbsp;</span>
                <span class="cost">&nbsp;</span>
            </div>
            <div class="tbody scroll-pane">
                <?php

                    foreach ($wishLists as $list) {
                    
                    ?>
                <div class="fl">
                    <span class="b1"></span>
                    <span class="b2"></span>
                    <span class="b3"></span>
                    <span class="image"><a href="<?php echo $list['view']; ?>"><?php echo $list['name']; ?></a></span>
                    <span class="detail">Number of items: <?php echo $list['count']; ?></span>
                    <span class="q"><a href="<?php echo $list['view']; ?>">view</a></span>
                    <span class="cost"><a href="<?php echo $list['delete']; ?>">delete</a></span>
                </div>
                <?php

                    }
                    
                    ?>
            </div>

        </div>
    </div>
       <?php 
    
           }
           


        }
    
    ?>


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
<script type="text/javascript">
$('.mag a').fancybox();
</script>