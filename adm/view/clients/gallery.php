<div class="title">
    Editing: <?php echo $gallery['client'] . '&rsquo;s ' . $gallery['name']; ?> gallery
</div>
<div class="relativewrapper">
    <div class="border"></div>
    <form method="post" action="<?php echo $action; ?>" class="central nm" enctype="multipart/form-data">
    <div class="bigpicleft">
            <div class="fl w100">
                <p><a href="<?php echo $addMoreImagesLink; ?>">Add More Images</a></p>
            </div>
            <div class="fl w100">
                <p><a href="<?php echo $viewImagesLink; ?>">View Images</a></p>
            </div>
            <div class="fl w100">
                <span>Gallery Name:</span>
                <span class="input"><?php echo $gallery['name']; ?></span>
            </div>
            <?php
                if ($seoUrls == 1) {
                    ?>
            <div class="w100 fl">
                <label for="seourl">SEO URL:</label>
            </div>
            <div class="w100 fl">
                <span><?php echo $seoUrlBase; ?></span>
                <input type="text" name="seourl" id="seourl" value="<?php echo $seoUrl; ?>" />
                <input type="hidden" value="<?php echo $seoBase; ?>" name="seobase" />
            <?php
                
                if (!empty($cError['seoUrl'])) {
                    echo '<span class="error">' . $cError['seoUrl'] . '</span>';
                }
                ?>
            </div>
            <?php
                }
                ?>
            <div class="fl w100">
                <label for="blog">Blog url:</label>
                <input type="text" name="blog" id="blog" value="<?php echo $gallery['blog']; ?>"/>
            </div>
            <div class="fl w100">
                <span>Total images:</span><span class="input"><span><?php echo $gallery['total']; ?></span> <a id="scan" href="<?php echo $reScan; ?>">Rescan</a></span>
            </div>
            <div class="fl w100">
                <label for="pword">Password:</label>
                <input type="text" value="<?php echo $gallery['pword']; ?>" name="pword" id="pword"/>
            </div>
            <div class="fl w100">
                <label for="activate">Is gallery live?</label>
                <input type="checkbox" class="fl" name="activate" <?php if ($gallery['activate'] !== 'activate') { echo 'checked="checked"';} ?> id="activate"/>
            </div>
            <div class="fl w100">
                <label for="expiry">Expiry Date:</label>
                <input type="text" name="expiry" id="expiry" value="<?php echo $gallery['expiry']; ?>"/>
            </div>
            <div class="fl w100">
                <label for="expiry">Never expires:</label>
                <input <?php if ($gallery['noexpiry'] === true) { echo 'checked="checked"';} ?> type="checkbox" class="fl" name="never" />
            </div>
            <div class="fl w100">
                <input type="submit" value="update" name="update"/>
                <a href="<?php echo $gallery['deleteLink']; ?>" class="button" onclick="return confirm('Click ok to permanently delete this gallery and all its images');">Delete</a>
            </div>
            <input type="hidden" value="<?php echo $cidentify; ?>" name="c"/>
    </div>
    <div class="bigpicright">
        <div class="bigpic tc">
            <?php

                //get all the information about the cart!
                
                if (!empty($options)) {
                    
                    
                    ?>
<p>Select the cost options that should be listed against this product:</p>
<div class="fl w100 tc">
<table cellspacing="0" class="priceoptions">
<?php
    
    foreach ($options as $k => $oArr) {
        $checked = '';
        if (!empty($optionsChecked)) {
            if (in_array($oArr['p'], $optionsChecked)) {
                $checked = ' checked="checked" ';   
            }
        }
        
        ?>

<tr>
<td><input <?php echo $checked; ?> type="checkbox" name="p[]" value="<?php echo $oArr['p']; ?>"/></td>
<td><span><?php echo $oArr['type']; ?>: <?php echo $oArr['name']; ?></span><td>
<td><?php
    
    
    if (!empty($oArr['costOptions'])) {
        echo '<select class="fr">';
        
        foreach ($oArr['costOptions'] as $ok => $o) {
            echo '<option>' . $ok . ' &ndash;' . $o . '</option>';
            
        }
        echo '</select>';
        
    }
    
    
    ?></td></tr><tr><td colspan="3">&nbsp;</td></tr><?php
        
        
        }
        ?>
</table>
</div>

<?php
    
    } else {
        
        ?><p>There are no cost options available in the cart</p><?php    
            
            }
            
            
            ?>
        </div>
    </div>
<input type="hidden" name="g" value="<?php echo $g; ?>"/>
</form>
</div>


<script type="text/javascript">/*<![CDATA[*/
$('#scan').click(function () {
                 
                 if (!$(this).hasClass('scanning')) {

                    $(this).addClass('scanning');

                    $(this).html('<img src="<?php echo $loader; ?>" alt="scanning"/>').prev('span').html('scanning: ');
                    
                    $.post('<?php echo $reScanFlat; ?>',
                           {<?php echo $reScanParams; ?>},
                           function (data) {
                           
                           if (data == 'false') {
                                alert('There was an error scanning your images. Browse directly to: <?php echo $reScan; ?>');
                           } else {
                           
                                $('#scan').html('Rescan').removeClass('scanning').prev('span').html(data);
                           
                           }
                           
                           }
                           );
                 }
                 return false;
                 });

$(function() {
  $( "#expiry" ).datepicker();
  });

 jQuery(function($){
       $.datepicker.regional['en-GB'] = {
       closeText: 'Done',
       prevText: 'Prev',
       nextText: 'Next',
       currentText: 'Today',
       monthNames: ['January','February','March','April','May','June',
       'July','August','September','October','November','December'],
       monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
       'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
       dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
       dayNamesShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
       dayNamesMin: ['Su','Mo','Tu','We','Th','Fr','Sa'],
       weekHeader: 'Wk',
       dateFormat: 'd MM yy',
       firstDay: 1,
       isRTL: false,
       showMonthAfterYear: false,
       yearSuffix: ''};
       $.datepicker.setDefaults($.datepicker.regional['en-GB']);
       });


/*]]>*/</script>