<div class="title">
    DashBoard
</div>
<div class="fl w100  tc">
    <div id="chartdiv" style="margin:25px auto 0;height:400px;width:90%; "></div>
<script type="text/javascript">/*<![CDATA[*/
<?php
    $jplot = '';
    foreach ($ordersByMonth as $m => $order) {
        
        $jplot .= ($jplot == '')?'[\'' . $m . '\', ' . $order . ']':', [\'' . $m . '\', ' . $order . ']';
        
    }
    
    $jplot = '[' . $jplot . ']';
    
    ?>
var plot = <?php echo $jplot; ?>;
$.jqplot('chartdiv', [plot], {
         title:'Monthly Sales Year To Date',
         gridPadding:{},
         axes:{
            xaxis:{
                renderer:$.jqplot.DateAxisRenderer, 
                tickOptions:{
                    formatString:'%b %Y',
                    showGridline: false,
                },
                min:'Jan 01, <?php echo date("Y", time()); ?>',
                max: 'Dec 01 <?php echo date("Y", time()); ?>',
                tickInterval:'1 month',
                label:'',
            },
            yaxis:{
                min:0,
                tickInterval: <?php
            
                    //get the maximum/10
         sort($ordersByMonth);
         array_multisort($ordersByMonth, SORT_DESC, SORT_NUMERIC);
         
         
         $step = (int)ceil($ordersByMonth[0]/10);
         

         
         echo $step + 1;
         
         
         
                ?>,
                label:'',
                tickOptions:{
                    formatString:'&pound; %.0f',
                },
            },

         },
         highlighter: {
            show: true,
            sizeAdjust: 10
         },

         
         
         });
/*]]>*/</script>

</div>

<div class="fl w100  tc">


<?php


        
    
//    echo $monthStr;

 //   echo $countOrders;

?>

</div>