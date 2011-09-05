<div class="title">
    Adding Extra Images to: <?php echo $client . '&rsquo;s ' . $name; ?> Gallery
</div>
<div class="relativewrapper">
    <div class="border"></div>
    <form method="post" action="<?php echo $action; ?>" class="central nm" enctype="multipart/form-data">
    <div class="bigpicleft">
            <p class="w100 fl">Quick Links:
                <a href="<?php echo $galleryLink; ?>" class="pds5">Edit Gallery</a>
                <a href="<?php echo $clientLink; ?>" class="pds5">Edit Client</a>
                <a href="<?php echo $viewGalleryLink; ?>" target="_blank" class="pds5">View Gallery</a>
                <a href="<?php echo $cancelLink; ?>" class="pds5">Cancel</a>
            </p>
            <p class="w100 fl">Upload more images to the chosen gallery. You can upload as many images as you like. But remember, choosing to upload too many at one may cause your browser to crash. <!--The images must be in a zip folder, and the zip folder can not be greater than <?php echo $maxUploadSize; ?>.--></p>
            <p class="w100 fl">Once you have uploaded all the images, click rescan to populate your images into the gallery.</p>
            <div class="fl w100">
                <span>Total images:</span><span class="input"><span><?php echo $total; ?></span> <a id="scan" href="<?php echo $reScan; ?>">Rescan</a></span>
            </div>
            <div class="fl w100">
<script type="text/javascript">/*<![CDATA[*/
		var swfu;

		window.onload = function() {
			var settings = {
				flash_url : "<?php echo $swfName; ?>.swf",
				upload_url: "<?php echo $uploadUrl; ?>",
				post_params: {"g" : "<?php echo $g; ?>", "c" : "<?php echo $cid; ?>"},
				file_size_limit : "100 MB",
				file_types : "*.*",
				file_types_description : "All Files",
				file_upload_limit : 800,
				file_queue_limit : 0,
				custom_settings : {
					progressTarget : "fsUploadProgress",
					cancelButtonId : "btnCancel"
				},
				debug: false,

				// Button settings
				button_image_url: "<?php echo $button; ?>",
				button_width: "61",
				button_height: "22",
				button_placeholder_id: "spanButtonPlaceHolder",
				
				// The event handler functions are defined in handlers.js
				file_queued_handler : fileQueued,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_start_handler : uploadStart,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,
				queue_complete_handler : queueComplete	// Queue plugin event
			};

			swfu = new SWFUpload(settings);
	     };

$('#scan').click(function () {
                 
                 if (!$(this).hasClass('scanning')) {
                 
                 $(this).addClass('scanning');
                 
                 $(this).html('<img src="<?php echo $loader; ?>" alt="scanning"/>').prev('span').html('scanning: ');
                 
                 $.post('<?php echo $reScanFlat; ?>',
                        {<?php echo $reScanParams; ?>},
                        function (data) {
                        
                        if (data == 'false') {
                            alert('There was an error scanning your images. Browse directly to: <?php echo $reScan; ?>');
                            $('#scan').html('Rescan').removeClass('scanning').prev('span').html('0 Images');
                        } else {
                        
                            $('#scan').html('Rescan').removeClass('scanning').prev('span').html(data);
                        
                        }
                        
                        }
                        );
                 }
                 return false;
                 });

	/*]]>*/</script>
			<div class="fieldset flash" id="fsUploadProgress">
			<span class="legend">Upload Queue</span>
			</div>
		<div id="divStatus">0 Files Uploaded</div>
			<div class="buttons">
				<span id="spanButtonPlaceHolder"></span>
				<input id="btnCancel" type="button" value="Cancel All Uploads" onclick="swfu.cancelQueue();" disabled="disabled"/>

			</div>
		</div>
    </div>
    <div class="bigpicright">
        <div class="bigpic tc">
            <?php echo $image; ?>
            <input type="hidden" name="g" value="<?php echo $g; ?>"/>
        </div>
    </div>
    </form>
</div>
<script type="text/javascript">/*<![CDATA[*/
$('#update').click(function () {$(this).addClass('dn');$('.button').addClass('dn');$('.hiddenmessage').removeClass('dn');});
/*]]>*/</script>