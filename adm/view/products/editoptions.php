<div class="title">
    Editing: <?php echo $option['name']; ?>
</div>
<div class="relativewrapper">
    <div class="border"></div>
    <div class="bigpicleft">
    <?php


    
    if (!empty($option)) {

        
    ?>
    

<form method="post" action="<?php echo $action; ?>" class="central">
    <div class="w100 fl">
        <span><a href="<?php echo $option['link']; ?>">cancel</a></span><input type="submit" value="save" name="save"/>
    </div>
    <input type="hidden" value="<?php echo $option['name']; ?>" name="name"/>
    <div class="fl w100">
        <label for="description">Description:</label>
        <textarea cols="30" rows="5" name="description" id="description"><?php echo $option['description']; ?></textarea>
    </div>
    <div class="fl w100">
        <span>Values:</span><input type="button" class="button" value="add a value" name="add" id="add"/>
    </div>
    <div class="fl w100 values">



            <?php
                
                if (!empty($option['values'])) {
                 
                    foreach ($option['values'] as $i => $v) {

                        ?>
                        <div class="fl w100">
                            <label for="option-value-<?php echo $i; ?>">Name:</label>
                            <input type="text" value="<?php echo $v['value']; ?>" id="option-value-<?php echo $i; ?>" name="option-value[<?php echo $i; ?>]"/>
                            </div>
                            
                            <div class="fl w100" style="border-bottom:1px solid #454545;padding-bottom:10px;margin-bottom:10px;">
                            <label for="option-cost-<?php echo $i; ?>">Cost: &pound;</label>
                            <input type="text" value="<?php echo str_replace('&pound; ','',$v['cost']); ?>" id="option-cost-<?php echo $i; ?>" name="option-cost[<?php echo $i; ?>]"/>
                        </div>
                        <input type="hidden" name="option-value-i[<?php echo $i; ?>]" value="<?php echo $i; ?>"/>
                        <input type="hidden" value="0" name="new-countoptions" id="new-countoptions"/>

                        <?php
                    }
                 
                    
                } else {
                      
                    
                ?>
                    <input type="hidden" value="1" name="new-option-value-i[1]"/>
                    <div class="fl w100">
                    <label for="new-option-value-1">Name:</label>
                        <input type="text" value="" id="new-option-value-1" name="new-option-value[1]"/>
                        </div>
                        <div class="fl w100">
                        <label for="new-option-cost-1">Cost: &pound;</label>
                    <input type="text" value="" id="new-option-cost-1" name="new-option-cost[1]"/>
                    </div>
                
                    <input type="hidden" value="1" name="new-countoptions" id="new-countoptions"/>



                <?php
                
                }
                
                ?>

        <input type="hidden" value="<?php echo count($option['values']); ?>" name="countoptions" id="countoptions"/>

    </div>
<div class="w100 fl">
    <span><a href="<?php echo $option['link']; ?>">cancel</a></span><input type="submit" value="save" name="save"/>
</div>
<input type="hidden" name="p" value="<?php echo $option['p']; ?>"/>
<input type="hidden" name="t" value="<?php echo $option['t']; ?>"/>
</form>
</div>
<div class="bigpicright">
    <div class="bigpic tc">
        <?php echo $option['image']; ?>
    </div>
</div>
        
    <?php
        
    }
        
        
        
    ?>
    </div>
</div>
<script type="text/javascript">/*<![CDATA[*/
$('#add').click(function () {
                var c = $('#new-countoptions').val()*1 + 1;
                $('#new-countoptions').val(c);
                $('.values').prepend('<input type="hidden" value="' + c + '" name="new-option-value-i[' + c + ']"/><div class="fl w100"><label for="new-option-value-' + c + '">Name:</label><input type="text" value="" id="new-option-value-' + c + '" name="new-option-value[' + c + ']"/></div><div style="border-bottom:1px solid #454545;padding-bottom:10px;margin-bottom:10px;" class="fl w100"><label for="new-option-cost-' + c + '">Cost: &pound;</label><input type="text" value="" id="new-option-cost-' + c + '" name="new-option-cost[' + c + ']"/></div>');                
                });

/*]]>*/</script>