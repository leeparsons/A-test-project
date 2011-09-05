<div class="title">
    Viewing Wish List: <?php echo $title; ?>
</div>
<div class="relativewrapper login">
    <div class="cartlarge">
        <table class="wishlist" cellspacing="0">
            <?php

                if (empty($wishList)) {
                 
                    echo '<tbody><tr><td>This wish list no longer exists or there are no items in this list.</td></tr>';
                    
                } else {
                 ?>
                    <thead>
                        <tr>
                            <th class="first">Image</th>
                            <th>Gallery</th>
                            <th>Image Name</th>
                            <th class="last"></th>
                        </tr>
                    </thead>
                    <tbody>

                <?php
                    
                    foreach ($wishList as $k => $list) {
                        $class = ($k > 0) ? 'bt' : '';

                     ?>
                        <tr>
                            <td class="first <?php echo $class; ?> tc"><a href="<?php echo $list['view']; ?>"><?php echo $list['image']; ?></a></td>
                            <td class="<?php echo $class; ?>"><?php echo $list['client']; ?>&rsquo;s <?php echo $list['gallery']; ?> Gallery</td>
                            <td class="<?php echo $class; ?>"><a href="<?php echo $list['view']; ?>"><?php echo $list['name']; ?></a></td>
                            <td class="<?php echo $class; ?> tc"><a href="<?php echo $list['view']; ?>">view</a></td>
                        </tr>

                       <?php 
                    }
                    
                    
                }
                
                
            ?></tbody>
        </table>
    </div>
</div>