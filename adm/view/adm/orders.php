<div class="title">
    Orders
</div>
<form action="<?php echo $action; ?>" method="post">
<div class="relativewrapper">
    <div class="cartlarge">
        <div class="fr">
<span>Select the period:</span>
        <select name="filter" id="filter">
            <option value="1">Month To Date</option>
            <option value="2">Year To Date</option>
        </select>
        </div>
    <table class="products w100" cellspacing="0">
        <thead>
            <tr>
                <th class="first bd">Date</th>
                <th class="bd">Name</th>
                <th class="bd">Amount</th>
                <th class="last">Gallery</th>
            </tr>
        </thead>
        <tbody>
            <?php

                if (empty($ordersArr)) {
                    
                    ?>
                <tr><td colspan="4" class="first last">You have no orders for the selected period</td></tr>
                <?php
                    
                } else {
                    
                    foreach ($ordersArr as $order) {
                        ?>
<tr><td>
<?php
                        print_r($order);
     ?></td></tr><?php                   
                    }
                    
                }
                
                ?>
        </tbody>
    </table>
    </div>
</div>
</form>
<script type="text/javascript">/*[CDATA[*/

$('#filter').change(function () {$('form').submit();});

/*]]>*/</script>