<div class="title">
    <?php echo $title; ?>
</div>
<div class="relativewrapper">
    <div class="imagegallery">
<?php
    
    if (!empty($images)) {
        
//        echo $paginationTop;
        ?>
        
        <div class="paginationtop">
            Page: <!--strong><?php echo $page; ?></strong> of <strong><?php echo $nPages; ?></strong-->
            <a class="cur" <?php echo $prevPageStyle; ?> href="?c=<?php echo $cid; ?>&amp;g=<?php echo $gid; ?>&amp;pg=<?php echo $prevPage; ?>">&lt;</a>
            <?php 
                
                if ($nPages > 15) {
                    if ($page < 6) {
                        
                        for ($x = 1; $x < 6; $x++) {
                            if ($page == $x) {
                                echo '<a class="cur" href="?c=' . $cid . '&amp;g=' . $gid . '&amp;pg=' . $x . '"> ' . $x . ' </a>';
                            } else {
                                echo '<a href="?c=' . $cid . '&amp;g=' . $gid . '&amp;pg=' . $x . '"> ' . $x . ' </a>';   
                            }
                            
                            
                        }
                        echo '...';
                        
                    } elseif ($page < $nPages - 4) {
                        echo '...';   
                        $i = (int)1;
                        for ($x = $page-2; $x <= $nPages; $x++) {
                 
                            if ($i > 5) {break;}
                            if ($page == $x) {
                                echo '<a class="cur" href="?c=' . $cid . '&amp;g=' . $gid . '&amp;pg=' . $x . '"> ' . $x . ' </a>';
                            } else {
                                echo '<a href="?c=' . $cid . '&amp;g=' . $gid . '&amp;pg=' . $x . '"> ' . $x . ' </a>';   
                            }
                            
                            $i++;
                            
                        }    
                        echo '...';
                    } elseif ($page > 5) {
                        echo '...';   
                        $i = (int)1;
                        

                        
                        for ($x = $nPages-4; $x <= $nPages; $x++) {
                            
                            if ($i > 5) {break;}
                            if ($page == $x) {
                                echo '<a class="cur" href="?c=' . $cid . '&amp;g=' . $gid . '&amp;pg=' . $x . '"> ' . $x . ' </a>';
                            } else {
                                echo '<a href="?c=' . $cid . '&amp;g=' . $gid . '&amp;pg=' . $x . '"> ' . $x . ' </a>';   
                            }
                            
                            $i++;
                            
                        }    
                        
                    }
                } else {

                    for ($x = 1; $x <= $nPages; $x++) {
                        if ($page == $x) {
                            echo '<a class="cur" href="?c=' . $cid . '&amp;g=' . $gid . '&amp;pg=' . $x . '"> ' . $x . ' </a>';
                        } else {
                            echo '<a href="?c=' . $cid . '&amp;g=' . $gid . '&amp;pg=' . $x . '"> ' . $x . ' </a>';   
                        }
                    
                    } 
                    
                    
                }
                ?>
            <a class="cur" <?php echo  $nextPageStyle; ?> href="?c=<?php echo $cid; ?>&amp;g=<?php echo $gid; ?>&amp;pg=<?php echo $nextPage; ?>">&gt;</a>
        </div>

<?php
//        $this->paginationBottom = '<div class="paginationbottom"><a ' . $prevPageStyle . ' href="?c=' . $_GET['c'] . '&amp;g=' . $_GET['g'] . '&amp;pg=' . $prevPage . '">&lt;</a>Page: <strong>' . $this->page . '</strong> of <strong>' . $nPages . '</strong><a ' . $nextPageStyle . ' href="?c=' . $_GET['c'] . '&amp;g=' . $_GET['g'] . '&amp;pg=' . $nextPage . '">&gt;</a></div>';

        
        
        echo '<div class="fl w100"><table class="imagegalleryt" cellspacing="0"><tbody>';
        
        $i = (int)1;
        $rows = (int)0;
        $colcount = (int)0;

        foreach ($images as $k => $arr) {
            
            
            
            if ($i == 1) {
                echo '<tr>';
                $rows++;
                
            }
            
            

            $extraClass = '';
            if ($rows == (int)$nRows && $nFinal < 5) {
                //only put colspan on the last ever image(s)!
                
                switch ($nFinal) {
                    case 4:
                        if ($i < 4) {
                            $colspan = 'colspan="1"';
                        } else {
                            $extraClass = 'override last';
                            $colspan = 'colspan="2"';
                        }
                        
                        break;
                    case 3:
                        if ($i < 2) {
                            $colspan = 'colspan="1"';
                        } else {
                            $extraClass = 'override last';

                            $colspan = 'colspan="2"';   
                        }
                        break;
                    case 2:
                        if ($i == 1) {
                            $colspan = 'colspan="2"';
                        } else {
                            $extraClass = 'override last';

                            $colspan = 'colspan="3"';   
                        }
                        break;
                    case 1:
                        $colspan = 'colspan="5"';
                        $extraClass = 'override last';

                        break;
                    default:
                        $colspan = 'colspan="1"';
                        break;
                }
            } else {
                $colspan = '';
                if ($nFinal < 5) {
                    $extraClass = "override";
                }
            }


            //if the row is less than the max rows just echo default:
            $class= '';
            
            //if the td is the last then add class = last
            if ($i == 5 || $i == $nFinal) {
                $class = ' class="last"';                    
            }
            
            if ($rows < (int)$nRows) {
                echo '<td' . $class . '><a href="' . $arr['href'] . '">' . $arr['html'] . '</a></td>';
            } elseif ($rows == (int)$nRows) {
                //if the row is equal to the last row
                //we need to figure out if the row is going to be having less images in it
                if ($i <= $nFinal) {
                    echo '<td' . $class . ' ' . $colspan . '><a href="' . $arr['href'] . '">' . $arr['html'] . '</a></td>';
                }
            }
            
            
            /*
            if ($colcount < $nFinal) {
                if ($colspan !== '') {
                    $colcount++;
                }
            }
            
                if ($k >= 0 && $k < $totalImages) {

                

                    if ($k > $totalImages - 5) {
                        
                        if ($i == 5) {
                            echo '<td ' . $colspan . ' class="bottom' . $extraClass . ' last"><a href="' . $arr['href'] . '">' . $arr['html'] . '</a></td>';                                            
                        } else {
                            //need to figure out if on the last image!
                            if ($k == $totalImages - 1) {
                                echo '<td ' . $colspan . ' class="bottom' . $extraClass . ' last"><a href="' . $arr['href'] . '">' . $arr['html'] . '</a></td>';                                            

                            } else {
                                echo '<td ' . $colspan . '><a href="' . $arr['href'] . '">' . $arr['html'] . '</a></td>';                                            
                            }
                        }
                    } else {
                        if ($i == 5 || $colcount == $nFinal) {
                            echo '<td ' . $colspan . ' class="last bottom' . $extraClass .  '"><a href="' . $arr['href'] . '">' . $arr['html'] . '</a></td>';    
                        } else {
                            echo '<td ' . $colspan . '><a href="' . $arr['href'] . '">' . $arr['html'] . '</a></td>';
                        }
                    
                    }
                
                
                
                }
                
            

            
            */
            
            
            if ($i == 5) {
                
                echo '</tr>';
                
                $i = (int)0;
                
            }
            
            $i++;        
            
            
            
        }
        
        
        if ($i > 1 && $i < 5 && $nFinal == 5) {
            
            
            for ($x = 1; $x <= 5; $x++) {
                
                echo '<td>&nbsp;</td>';
                echo '</tr>';
            }
            
        }
        
        
        
        
        
        echo '</tbody></table></div>';
        
        ?>
    

<div class="paginationtop">
Page: <!--strong><?php echo $page; ?></strong> of <strong><?php echo $nPages; ?></strong-->
<a class="cur" <?php echo $prevPageStyle; ?> href="?c=<?php echo $cid; ?>&amp;g=<?php echo $gid; ?>&amp;pg=<?php echo $prevPage; ?>">&lt;</a>
<?php 
    
    if ($nPages > 15) {
        if ($page < 6) {
            
            for ($x = 1; $x < 6; $x++) {
                if ($page == $x) {
                    echo '<a class="cur" href="?c=' . $cid . '&amp;g=' . $gid . '&amp;pg=' . $x . '"> ' . $x . ' </a>';
                } else {
                    echo '<a href="?c=' . $cid . '&amp;g=' . $gid . '&amp;pg=' . $x . '"> ' . $x . ' </a>';   
                }
                
                
            }
            echo '...';
            
        } elseif ($page < $nPages - 4) {
            echo '...';   
            $i = (int)1;
            for ($x = $page-2; $x <= $nPages; $x++) {
                
                if ($i > 5) {break;}
                if ($page == $x) {
                    echo '<a class="cur" href="?c=' . $cid . '&amp;g=' . $gid . '&amp;pg=' . $x . '"> ' . $x . ' </a>';
                } else {
                    echo '<a href="?c=' . $cid . '&amp;g=' . $gid . '&amp;pg=' . $x . '"> ' . $x . ' </a>';   
                }
                
                $i++;
                
            }    
            echo '...';
        } elseif ($page > 5) {
            echo '...';   
            $i = (int)1;
            
            
            
            for ($x = $nPages-4; $x <= $nPages; $x++) {
                
                if ($i > 5) {break;}
                if ($page == $x) {
                    echo '<a class="cur" href="?c=' . $cid . '&amp;g=' . $gid . '&amp;pg=' . $x . '"> ' . $x . ' </a>';
                } else {
                    echo '<a href="?c=' . $cid . '&amp;g=' . $gid . '&amp;pg=' . $x . '"> ' . $x . ' </a>';   
                }
                
                $i++;
                
            }    
            
        }
    } else {
        
        for ($x = 1; $x <= $nPages; $x++) {
            
            echo $x;
            
        } 
        
        
    }
    ?>
<a class="cur" <?php echo  $nextPageStyle; ?> href="?c=<?php echo $cid; ?>&amp;g=<?php echo $gid; ?>&amp;pg=<?php echo $nextPage; ?>">&gt;</a>
</div>


<?php

    }
    
    
    ?>
    </div>
</div>