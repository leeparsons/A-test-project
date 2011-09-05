<div class="title">
    Add a new product type
</div>
<div class="relativewrapper">
    <div class="cartlarge">
        <form method="POST" action="<?php echo $action; ?>" class="central" enctype="multipart/form-data">
            <div class="fl w100">
                <label for="name">Product Type Name:</label>
                <input type="text" name="name" id="name" value="<?php echo $name; ?>"/>
                <?php
    
                    if (!empty($cError['name'])) {
                        echo '<span class="error">' . $cError['name'] . '</span>';
                    }
                    
                    ?>
            </div>
            <div class="fl w100">
                <label for="description">Description:</label>
                <textarea cols="30" rows="5" name="description" id="description"><?php echo $description; ?></textarea>
            </div>
            <div class="fl w100">
                <label for="where">Choose which section products should appear in</label>
                <select name="section" id="where">
                    <option value="1" <?php if ($section == 1) {echo 'selected';} ?>>Galleries</option>
                    <option value="2" <?php if ($section == 2) {echo 'selected';} ?>>Product Shop (one off purchase)</option>
                </select>
            </div>
            <div class="fl w100" id="choosehow" <?php if ($section == 2) {echo 'style="display:none"';} ?>>
                <label for="how">Choose how items should be added</label>
                <select name="how" id="how">
                    <option value="1" <?php if ($how == 1) {echo 'selected';} ?>>Individually (as in a single print)</option>
                    <option value="2" <?php if ($how == 2) {echo 'selected';} ?>>Collection (as in images for an album)</option>
                </select>
            </div>
            <div class="fl w100">
                <label for="frontimage">Choose an image for the product type:</label>
                <input type="file" name="image" id="frontimage"/>
                <?php
    
                    if (!empty($cError['image'])) {
                        echo '<span class="error">' . $cError['image'] . '</span>';
                    }
                    
                ?>
            </div>
            <div class="fl w100">
                <input type="submit" value="create" name="create"/>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">/*<![CDATA[*/
$('#where').change(function () {if ($('#where option:selected').val() == 1) {$('#choosehow').removeAttr('style');} else {$('#choosehow').attr('style','display:none');}});
/*]]>*/</script>