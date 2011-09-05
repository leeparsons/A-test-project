<div class="title">
    Editing product type: <?php echo $type['name']; ?>
</div>
<div class="relativewrapper">
    <div class="border"></div>
    <div class="bigpicleft">
        <form method="POST" action="<?php echo $action; ?>" class="central" enctype="multipart/form-data">
            <input type="hidden" name="t" value="<?php echo $t; ?>" />
            <input type="hidden" name="section" value="1"/>
            <input type="hidden" name="how" value="1"/>
            <div class="fl w100">
                <label for="name">Product Type Name:</label>
                <input type="text" name="name" id="name" value="<?php echo $type['name']; ?>"/>
                <?php
    
                    if (!empty($cError['name'])) {
                        echo '<span class="error">' . $cError['name'] . '</span>';
                    }
                    
                    ?>
            </div>
            <div class="fl w100">
                <label for="description">Description:</label>
                <textarea cols="30" rows="5" name="description" id="description"><?php echo $type['description']; ?></textarea>
            </div>
            <div class="fl w100">
                <label for="where">Which section products will appear in</label>
                <span class="input"><?php echo $type['section'] ;?></span>
            </div>
            <?php
                
                if ($type['how'] !== '') {
                
                ?>
            <div class="fl w100">
                <label for="where">Items will be added:</label>
                <span class="input"><?php echo $type['how'] ;?></span>
            </div>
            <?php
                
                }
                
                ?>
            <div class="fl w100">
                <label for="newimage">Upload a new image:</label>
                <input type="checkbox" name="uploadnew" class="fl" id="uploadnew" />
                <input type="file" name="newimage" id="newimage" />
            </div>
            <input type="hidden" name="origfile" value="<?php echo $type['imagename']; ?>"/>
            <div class="fl w100">
                <input type="submit" value="save" name="create"/>
            </div>
        </form>
    </div>
    <div class="bigpicright">
        <div class="bigpic tc">
            <?php echo $type['image']; ?>
        </div>
    </div>
</div>
<script type="text/javascript">/*<![CDATA[*/
$('#uploadnew').change(function () {

                       $('#newimage').toggle();
                       
                    
                      
                       }).next('input').css({display:'none'});
/*]]>*/</script>