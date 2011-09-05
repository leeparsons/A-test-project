<div class="title">
    <h2>Add a new client</h2>
</div>
<div class="relativewrapper">
    <div class="cartlarge">
        <form method="POST" action="<?php echo $action; ?>" class="central" enctype="multipart/form-data">
            <div class="fl w100">
                <label for="name">Client Name:</label>
                <input type="text" name="name" id="name" value="<?php echo $name; ?>"/>
                <?php
                    
                    if (!empty($cError['name'])) {
                        echo '<span class="error">' . $cError['name'] . '</span>';
                    }
                    
                    
                    ?>
            </div>
<?php if ($seoUrls == 1) { ?>
            <div class="fl w100">
            
                <label for="seourl">SEO URL:</label>
                <input type="text" id="seourl" name="seourl" value="<?php echo $seoUrl; ?>" />
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
<label for="description">Description:</label>
<textarea cols="30" rows="5" name="description" id="description"><?php echo $description; ?></textarea>
</div>
<div class="fl w100">
<label for="email">Email Address:</label>
<input type="text" name="email" id="email" value="<?php echo $email; ?>"/>
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
    <label for="splash">Upload a new Splash Image:</label>
    <input type="file" name="splash" id="splash"/>
</div>
<div class="fl w100">
    <input type="submit" value="create" name="create"/>
</div>
        </form>
    </div>
</div>