<div class="title">
    Share Wish List: <?php echo $title; ?>
</div>
<div class="relativewrapper login">
    <div class="imagegallery tc">
        <p class="w75"><a href="<?php echo $wishListsLink; ?>">View all wish lists</a></p>
        <div class="w100 fl">
        <?php

                if (empty($name)) {
                 
                    echo '<p>This wish list no longer exists.</p>';
                    
                } else {
                 
                    
                        
                     ?>
                        <form method="post" class="divlayout createcentral" action="<?php echo $action; ?>">
                            <div class="w100 fl">
                                <p class="message">Enter the email address and the name of the person you want to share your wish list with.</p>
                            </div>
                            <div class="w100 fl">
                                <label for="name">Recipients Name:</label>
                                <input type="text" name="name" id="name" size="27" value="<?php
                                    
                                    echo $recipient;
                                    
                                    ?>" />
                            </div>
<div class="fl w100">
<label for="storeowner">Share with store owner</label>
<input type="checkbox" name="storeowner" id="storeowner" /> (No need to fill in the email address)
</div>
<?php
    
    if (!empty($cError['email'])) {
        
        ?>

<div class="w100 fl">

<span class="error"><?php echo $cError['email']; ?></span>
</div>
<?php
    }
    
    ?>
                            <div class="w100 fl">
                                <label for="email">Email address:</label>
                                <input type="text" name="email" id="email" size="27" value="<?php
                                    
                                    echo $emails;
                                    
                                    ?>" />
                            </div>
                            <div class="w100 fl">
                                <label for="notes">Notes:</label>
                                <textarea cols="24" rows="5" name="notes" id="notes"><?php
                                    
                                    echo $notes;
                                    
                                    ?></textarea>
                            </div>

                            <div class="fl w100">
                                <input type="submit" class="fr" value="send" />
                            </div>
                            <input type="hidden" value="<?php echo $w; ?>" name="w" />
                        </form>
                       <?php 
                    }
                    
                    
                
                
            ?>
        </div>
    </div>
</div>