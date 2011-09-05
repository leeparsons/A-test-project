<div class="title">
    <h2>Add a new product</h2>
</div>
<div class="relativewrapper">
    <div class="cartlarge">
        <form method="POST" action="<?php echo $action; ?>" class="central" enctype="multipart/form-data">
<div class="fl w100">
<?php
    
    if (empty($types)) {
        
        ?>
<span>Create your procut types first</span>
<span class="input"><a href="<?php echo $create; ?>">create types</a></span>
<?php
    
    } else {
        
        ?>
<label for="type">Select the type of product:</label>
<?php
    
    }
    
    ?>
</div>
            <div class="fl w100">
                <label for="name">Product Name:</label>
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
                <label for="email">Product Image:</label>
                <input type="text" name="image" id="image" value="<?php echo $image; ?>"/>
            </div>
            <div class="fl w100">
                <label for="frontimage">Choose an image for the client:</label>
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