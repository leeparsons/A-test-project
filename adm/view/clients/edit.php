<div class="title">
    Editing: <?php echo $clientInfo['name']; ?>
</div>
<div class="relativewrapper">
    <div class="border"></div>
    <div class="bigpicleft">
        <form method="POST" action="<?php echo $action; ?>" class="central" enctype="multipart/form-data">
            <div class="fl w100">
                <label for="name">Client Name:</label>
                <input type="text" name="name" id="name" value="<?php echo $clientInfo['name']; ?>"/>
                <?php
                    
                    if (!empty($cError['name'])) {
                        echo '<span class="error">' . $cError['name'] . '</span>';
                    }
                    
                    
                    ?>
            </div>
            <?php if ($seoUrls == 1) { ?>
            <div class="w100 fl">
                <label for="seourl">SEO URL:</label>
                <input type="text" name="seourl" id="seourl" value="<?php echo $clientInfo['seoUrl']; ?>" />
<?php
    
    if (!empty($cError['seoUrl'])) {
        echo '<span class="error">' . $cError['seoUrl'] . '</span>';
    }
    
    
    ?>
            </div>
            <script type="text/javascript">/*<![CDATA[*/
                $('#name').keyup(function () {
                                 var str = $(this).val().replace(/\s/g, '-');
                                 $('#seourl').val(str.toLowerCase());
                                 });

            /*]]>*/</script>
            <?php } ?>
            <div class="fl w100">
                <label for="description">Description:</label>
                <textarea cols="30" rows="5" name="description" id="description"><?php echo $clientInfo['description']; ?></textarea>
            </div>
            <div class="fl w100">
                <label for="email">Email Address:</label>
                <input type="text" name="email" id="email" value="<?php echo $clientInfo['email']; ?>"/>
            </div>
            <div class="fl w100">
                <label for="frontimage">Upload a new image for the client:</label><input type="checkbox" name="newfile" id="newfile"/>
                <input type="file" name="image" id="frontimage"/>
                <?php
    
    if (!empty($cError['image'])) {
        echo '<span class="error">' . $cError['image'] . '</span>';
    }
    
    
                ?>
                <script type="text/javascript">/*<!CDATA[*/
                   // $('input#newfile').change(function () {$(this).next("input").toggle();}).next("input").toggle();}, 1000);
                /*]]>*/</script>
            </div>
<div class="fl w100">
<label for="splash">Upload a new image for the splash page:</label><input type="checkbox" name="newsplash" class="fl" id="newsplash"/>
<input type="file" name="splash" id="splash"/>
<?php
    
    if (!empty($cError['splash'])) {
        echo '<span class="error">' . $cError['splash'] . '</span>';
    }
    
    
    ?>
<script type="text/javascript">/*<!CDATA[*/
//$('input#newsplash').change(function () {$(this).next("input").toggle();}).next("input").toggle();
/*]]>*/</script>
</div>

            <div class="fl w100">
                <input type="submit" value="update" name="update"/>
            </div>
            <input type="hidden" value="<?php echo $cidentify; ?>" name="c"/>
            <input type="hidden" value="<?php echo $originalName; ?>" name="originalname"/>
            <input type="hidden" value="<?php echo $originalFile; ?>" name="originalfile"/>
            <input type="hidden" value="<?php echo $originalSplashFile; ?>" name="originalsplashfile"/>
        </form>
        <div class="gallerieslist">
            <?php 
                
                if (!empty($clientInfo['galleries'])) {
                    
                    ?><h3>Galleries:</h3>
                    
                    <a href="<?php echo $create; ?>" class="input">Create a new gallery</a>

                    <table class="carttable" cellspacing="0">
                        <?php
                            
                            foreach ($clientInfo['galleries'] as $i => $g) {
                            
                            ?>
                        <tr>
                            <td><a href="<?php echo $g['gLink']; ?>"><?php echo $g['image']; ?></a></td>
                            <td><a href="<?php echo $g['gLink']; ?>"><?php echo $g['name']; ?></a></td>
                            <td><a href="<?php echo $g['gLink']; ?>">edit</a></td>
                            <td><a href="<?php echo $g['addImagesLink']; ?>"><?php echo $g['addImagesText']; ?></a></td>
                            <td><a href="<?php echo $g['activateLink']; ?>"><?php echo $g['activate']; ?></a></td>
                        </tr>
                        <?php
                            
                            }
                            
                            ?>
                    </table>
                    <?php
                } else {
                    
                 
                    ?>
                <p>No galleries have been created yet for this client</p>
                <p><?php if (isset($guide)) { ?>
                    You can create a gallery by uploading the folder structure and images following the <a href="<?php echo $guide; ?>">guide here</a><br/>or <?php } ?>if you have the know&ndash;how, <a href="<?php echo $create; ?>" class="input">create a gallery</a></p>
                <?php
                    
                    
                }
                
                
                
                ?>
        </div>

</div>
<div class="bigpicright">
<div class="bigpic tc">
<p>Current Splash Image:</p>
<?php echo $clientInfo['image']; ?>
</div>
</div>
</div>