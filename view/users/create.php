<div class="title">
    Create a new wish list
</div>
<div class="relativewrapper login">
    <div class="imagegallery tc">
        <form method="post" class="createcentral" action="<?php echo $action; ?>">
            <div class="fl w100">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" value="<?php echo $name; ?>"/>
            </div>
            <?php if ($cError['name']) { ?>
            <div class="fl w100">
                <span class="error">Please enter a unique name.</span>
            </div>
            <?php } ?>
            <div class="fl w100">
                <input type="submit" class="fr" value="create" />
            </div>
            <input type="hidden" name="referrer" value="<?php echo $returnUrl; ?>" />
        </form>
    </div>
</div>