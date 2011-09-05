<div class="title">
    General Settings
</div>
<div class="relativewrapper">
    <div class="cartlarge">
        <form method="POST" action="<?php echo $action; ?>" class="central" enctype="multipart/form-data">
            <ul>
                <li><span>General Settings</span></li>
                <li>Colours and fonts</li>
                <li>SEO settings</li>
            </ul>
            <div class="fl w100">
                <p class="description">The site title is what will appear in the title bar before the SEO title of each page by default. This can be overridden on a page by page basis.</p>
                <label for="title">Shop Title:</label>
                <input type="text" name="name" id="title" value="<?php echo $siteTitle; ?>"/>
            </div>
            <div class="fl w100">
                <p class="description">If SEO Urls are on you must make sure you have set up your .htaccess file correctly.</p>
                <label for="seo">SEO URLs On/Off:</label>
                <input class="fl" type="checkbox" <?php if ($seoUrls == 1) {echo 'checked="checked"';} ?> name="seourls" id="seo" />
            </div>
            <div class="fl w100">
                <p class="description">Determine whether or not search engines can index your online proofing parlour. If set on, people can find your proofing parlour, otherwise it will be as if it never existed.<br/>You can also override this on individual pages.</p>
                <label for="indexing">Allow Search Engines to index your proofing parlour:</label>
                <input class="fl" type="checkbox" <?php if ($indexing == 1) {echo 'checked="checked"';} ?> name="indexing" id="indexing"/>
            </div>

            <div class="fl w100">
                <p class="description">The default meta description will be used when a page has not been given a meta description.</p>
                <label for="metadescription">Default Meta Description</label>
                <textarea name="metadescription" id="metadescription" cols="25" rows="5"><?php echo $metad; ?></textarea>
            </div>

            <div class="fl w100">
                <p class="description">The default key words will be used when a page has not been given any keywords.</p>
                <label for="metakeywords">Default Meta Key Words</label>
                <textarea name="metakeywords" id="metakeywords" cols="25" rows="5"><?php echo $metakw; ?></textarea>
            </div>
            <div class="w100 fl">
                <p class="description">Paste your web analytics code (javascript) in the box below. This will be used on every page fo your site to track visitors.</p>
                <label for="analytics">Analytics Script</label>
                <textarea name="analytics" id="analytics" cols="25" rows="5"><?php echo $analytics; ?></textarea>
            </div>

            <div class="fl w100">
                <label for="font1">Link Colour</label>
                <input type="text" id="font1" name="font1" value="994999" class="colourpicker-input-box"/>
                <div class="colourpicker-select-box" id="colourpicker-select-box1"></div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
$('div#colourpicker-select-box1').ColorPicker({
    onSubmit: function(hsb, hex, rgb, el) {
                            $('input#font1').val(hex);
                            $(el).ColorPickerHide();
                        },
    onBeforeShow: function () {
                                               $(this).ColorPickerSetColor($('#font1').val());
                        },
    onChange: function (hsb, hex, rgb, el) {
                                               $('#font1').val(hex);
                        }
                        });


</script>