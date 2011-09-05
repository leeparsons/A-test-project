<div class="title">
    Forgotten Details Retreival Step 1
</div>
<div class="relativewrapper login">
    <div class="imagegallery tc">
            <p>To retrieve your passsword please enter the email address you used to register.</p>
           <form method="post" class="register" action="<?php echo $action; ?>">
<?php
    
    if (!empty($error)) {

    
        foreach ($error as $e) {
    
    ?>
                <div class="fl w100">
                    <span class="error fr"><?php echo $e; ?></span>
                </div>
<?php
    
       
        } 
    }
    
    
    ?>
                <div class="w100 fl">
                    <label for="email">Email address:</label>
                    <input type="text" name="email" id="email" value="<?php echo $email; ?>" />
                </div>
                <div class="fl w100">
                    <input type="submit" class="fr" value="submit" name="submit" />
                </div>
                <input type="hidden" name="r" value="<?php echo $redirectUrl; ?>" />
            </form>
        </div>
    </div>
</div>