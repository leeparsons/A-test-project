<div class="carttitle">
    <?php echo $title; ?>
    <img class="fr" src="<?php echo $cards; ?>" alt="payment icons" height="35">
    <span class="fr">We accept: </span>
</div>
<div class="relativewrapper">
    <div class="cartlarge">
    <?php
        
        if (!empty($cart)) {
        
        ?>

        <table cellspacing="0" class="carttable">
            <tbody>
                <tr>
                    <th>Item Preview</th>
                    <th>Details</th>
                    <th>Quantity</th>
                    <th class="last">Cost</th>
                </tr>
                <?php
                    
                    
                    
                    foreach ($cart as $k => $arr) {
                        echo '<tr>';   
                    
                        
                        if ($k == count($cart) - 1) {
                            $class = ($k % 2)?' class="bottom odd"':' class="bottom"';
                        } else {
                            $class = ($k % 2)?' class="odd"':'';   
                        }
                        
                        echo '<td onclick="window.location = \'' . $arr['url'] . '\';" ' . $class . '>' . $arr['image'] . '</td>';
                                               
                        echo '<td onclick="window.location = \'' . $arr['url'] . '\';" ' . $class . '>' . $arr['name'] . '<br/>' . $arr['description'];
                        
                        if ($arr['notes'] !== '') {
                            
                            echo '<a href="" class="notecontainer" onclick="return false;"><span class="morenotes">' . nl2br(htmlentities(stripslashes($arr['notes']))) . '</span>see notes</a>';   
                        }
                        
                        echo '</td>';
                        
                        echo '<td' . $class . '><span>' . $arr['quantity'] . '</span><a href="' . $arr['plus'] . '"><span class="plus"></span></a><a href="' . $arr['minus'] . '"><span class="minus"></span></a></td>';
                        
                        echo '<td' . $class . '>&pound; ' . $arr['cost'] . '</td>';
                        
                        echo '</tr>';
                    }
                    
                    ?>
            </tbody>
        </table>
<?php /*        <form method="post" action="<?php echo $action; ?>"> */ ?>
        <table class="totals" cellspacing="0">
            <tr class="subtotal">
                <td></td><td>Sub Total:</td><td><span><?php echo $subTotal; ?></span></td>
            </tr>
            <?php
                
                if ($vatTotal !== false) {
                 
                    echo '<tr class="vat"><td></td><td>VAT:</td><td><span>' . $vatTotal . '</span><span>at ' . $vatRate . '</span></td></tr>';
                    
                }
                
                ?>
            <tr class="total">
                <td></td><td>Total:</td><td><span><?php echo $total; ?></span></td>
            </tr>
            <tr>
                <td colspan="3" class="submission"><a href="<?php echo $action; ?>" class="addtocart">continue to shipping</a></td>
            </tr>
        </table>
<?php
    /*
        <input type=hidden name="instId" value="244018" />
        <input type=hidden name="amount" value="<?php echo $unformattedTotal; ?>" />
        <input type=hidden name="currency" value="GBP" />
        <input type=hidden name="testMode" value="100" />
        <input type=hidden name="desc" value="<?php echo $cartDescription; ?>" />
        <input type=hidden name="name" value="AUTHORISED">

        </form>
*/ ?>
<?php
        
        } else {
            echo $message;   
        }
        
        ?>
    </div>
</div>
