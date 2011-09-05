<div class="title">
    <h2>Viewing Clients</h2>
</div>
<div class="relativewrapper">
    <div class="cartlarge">
        <div class="fl w100">
            <p><a href="add">Create a new cleint</a></p>
        </div>

        <?php
        
            if (!empty($clientsList)) {

                echo '<div class="fl w100">';
                
                echo '<table class="products" cellspacing="0"><thead>';
                
                echo '<th class="bd first">image</th>';
                
                echo '<th class="bd" colspan="4">Client</th>';
                
                echo '<th class="bd"></th>';

                echo '<th class="bd last"></th>';

                echo '</thead><tbody>';
                
                foreach ($clientsList as $i => $client) {
                    
                    echo '<tr class="client">';
                    
                    if ($i > 0) {
                        echo '<td class="bd first bt" width="150">' . $client['image'] . '</td>';

                        echo '<td class="bd bt" colspan="4"><a href="' . $client['cLink']. '">' . $client['name'] . '</a><a style="margin-left:25px" href="' . $client['createg'] . '">Create a new gallery for this client</a></td>';
                        
                        echo '<td class="bd bt"><a href="' . $client['cLink']. '">edit</a></td>';
                        
                        echo '<td class="bd last bt"><a href="' . $client['deleteLink']. '">delete</a></td>';
                        
                    } else {
                        echo '<td class="bd first">' . $client['image'] . '</td>';                        

                        echo '<td class="bd" colspan="4"><a href="' . $client['cLink']. '">' . $client['name'] . '</a><a style="margin-left:25px" href="' . $client['createg'] . '">Create a new gallery for this client</a></td>';
                        
                        echo '<td class="bd" width="65"><a href="' . $client['cLink']. '">edit</a></td>';
                        
                        echo '<td class="bd last" width="65"><a href="' . $client['deleteLink']. '">delete</a></td>';
                        
                    }
                    
                    
                    echo '</tr>';

                    if (!empty($client['galleries'])) {
                        
                        foreach ($client['galleries'] as $l) {

                            
                            if ($l['name'] !== '') {
                            
                                echo '<tr class="cgallery cgallery' . $i . '">';
                                
                                echo '<td class="bd" style="border-bottom:none">&nbsp;</td>';
                                
                                echo '<td class="bd bt">' . $l['image'] . '</td>';
                                
                                echo '<td class="bd bt"><a href="' . $l['gLink'] . '">' . $l['name'] . '</a></td>';
                                
                                echo '<td class="bd bt"><a href="' . $l['activeLink'] . '">' . $l['activeText'] . '</a></td>';

                                echo '<td class="bd bt"><a href="' . $l['addImagesLink'] . '">' . $l['addImagesText'] . '</a></td>';

                                echo '<td class="bd bt"><a href="' . $l['gLink'] . '">edit</a></td>';
                                
                                echo '<td class="bd bt"><a href="' . $l['delLink'] . '">delete</a></td>';
                                
                                
                                
                                echo '</tr>';
                                
                            }
                        }
                        
                        
                    } else {
                     
                        echo '<tr><td colspan="5">There are no galleries for <a href=""' . $client['cLink']. '">' . $client['name'] . '</a> yet.</td></tr>';
                        
                    }
                
                }
                
                
                
                echo '</tbody></table>';
                
                echo '</div>';
            }
        
        
        ?>
    </div>
</div>
<script type="text/javascript">/*<![CDATA[*/
                  $('tr.cgallery').each(function () {$(this).css({display:'none'});});




                  $('tr.client').each(function (i) {
                                      if ($(this).next('tr').hasClass('cgallery')) {
                                      $(this).find('td').eq(1).append('<span class="fr cp" id="cgallery' + i + '"><span class="showgalleries">show galleries &#9660;</span><span class="showgalleries" style="display:none">hide galleries &#9650;</span></span>');
                                        $(this).find('td').eq(1).find('span.cp').click(function () {
                                                                                  $(this).find('span').toggle();
                                                                                  $('tr.' + $(this).attr('id')).toggle();
                                                                                  });
                                      }
                                      });
/*]]>*/</script>