<div class="title">
    Register
</div>
<div class="relativewrapper login">
    <div class="imagegallery tc">
            <p>To create an account you will need to enter your name, email address and create a password with a reminder.</p>
            <p>Your email address is used to login so make sure you enter one which you can access.</p>
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
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" value="<?php echo $name; ?>" />
                </div>
                <div class="w100 fl">
                    <label for="email">Email address:</label>
                    <input type="text" name="email" id="email" value="<?php echo $email; ?>" />
                </div>



                <div class="fl w100">
                    <label for="password1">Password:</label>
                    <input type="password" name="password1" id="password1" value="<?php echo $password1; ?>" />
                </div>
                <div class="fl w100">
                    <label for="password2">Re&ndash;enter your password:</label>
                    <input type="password" name="password2" id="password2" value="<?php echo $password2; ?>" />
                </div>

                <div class="fl w100">
                    <label for="reminderq">Create a reminder question:</label>
                    <input type="text" name="reminderq" id="reminderq" value="<?php echo $reminderq; ?>" />
                </div>
                <div class="fl w100">
                    <label for="remindera">Create a reminder answer:</label>
                    <input type="text" name="remindera" id="remindera" value="<?php echo $remindera; ?>" />
                </div>
                <div class="fl w100">
                    <input type="submit" class="fr" value="login" name="login" />
                </div>
                <input type="hidden" name="redirect" value="<?php echo $redirectUrl; ?>" />
            </form>
        </div>
    </div>
</div>