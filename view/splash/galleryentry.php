<div class="title">
    <?php echo $title; ?>
</div>
<div class="relativewrapper">
    <div class="dn abslinks">
        <a href="">Login</a>
        <a href="" class="cartsummary">Cart Summary (0 items)</a>
    </div>
    <div class="border"></div>
    <div class="bigpicleft">
        <div class="bigpic tc">
            <div class="imageover"><div></div><span>&copy;</span></div>
            <?php echo $mainImage; ?>
        </div>
    </div>
    <div class="bigpicright">
        <div class="summary">
        <p><?php
    
            echo nl2br($message);
    
            ?></p>
        </div>           
        <div class="list">
            <?php if ($displayForm) { ?>
            <form method="post" class="entry" action="<?php echo $action; ?>">
                <div class="fl w100">
                    <label for="pw" class="w100 fl">Password:</label>
                </div>
                <div class="fl w100">
                    <input type="password" name="pw" id="pw" />
                </div>
                <div class="fl w100">
                    <input type="submit" value="enter"/>
                </div>
                <input type="hidden" name="g" value="<?php echo $gid; ?>" />
                <input type="hidden" name="c" value="<?php echo $cid; ?>" />
            </form>
            <?php } ?>
        </div>
    </div>
</div>