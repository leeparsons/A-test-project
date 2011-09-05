<div class="title">
    One Off Products
</div>
<div class="relativewrapper">
    <div class="cartlarge">
        <div class="fl w100">
            <p><a href="<?php echo $create; ?>">Create a New Product</a></p>
        </div>

<?php


    
    if (!empty($types)) {
        echo '<div class="fl w100">';
        
        echo '<table cellspacing="0" class="products">';
        
        echo '<thead><tr>';
        
        echo '<th class="bd first">Image</th>';
        
        echo '<th class="bd">Name</th>';
        
        echo '<th class="bd">Description</th>';
        
        echo '<th class="bd">Cost</th>';

        echo '<th class="bd"></th>';

        echo '<th class="bd"></th>';

        echo '<th class="bd">Link</th>';
                
        echo '</tr></thead><tbody>';
        
        $cProds = count($types);

        foreach ($types as $i => $prod) {
            
            
            if ($cProds == $i + 1) {

                echo '<tr class="client">';
                
                echo '<td class="bd bottom first">' . $prod['image'] . '</td>';
                
                echo '<td class="bd bottom"><a href="' . $prod['edit']. '">' . $prod['name'] . '</a></td>';
                
                echo '<td class="bd bottom">' . $prod['description'] . '</td>';
                    
                echo '<td class="bd bottom">&pound; ' . $prod['cost'] . '</td>';

                echo '<td class="bd bottom"><a href="' . $prod['edit']. '">edit</a></td>';
                
                echo '<td class="bd bottom"><a href="' . $prod['delete']. '">delete</a></td>';

                echo '<td class="bd bottom link"><input type="hidden" size="25" value="' . $prod['link']. '" id="link" /><a href="' . $prod['link'] . '">preview</a></td>';
                
                echo '</tr>';
                
            } else {
            
                echo '<tr class="client">';
                
                echo '<td class="bd first">' . $prod['image'] . '</td>';
                
                echo '<td class="bd"><a href="' . $prod['edit']. '">' . $prod['name'] . '</a></td>';
                
                echo '<td class="bd">' . $prod['description'] . '</td>';
                
                echo '<td class="bd">&pound; ' . $prod['cost'] . '</td>';

                
                echo '<td class="bd"><a href="' . $prod['edit']. '">edit</a></td>';
                
                echo '<td class="bd"><a href="' . $prod['delete']. '">delete</a></td>';
                
                echo '<td class="bd link"><input type="hidden" size="25" value="' . $prod['link']. '" id="link" /><a href="' . $prod['link'] . '">preview</a></td>';

                
                
                echo '</tr>';
            }
            
                        
        }
        
        
        
        echo '</tbody></table>';
      
        echo '</div>';
    }
    ?>
    </div>
</div>
<script type="text/javascript">/*<![CDATA[*/

$.reveal = function (em, href) {
    em.parent('td').append('<br/><span>' + href + '</span>');
    em.remove();
}
$('td.link').each(function () {
                  var href = $(this).find('input').eq(0).val();
                  $(this).append('<br/><span class="copylink" onclick="$.reveal($(this), \'' + href + '\');">Copy Link</span>');
                  });
/*]]>*/</script>