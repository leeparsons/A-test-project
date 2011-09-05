<div class="title">
    Add a new product Option
</div>
<div class="relativewrapper">
    <div class="cartlarge">
        <?php 
            
            if (empty($types)) {
                
                echo '<p>' . $message . '</p>';
                
            } else {

                ?>


        <form method="POST" action="<?php echo $action; ?>" class="central" enctype="multipart/form-data">
            <div class="fl w100">
                <input type="submit" value="create" name="create"/>
            </div>

            <div class="fl w100">
                <label for="name">Product Option Name:</label>
                <input type="text" name="name" id="name" value="<?php echo $name; ?>"/>
                <?php
    
                    if (!empty($cError['name'])) {
                        echo '<span class="error">' . $cError['name'] . '</span>';
                    }
                    
                    ?>
            </div>
            <div class="fl w100">
                <label for="type">Select the Type of Product:</label>
                <select name="t" id="type">
                <?php
                    
                    foreach ($types as $type) {
                        
                        echo '<option value="' . $type['t'] . '">' . $type['name'] . '</option>';
                        
                    }
                    
                    ?>
                </select>
            </div>
            <div class="fl w100">
                <label for="description">Description:</label>
                <textarea cols="30" rows="5" name="description" id="description"><?php echo $description; ?></textarea>
            </div>
            <input type="hidden" value="<?php echo $p; ?>" name="p"/>

<div class="fl w100">
<span>Values:</span><input type="button" class="button" value="add a value" name="add" id="add"/>
</div>
<div class="fl w100 values">



<?php

    if (!empty($editOptions)) {
        
        foreach ($editOptions as $i => $v) {
            
            ?>
<div class="fl w100">
<label for="option-value-<?php echo $i; ?>">Name:</label>
<input type="text" value="<?php echo $v['name']; ?>" id="option-value-<?php echo $i; ?>" name="option-value[<?php echo $i; ?>]"/>
</div>

<div class="fl w100" style="border-bottom:1px solid #454545;padding-bottom:10px;margin-bottom:10px;">
<label for="option-cost-<?php echo $i; ?>">Cost: &pound;</label>
<input type="text" value="<?php echo str_replace('&pound; ','',$v['cost']); ?>" id="option-cost-<?php echo $i; ?>" name="option-cost[<?php echo $i; ?>]"/>
</div>
<input type="hidden" name="option-value-i[<?php echo $i; ?>]" value="<?php echo $i; ?>"/>

<?php
    }
    
    
    } else {
        
        
        ?>
<input type="hidden" value="1" name="option-value-i[1]"/>
<div class="fl w100">
<label for="option-value-1">Name:</label>
<input type="text" value="" id="option-value-1" name="option-value[1]"/>
</div>
<div class="fl w100">
<label for="option-cost-1">Cost: &pound;</label>
<input type="text" value="" id="option-cost-1" name="option-cost[1]"/>
</div>




<?php
    
    }
    
    
    
    ?>

<input type="hidden" value="<?php if (isset($option)) { echo (count($option['values']) == 0)?1:count($option['values']);} else {echo (int)0;} ?>" name="countoptions" id="countoptions"/>

</div>



            <div class="fl w100">
                <input type="submit" value="create" name="create"/>
            </div>
        </form>
    <?php
        
        
        }
        
        
        ?>
    </div>
</div>
<script type="text/javascript">/*<![CDATA[*/
$('#add').click(function () {
                var c = $('#countoptions').val()*1 + 1;
                $('#countoptions').val(c);
                $('.values').prepend('<input type="hidden" value="' + c + '" name="option-value-i[' + c + ']"/><div class="fl w100"><label for="option-value-' + c + '">Name:</label><input type="text" value="" id="option-value-' + c + '" name="option-value[' + c + ']"/></div><div style="border-bottom:1px solid #454545;padding-bottom:10px;margin-bottom:10px;" class="fl w100"><label for="option-cost-' + c + '">Cost: &pound;</label><input type="text" value="" id="option-cost-' + c + '" name="option-cost[' + c + ']"/></div>');                
                });

/*]]>*/</script>