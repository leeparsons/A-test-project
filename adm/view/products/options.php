<div class="title">
    All product Options
</div>
<div class="relativewrapper">
    <div class="cartlarge">
        <div class="fl w100">
            <p><a href="<?php echo $create; ?>">Create a new product option</a></p>
        </div>

<?php


    
    if (!empty($options)) {
        echo '<div class="fl w100">';
        
        echo '<table cellspacing="0" class="products">';
        
        echo '<thead><tr>';
        
        echo '<th class="bd first">Image</th>';
        
        echo '<th class="bd">Name</th>';
        
        echo '<th class="bd">Description</th>';
        
        echo '<th class="bd">Options</th>';

        echo '<th class="bd"></th>';

        echo '<th class="bd"></th>';
        
        echo '<th class="last">Notes</th>';
        
        echo '</tr></thead><tbody>';
        
        $cOptions = count($options);

        foreach ($options as $i => $option) {
            
            
            if ($cOptions == $i + 1) {

                echo '<tr class="client">';
                
                echo '<td class="bd bottom first">' . $option['image'] . '</td>';
                
                echo '<td class="bd bottom"><a href="' . $option['link']. '">' . $option['name'] . '</a></td>';
                
                echo '<td class="bd bottom">' . $option['description'] . '</td>';
                
                
                if (!empty($option['values'])) {
                    
                    
                    echo '<td class="bd bottom"><select>';
                    
                    foreach ($option['values'] as $v) {
                        echo '<option>' . trim($v['value']) .  ' '   . trim($v['cost']) . '</option>';                    
                    }
                    
                    echo '</select></td>';
                    
                    
                } else {
                    echo '<td class="bd bottom">No value set (This means no one can purchase this option!</td>';
                }
                
                echo '<td class="bd bottom"><a href="' . $option['link']. '">edit</a></td>';
                
                echo '<td class="bd bottom">' . $option['remove']. '</td>';
                
                echo '<td class="bd bottom last">' . $option['notes'] . '</td>';
                
                echo '</tr>';
                
            } else {
            
                echo '<tr class="client">';
                
                echo '<td class="bd first">' . $option['image'] . '</td>';
                
                echo '<td class="bd"><a href="' . $option['link']. '">' . $option['name'] . '</a></td>';
                
                echo '<td class="bd">' . $option['description'] . '</td>';
                
                
                if (!empty($option['values'])) {
                    
                    
                    echo '<td class="bd"><select>';
                    
                    foreach ($option['values'] as $v) {
                        echo '<option>' . trim($v['value']) .  ' '   . trim($v['cost']) . '</option>';                    
                    }
                    
                    echo '</select></td>';
                    
                    
                } else {
                    echo '<td class="bd">No value set (This means no one can purchase this option!</td>';
                }
                
                echo '<td class="bd"><a href="' . $option['link']. '">edit</a></td>';
                
                echo '<td class="bd">' . $option['remove']. '</td>';
                
                echo '<td class="bd last">' . $option['notes'] . '</td>';
                
                echo '</tr>';
            }
            
                        
        }
        
        
        
        echo '</tbody></table>';
      
        echo '</div>';
    }
    ?>
    </div>
</div>