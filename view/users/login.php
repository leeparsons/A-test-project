<div class="title">
    Login
</div>
<div class="relativewrapper login">
    <div class="border"></div>
    <div class="bigpicleft">
        <div class="bigpic">
            <form method="post" class="loginform" action="<?php echo $action; ?>">
<?php if ($error) { ?>
                <div class="fl w75">
                    <span class="error fr">Please make sure you have entered the correct information</span>
                </div>
<?php } ?>
                <div class="w75 fl">
                    <label for="email">Email address:</label>
                    <input type="text" name="email" id="email" value="<?php echo $email; ?>" />
                </div>
                <div class="fl w75">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password">
                </div>
                <div class="fl w75">
                    <input type="submit" class="fr" value="login" name="login" />
                </div>
                <input type="hidden" name="redirect" value="<?php echo $redirectUrl; ?>" />
                <div class="fr w75">
                    <a href="<?php echo $forgottenLink; ?>">Forgotten Your Password?</a>
                </div>
            </form>
        </div>
    </div>

    <div class="bigpicright">
        <div class="bigpic">
            <form method="post" action="<?php echo $register; ?>" class="registerone">
                <p>If you have not got an account please register first</p>
                <p><input type="submit" value="Click here to register" /></p>
                <input type="hidden" name="redirecturl" value="<?php echo $redirectUrl; ?>" />
            </form>
        </div>
    </div>
</div>