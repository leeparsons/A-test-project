<div class="title">
    <?php echo $title; ?>
</div>
<div class="relativewrapper">
	<div class="border"></div>
    <div class="bigpicleft">
        <div class="bigpic">
            <a href="<?php echo $fancy; ?>" class="mag"></a>
            <?php echo $img; ?>
        </div>
    <?php 
                        
    if (!empty($options)) {
                    
    ?>

        <div class="picoptions">
            <ul class="optnul">

    <?php

        $liStr = '';
        $k = (int)0;
        
        foreach ($options as $i => $opt) {
            if ((int)$k == 0) {
                $liStr .= '<li><a class="active" id="' . strtolower(str_replace(' ', '-', $i)) . '">' . $i . '</a></li>';
            } else {
                $liStr .= '<li><a id="' . strtolower(str_replace(' ', '-', $i)) . '">' . $i . '</a></li>';
            }
            
        }
        
        
        echo $liStr;
        print_r($options);
            

        ?>
        
            </ul>
        </div>
        <form method="post" id="addcart" action="<?php echo $cartAddLink; ?>">
            <?php
                
                foreach ($options as $i => $opt) {
                
                ?>
        <div class="<?php echo strtolower(str_replace(' ', '-', $i)); ?>showhide photooption <?php echo strtolower(str_replace(' ', '-', $i)); ?>">
            <div class="w75">
                <div>
                    <label for="quantity">Qnty</label>
                    <input type="text" id="quantity" name="quantity" value="1" size="3"/>
                </div>
            </div>
        </div>
            <?php
                
                }
                
                ?>
        </form>
    <?php
        
        }
        
        ?>
    </div>
</div>
