<div class="title">
    Create a new gallery for the client: <?php echo $name; ?>
</div>
<div class="relativewrapper">    
<form method="POST" action="<?php echo $action; ?>" class="nm central" enctype="multipart/form-data">
    <div class="border"></div>
    <div class="bigpicleft">
            <div class="fl w100">
                <span>Gallery Name:</span>
                <input name="name" type="text" id="name" value="<?php echo $gname; ?>" />
            </div>
            <?php
                
                if (!empty($cError['name'])) {
                    
                    echo '<div class="fl w100"><span class="error">' . $cError['name'] . '</span></div>';
                }
                
                ?>
            <?php
                
                if ($seoUrls == 1) {
                
                ?>
            <div class="w100 fl">
                <label for="seorul">SEO URL:</label>
            </div>
            <div class="fl w100">
                <span><?php echo $seoUrlBase; ?></span>
                <input type="hidden" value="<?php echo $seoBase; ?>" name="seobase"/>
                <input type="text" name="seourl" id="seourl" value="<?php echo $seoUrl; ?>" />
            </div>
            <?php
                
                if (!empty($cError['seoUrl'])) {
                    
                    echo '<span class="error">' . $cError['seoUrl'] . '</span>';   
                    
                }
                
                ?>
            </div>
<script type="text/javascript">/*<![CDATA[*/
$('#name').keyup(function () {
                 var str = $(this).val().replace(/\s/g, '-');
                 $('#seourl').val(str.toLowerCase());
                 });

/*]]>*/</script>
            <?php
                
                }
                
                ?>
            <div class="fl w100">
                <label for="blog">Blog url:</label>
                <input type="text" name="blog" id="blog" value="<?php echo $blog; ?>"/>
            </div>
<?php
    
    if (!empty($cError['blog'])) {
        
        echo '<div class="fl w100"><span class="error">' . $cError['blog'] . '</span></div>';
    }
    
    ?>
            <div class="fl w100">
                <label for="pword">Password:</label>
                <input type="text" value="<?php echo $pword; ?>" name="pword" id="pword"/>
            </div>
            <div class="fl w100">
                <span>Set the gallery to active:.</span>
                <input type="checkbox" class="fl" name="active" <?php if ($activate === true) { echo 'checked="checked"'; } ?>/>
            </div>
            <div class="fl w100">
                <span>Set an expiry date:</span>
                <input type="text" value="<?php echo $expiry; ?>" name="expiry" id="expiry" />
            </div>
            <div class="fl w100">
                <span>Never Expire</span>
                <input type="checkbox" class="fl" name="indef" <?php if ($indef === true) { echo 'checked="checked"';} ?>/>
            </div>
            <!--div class="w100 fl">
                <!--label for="zip">Upload a zip file of images</label>
                <!--input type="file" name="zip" id="zip" />
            </div-->
            <div class="w100 fl">
                <input type="hidden" value="<?php echo $cid; ?>" name="c"/>
                <input type="submit" value="create" />
            </div>
    </div>
    <div class="bigpicright">
        <div class="bigpic tc">
            <?php

                
                
                //get all the information about the cart!

                if (!empty($options)) {
                
                    
                    ?>
                        <p>Select the cost options that should be listed against this product:</p>
<div class="fl w100">
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
</form>
</div>
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
       dateFormat: 'MM dd yy',
       firstDay: 1,
       isRTL: false,
       showMonthAfterYear: false,
       yearSuffix: ''};
       $.datepicker.setDefaults($.datepicker.regional['en-GB']);
       });


/*]]>*/</script>