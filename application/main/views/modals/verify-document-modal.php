<div class="modal fade" id="verifyDocModal" role="dialog" aria-labelledby="sm">
	<div class="modal-dialog modal-md">
			<div class="modal-content" id="modal-content">
				<div class="modal-header bg-cyan text-white">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" style="font-family:Trebuchet MS; font-size:16px;"><b>Document verification</b></h4>
				</div>
				<div class="modal-body" id="modal-body">
					<div id="qr-reader"></div>
				</div>
				<div class="modal-footer">
					<div class="pull-right">
						<button type="button" class="btn bg-cyan btn-sm text-white" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
						<button type="button" class="btn bg-green btn-sm text-white"><i class="fa fa-check"></i> Verify</button>
					</div>
				</div>
			</div>
	</div>
</div>

<script type="text/javascript" src="<?php echo public_url(); ?>resources/libraries/html5-qrcode.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		const html5QrCode = new Html5Qrcode("qr-reader");

        $('#verifyDocModal').on('show.bs.modal', function (e) {
        	// $('#verifyDocModal').LoadingOverlay('show');
        }).on('shown.bs.modal', function (e) {
            // This method will trigger user permissions
            Html5Qrcode.getCameras().then(devices => {
              /**
               * devices would be an array of objects of type:
               * { id: "id", label: "label" }
               */
                console.log(devices);
              if (devices && devices.length) {

                var camera      = devices[0];
                var cameraID    = camera.id
                var cameraLabel = camera.label;
                // .. use this to start scanning.

                html5QrCode.start(
                  cameraID,     // retreived in the previous step.
                  {
                    fps: 10,    // sets the framerate to 10 frame per second
                    qrbox: 250  // sets only 250 X 250 region of viewfinder to
                                // scannable, rest shaded.
                  },
                  qrCodeMessage => {
                    // do something when code is read. For example:
                    console.log(`QR Code detected: ${qrCodeMessage}`);
                  },
                  errorMessage => {
                    // parse error, ideally ignore it. For example:
                    // console.log(`QR Code no longer in front of camera.`);
                  })
                .catch(err => {
                  // Start failed, handle it. For example,
                  console.log(`Unable to start scanning, error: ${err}`);
                });

                // $('#verifyDocModal').LoadingOverlay('hide');

              }

            }).catch(err => {
              bootbox.alert('Unable to find camera to use for scanning.')
            });

        }).on('hide.bs.modal', function (e) {
            console.log('close');
            html5QrCode.stop().then(ignore => {
              // QR Code scanning is stopped.
              console.log("QR Code scanning stopped.");
            }).catch(err => {
              // Stop failed, handle it.
              console.log("Unable to stop scanning." + err);
            });

            $('#verifyDocModal #qr-reader').html()
        });
	})
</script>