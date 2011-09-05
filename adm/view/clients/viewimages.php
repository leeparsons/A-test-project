<div class="title">
    Viewing Images for: <?php echo $client . '&rsquo;s ' . $name; ?> Gallery
</div>
<div class="relativewrapper">
    <div class="cartlarge">
    <form method="post" action="<?php echo $action; ?>" class="tc">

        <table class="center" cellspacing="0">
            <thead>
                <tr>
                    <th class="first bd">Preview</th><th class="bd">Name</th><th class="last">Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    
                    if (!empty($images)) {
                    
                        echo '<tr><td class="first last" colspan="3">' . $pagination . '</td></tr>';

                        echo '<tr><td class="first last" colspan="3"><input class="button" type="submit" value="Delete Selected"/></td></tr>';

                        
                        echo '<tr><td class="first bd" colspan="2"></td><td class="last checker">Select All/ Unselect All<input type="hidden" value="0" id="checker" name="checker"/></td></tr>';

                        
                        foreach ($images as $image) {
                         
                            echo '<tr class="content">';
                            
                            echo '<td width="150" class="bd content first">' . $image['img'] . '</td>';
                            
                            echo '<td class="bd content">' . $image['name'] . '</td>';

                            echo '<td width="150" class="last"><input type="checkbox" name="delete[' . $image['id'] . ']"/></td>';

                            echo '</tr>';
                        }
                        

                        echo '<tr><td class="first last" colspan="3"><input type="submit" value="Delete Selected"/></td></tr>';
                        echo '<tr><td class="first last" colspan="3">' . $pagination . '</td></tr>';

                        
                    } else {
                     
                        ?>
                <tr>
                    <td colspan="3">There are no images recorded for this gallery</td>
                </tr>
                <?php
                        
                    }

                    ?>
            </tbody>
        </table>
        <input type="hidden" value="<?php echo $cid; ?>" name="c"/>
        <input type="hidden" value="<?php echo $g; ?>" name="g"/>
        <input type="hidden" value="<?php echo $p; ?>" name="p"/>
    </form>
    </div>
</div>
<script type="text/javascript">/*<![CDATA[*/
$('td.content').click(function () {var t = $(this).parent('tr').find('input');if (t.attr('checked') == true) {t.attr('checked', false);} else {t.attr('checked', true);}});
$('td.checker').click(function () {var c = $(this).find('input').eq(0).val(); if (c == 0) {$('tr.content').find('input').attr('checked', true);$(this).find('input').eq(0).val(1);} else {$(this).find('input').eq(0).val(0);$('tr.content').find('input').attr('checked', false);};});
/*]]>*/</script>