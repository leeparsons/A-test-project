<div class="title">
<?php echo $cName; ?>&rsquo;s Galleries
</div>
<div class="relativewrapper">
    <div class="dn abslinks">
        <a href="">Login</a>
        <a href="" class="cartsummary">Cart Summary (0 items)</a>
    </div>
    <div class="border"></div>
        <div class="bigpicleft">
            <div class="bigpic tc">
                <div class="imageover"><div></div><span>&copy;</span></div>
                <?php echo $mainImage; ?>
            </div>
        </div>
        <div class="bigpicright">
            <div class="summary">
                <p><?php
                    
                    echo nl2br($cDescription);
                    
                    ?></p>
                <p>Click below to view images from the day.</p>
            </div>
            <div class="list">
                <?php
                    
                    if ($noGalleries === '') {
                        
                        foreach ($gallery as $g) {
                            echo '<p><a href="' . $g['url'] . '">' . ucfirst($g[2]) . ' Gallery: (' . $g[4] . ' images)</a>' . $g[3] . '</p>';
                        }
                        
                    } else {
                        echo $noGalleries;
                    }
                    
                    ?>
            </div>
        </div>
    </div>
</div>