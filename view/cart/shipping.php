<div class="carttitle">
    <?php echo $title; ?>
    <img class="fr" src="<?php echo $cards; ?>" alt="payment icons" height="35">
    <span class="fr">We accept: </span>
</div>
<div class="relativewrapper">
    <div class="cartlarge tc">
        <div class="shippingbox">
            <div class="boxinner">
            <p class="description">Please enter your shipping details:</p>
            <form method="post" action="<?php echo $action; ?>">
            <table class="shipping">
                <tbody>
                    <?php
                        if ($nameError !== '') {
                        ?>
                    <tr>
                        <td colspan="2"><span class="error"><?php echo $nameError; ?></span></td>
                    </tr>
                    <?php
                        }
                        ?>
                    <tr>
                        <td valign="top"><label for="recipient-name">Recipient Name:</label></td>
                        <td><input type="text" name="rname" id="recipient-name" value="<?php echo $rName; ?>"/></td>
                    </tr>
<?php
    if ($addressError !== '') {
        ?>
<tr>
<td colspan="2"><span class="error"><?php echo $addressError; ?></span></td>
</tr>
<?php
    }
    ?>

                    <tr>
                        <td valign="top"><label for="recipient-address">Recipient Address:</label></td>
                        <td><textarea cols="25" rows="5" name="rAddress" id="recipient-address"><?php echo $rAddress; ?></textarea></td>
                    </tr>
                    <tr>
                        <td valign="top"><label for="special-instructions">Enter any Special Instructions</label></td>
                        <td><textarea cols="25" rows="5" name="sinstructions" id="special-instructions"><?php echo $sInstructions; ?></textarea></td>
                    </tr>
                    <tr>
                        <td colspan="2"><input type="submit" value="Continue to payment" /></td>
                    </tr>
                </tbody>
            </table>
            <input type="hidden" name="desc" value="<?php echo $cartDetail; ?>" />
            <input type="hidden" name="amount" value="<?php echo $total; ?>" />
            <input type="hidden" name="currency" value="GBP" />
            <input type="hidden" name="vat" value="0.00" />
            <input type="hidden" name="shipping" value="1"/>
            </form>
    </div>
        </div>
    </div>
    </div>
</div>
