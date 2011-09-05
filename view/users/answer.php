<div class="title">
    Forgotten Details Retreival Step 2
</div>
<div class="relativewrapper login">
    <div class="imagegallery tc">
        <p>To retrive your passsword please answer your security question:</p>
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
                <div class="fl w100">
                    <span>Question:</span>
                    <span><?php echo $question; ?></span>
                </div>
                <div class="w100 fl">
                    <label for="answer">Answer:</label>
                    <input type="text" name="answer" id="answer" value="<?php echo $answer; ?>" />
                </div>
                <div class="fl w100">
                    <input type="submit" class="fr" value="submit" name="submit" />
                </div>
                <input type="hidden" name="r" value="<?php echo $redirectUrl; ?>" />
                <input type="hidden" name="h" value="<?php echo $hash; ?>" />
                <input type="hidden" name="q" value="<?php echo $question; ?>" />
            </form>
        </div>
    </div>
</div>