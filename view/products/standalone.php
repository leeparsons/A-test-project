<div class="title">
    <?php echo $title; ?>
</div>
<div class="relativewrapper">
	<div class="border"></div>
    <div class="bigpicleft">
        <div class="bigpic tc">
            <?php echo $image; ?>
            <p class="name"><?php echo $name; ?></p>
        </div>
    </div>
    <div class="bigpicright">
        <div class="list bn">
            <form method="post" action="<?php echo $action; ?>" class="standalone">
                <div class="w100 fl">
                    <p><?php echo $description; ?></p>
                </div>
                <div class="w100 fl">
                    <p>To purchase this product, fill in any notes you need to add below, and then click on the button below to go to the payment page.</p>
                </div>
		<?php if ($cError !== '') { ?>
		<div class="fl w100">
			<p class="standalone-error">Please enter your name</p>
		</div>
		<?php } ?>
		<div class="fl w100">
			<label for="name">Name:</label>
			<input type="text" name="name" id="name" value="<?php echo $clientName; ?>" />
		</div>
                <?php if (!$nocost) { ?>
                <div class="fl w100">
                    <span class="left">Cost:</span>
                    <span class="right">&pound; <?php echo $cost; ?></span>
                </div>
		<input type="hidden" name="amount" value="<?php echo $cost; ?>" />
                <?php } else { ?>
		<?php if ($customAmountError !== '') { ?>
		<div class="fl w100">
			<p class="standalone-error">Please enter an amount</p>
		</div>
		<?php } ?>
                <div class="fl w100">
                    <span class="left">Enter the amount to pay:</span>
                    <span class="right">&pound; <input type="text" size="5" name="amount" value="<?php echo $customAmount; ?>" /></span>
                </div>
                <?php } ?>
                <div class="w100 fl">
                    <label for="notes">Notes:</label>
                    <textarea cols="17" rows="7" name="notes" id="notes"></textarea>
                </div>
                <div class="fl w100">
                    <input type="submit" name="checkout" value="Go to Checkout" />
                </div>
                <input type="hidden" name="tkn" value="<?php echo $tkn; ?>"/>
                <input type="hidden" name="i" value="<?php echo $id; ?>" />
                <input type="hidden" name="currency" value="GBP" />
                <input type="hidden" name="description" value="<?php echo $description; ?>" />
            </form>
        </div>
    </div>
</div>