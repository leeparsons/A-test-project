<div class="title">
    <p>Please login to use your management control panel:</p>
    <form method="post" action="<?php echo $action; ?>" class="login">
        <div class="fl w100">
            <label for="username">User name:</label><input id="username" type="text" name="usr" value="<?php echo $usr; ?>"/>
        </div>
        <div class="fl w100">
            <label for="password">Password:</label>
            <input type="password" name="pss" value=""/>
        </div>
        <div class="fl w100">
            <input type="submit" value="Login" name="login"/>
        </div>
    </form>
</div>