<div class="title">
    Your wish Lists
</div>
<div class="relativewrapper login">
    <div class="cartlarge">
        <p><a href="<?php echo $create; ?>">Create a new wishlist</a></p>
        <table class="wishlist" cellspacing="0">
            <?php

                if (empty($wishListsArr)) {
                 ?>
                    <thead><tr><th>&nbsp;</th></tr></thead>
                    <tbody class="empty"><tr><td class="first">You currently have no wish lists.</td></tr>
                   <?php 
                } else {

                    ?>

                    <thead>
                        <tr>
                            <th class="first">Name</th>
                            <th>Number of Items</th>
                            <th></th>
                            <th></th>
                            <th class="last"></th>
                        </tr>
                    </thead>
                    <tbody>
                <?php
                    
                    foreach ($wishListsArr as $k => $list) {
                        $class = ($k > 0) ? 'bt' : '';

                     ?>
                        <tr>
                            <td class="first <?php echo $class; ?>"><?php echo $list['name']; ?></td>
                            <td class="<?php echo $class; ?>"><?php echo $list['items']; ?> Items</td>
                            <td class="<?php echo $class; ?>"><a href="<?php echo $list['view']; ?>">view</a></td>
                            <td class="<?php echo $class; ?>"><a href="<?php echo $list['share']; ?>">share</a></td>
                            <td class="last <?php echo $class; ?>"><a href="<?php echo $list['delete']; ?>">delete</a></td>
                        </tr>

                       <?php 
                    }
                    
                    
                }
                
                
            ?></tbody>
        </table>
    </div>
</div>