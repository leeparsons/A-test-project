<div class="title">
    Add a New One Off Product
</div>
<div class="relativewrapper">
<?php if ($editing) { ?><div class="border"></div><?php } ?>
    <div class="<?php if ($editing) { ?>bigpicleft<?php } else { ?>cartlarge<?php } ?>">
        <form method="POST" action="<?php echo $action; ?>" class="central" enctype="multipart/form-data">
            <div class="fl w100">
                <input type="submit" value="create" name="create"/>
                <a href="../" class="button">Cancel</a>
            </div>

            <div class="fl w100">
                <label for="name">Product Name:</label>
                <input type="text" name="name" id="name" value="<?php echo $name; ?>"/>
                <?php
    
                    if (!empty($cError['name'])) {
                        echo '<p class="fr error">' . $cError['name'] . '</p>';
                    }
                    
                    ?>
            </div>
            <div class="fl w100">
                <label for="description">Description:</label>
                <textarea cols="30" rows="5" name="description" id="description"><?php echo $description; ?></textarea>
            </div>
            <div class="fl w100">
                <label for="image">Upload a new image:</label>
                <?php if ($editing) { ?>
                <input type="checkbox" class="fl" name="newimage"/>
                <?php } ?>
                <input type="file" name="image" id="image" />
<?php
    
    if (!empty($cError['image'])) {
        echo '<div class="fr"><p class="fr error">' . $cError['image'] . '</p></div>';
    }
    
    if ($editing) {
     
        ?>

                <input type="hidden" name="i" value="<?php echo $id; ?>" />
                <input type="hidden" name="origfile" value="<?php echo $origFile; ?>" />
<?php
        
    }
    
    ?>
            </div>
            <div class="fl w100">
                <label for="nocost">Client can enter a cost</label>
                <input type="checkbox" name="nocost" id="nocost" <?php if ($nocost) {echo 'checked="checked"';} ?> />
            </div>
            <div class="fl w100 costcontainer">
                <label for="cost">Cost: (&pound;)</label>
                <input type="text" name="cost" id="cost" value="<?php echo $cost; ?>" />
            </div>
            <div class="fl w100">
                <input type="submit" value="create" name="create"/>
                <a href="../" class="button">Cancel</a>
            </div>
        </form>
    </div>
    <?php if ($editing) { ?><div class="bigpicright"><div class="bigpic tc"><p>Current Product Image:</p><?php echo $pImage; ?></div></div></div><?php } ?>
</div>
<script type="text/javascript">/*<![CDATA[*/
$('#nocost').change(function () {$('.costcontainer').toggle();});
<?php if ($nocost) { ?>
$('.costcontainer').toggle();
<?php } ?>
/*]]>*/</script>