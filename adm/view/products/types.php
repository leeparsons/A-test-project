<div class="title">
    All product types
</div>
<div class="relativewrapper">
    <div class="cartlarge">
        <div class="fl w100">
            <p><a href="<?php echo $create; ?>">Create a new product type</a></p>
        </div>

<?php

    if (!empty($types)) {
        
        echo '<div class="fl w100">';
        
        echo '<table class="products" cellspacing="0">';
        
        echo '<thead><tr>';
        
        echo '<th class="bd first">Image</th>';

        echo '<th class="bd">Name</th>';
    
        echo '<th class="bd"></th>';
            
        echo '<th class="bd"></th>';
        
        echo '<th class="last">Notes</th>';
        
        echo '</tr></thead>';
        
        $cType = count($types);
        
        foreach ($types as $i => $type) {
            
            
            if ($cType == $i + 1) {
                
                echo '<tr class="client">';
                
                echo '<td class="bd bottom first">' . $type['image'] . '</td>';
                
                echo '<td class="bd bottom"><a href="' . $type['link']. '">' . $type['name'] . '</a></td>';
                
                echo '<td class="bd bottom"><a href="' . $type['link']. '">edit</a></td>';
                
                echo '<td class="bd bottom">' . $type['remove']. '</td>';
                
                echo '<td class="bottom last">' . $type['notes'] . '</td>';
                
                echo '</tr>';
                
            } else {
                
                echo '<tr class="client">';
                
                echo '<td class="bd first">' . $type['image'] . '</td>';
                
                echo '<td class="bd"><a href="' . $type['link']. '">' . $type['name'] . '</a></td>';
                
                echo '<td class="bd"><a href="' . $type['link']. '">edit</a></td>';
                
                echo '<td class="bd">' . $type['remove']. '</td>';
                
                echo '<td class="last">' . $type['notes'] . '</td>';
                
                echo '</tr>';
                
            }
                        
        }
        
        
        
        echo '</table>';
        echo '</div>';  
    }
    ?>
    </div>
</div>