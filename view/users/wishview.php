<div class="title">
    Viewing Wish List: <?php echo $title; ?>
</div>
<div class="relativewrapper login">
    <div class="cartlarge">
        <p><a href="<?php echo $wishListsLink; ?>">View all wish lists</a></p>
        <table class="wishlist" cellspacing="0">
            <?php

                if (empty($wishList)) {
                ?> 
                    
                <thead><tr><th>&nbsp;</th></tr></thead>
                <tbody class="empty"><tr><td class="first bt">This wish list no longer exists or there are no items in this list.</td></tr>
                  <?php  
                } else {
                 ?>
                    <thead>
                        <tr>
                            <th class="first">Image</th>
                            <th>Gallery</th>
                            <th>Image Name</th>
                            <th></th>
                            <th class="last"></th>
                        </tr>
                    </thead>
                    <tbody>

                <?php
                    
                    foreach ($wishList as $k => $list) {
                       
                        $class = ($k > 0) ? 'bt' : '';
                        
                     ?>
                        <tr>
                            <td class="first <?php echo $class; ?> tc"><?php echo $list['image']; ?></td>
                            <td class="<?php echo $class; ?>"><?php echo $list['client']; ?>&rsquo;s <?php echo $list['gallery']; ?> Gallery</td>
                            <td class="<?php echo $class; ?>"><?php echo $list['name']; ?></td>
                            <td class="<?php echo $class; ?> tc"><a href="<?php echo $list['view']; ?>">view</a></td>
                            <td class="last <?php echo $class; ?> tc"><a href="<?php echo $list['delete']; ?>">remove</a></td>
                        </tr>

                       <?php 
                    }
                    
                    
                }
                
                
            ?></tbody>
        </table>
    </div>
</div>