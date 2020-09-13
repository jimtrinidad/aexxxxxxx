<div class="modal fade" id="verifyDocModal" role="dialog" aria-labelledby="sm">
	<div class="modal-dialog modal-md">
			<div class="modal-content" id="modal-content">
				<div class="modal-header bg-cyan text-white">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" style="font-family:Trebuchet MS; font-size:16px;"><b>Document verification</b></h4>
				</div>
				<div class="modal-body" id="modal-body">
					<div id="qr-reader"></div>
					<hr>
					<label class="control-label text-bold" for="search_doc_id">Or enter transaction number.</label>
					<div class="input-group">
					   <input type="text" id="search_doc_id" name="search_doc_id" class="form-control">
					   <span class="input-group-btn">
					        <button class="btn btn-primary" id="search_doc_btn" type="button">Verify</button>
					   </span>
					</div>
				</div>
				<div class="modal-footer">
					<div class="pull-right">
						<button type="button" class="btn bg-cyan btn-sm text-white" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					</div>
				</div>
			</div>
	</div>
</div>


<div class="modal fade" id="verifiedDocumentModal" role="dialog" aria-labelledby="sm">
	<div class="modal-dialog modal-md">
			<div class="modal-content" id="modal-content">
				<div class="modal-header bg-cyan text-white">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" style="font-family:Trebuchet MS; font-size:16px;"><b>Verified Document</b></h4>
				</div>
				<div class="modal-body" id="modal-body">
					
					<div class="post-items bg-white padding-10">
						<div class="row gutter-5">
					         <div class=" col-xs-3">
					            <img src="" class="img-responsive verified-photo" style="margin: 10px auto 0;max-height: 70px;max-width: 70px;">
					         </div>
					         <div class="col-xs-9">
					         	<div class="row gutter-0">
					         		<div class="col-xs-12">
							         	<h2><span class="text-bold verified-name">Department name</span></h2>
							         	<span class="" style="font-family:Trebuchet MS; font-size:12px;">
							            	Email: <span class="verified-email">123123</span><br>
							            	Contact: <span class="verified-contact">123123</span><br>
							            </span>
							            <br>
							            <h2><span class="text-bold verified-docname">Service name</span></h2>
							            <span class="" style="font-family:Trebuchet MS; font-size:12px;">
							            	Date: <span class="verified-date text-green">123123</span><br>
							            	Expiration: <span class="verified-expiration text-orange">123123</span><br>
							            </span>
							            <h5><a target="_blank" class="verified-document_link btn btn-xs btn-success" style="font-family:Trebuchet MS; font-size:12px;padding:2px 5px;margin-top:5px;">Open Document</a></h5>
						            </div>
					            </div>
					         </div>
					    </div>
					</div>

				</div>
				<div class="modal-footer">
					<div class="pull-right">
						<button type="button" class="btn bg-cyan btn-sm text-white" data-dismiss="modal" onclick="Mgovph.verifyDocument()"><i class="fa fa-search"></i> Scan Again</button>
					</div>
					<div class="pull-left">
						<button type="button" class="btn bg-red btn-sm text-white" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					</div>
				</div>
			</div>
	</div>
</div>

<script type="text/javascript" src="<?php echo public_url(); ?>resources/libraries/qrcode.min.js"></script>
<script type="text/javascript" src="<?php echo public_url(); ?>resources/libraries/html5-qrcode.js?2"></script>
<script type="text/javascript">
	$(document).ready(function(){

		let html5QrcodeScanner = new Html5QrcodeScanner("qr-reader",{ 
			fps: 10, 
			qrbox: 250 
		}, false);

		var requesting;

		function onScanSuccess(qrMessage) {
			// handle the scanned code as you like
			// console.log(`QR matched = ${qrMessage}`);

			// check doccode if exists

			if (!requesting) {

				requesting = true;

				$('body').LoadingOverlay('show');
		        $.ajax({
		            url: window.public_url('get/document_qr_scan'),
		            type: 'GET',
		            data: jQuery.param({
		            	code : qrMessage
		            }),
		            success: function (response) {
		                // console.log(response);
		                if (response.status) {
		                	d = response.data;
		                	$('#verifiedDocumentModal').find('.verified-photo').prop('src', d.photo);
		                	$('#verifiedDocumentModal').find('.verified-name').text(d.mid + ' - ' + d.name);
		                	$('#verifiedDocumentModal').find('.verified-email').text(d.email);
		                	$('#verifiedDocumentModal').find('.verified-contact').text(d.contact);
		                	$('#verifiedDocumentModal').find('.verified-docname').text(d.docname);
		                	$('#verifiedDocumentModal').find('.verified-date').text(d.date);
		                	$('#verifiedDocumentModal').find('.verified-expiration').text(d.expire);
		                	$('#verifiedDocumentModal').find('.verified-document_link').prop('href',window.public_url('get/document/' + d.doccode + '/' + d.id));
		                	$('#verifiedDocumentModal').modal({
		                		backdrop : 'static',
	            				keyboard : false
		                	});
		                    $('#verifyDocModal').modal('hide');
		                } else {
		                	bootbox.alert({
								size: 'medium',
								message: response.message
							});
		                	html5QrcodeScanner.stop();
		                }
		            },
		            complete: function() {
		                $('body').LoadingOverlay('hide');
		                requesting = false;
		            },
		            cache: false,
		            contentType: false,
		            processData: false
		        });

		    }
		}

		function onScanFailure(error) {
			// handle scan failure, usually better to ignore and keep scanning
			// console.warn(`QR error = ${error}`);
		}

		$('#verifyDocModal').on('show.bs.modal', function (e) {
			html5QrcodeScanner.render(onScanSuccess, onScanFailure);
		}).on('hide.bs.modal', function (e) {
			html5QrcodeScanner.clear();
		});


		$('#search_doc_btn').click(function(){
			var doc_id = $('#search_doc_id').val();
			if (doc_id != '') {
				if (!requesting) {
					requesting = true;

					$('body').LoadingOverlay('show');
			        $.ajax({
			            url: window.public_url('get/document_qr_scan'),
			            type: 'GET',
			            data: jQuery.param({
			            	doc_id: doc_id
			            }),
			            success: function (response) {
			                // console.log(response);
			                if (response.status) {
			                	d = response.data;
			                	$('#verifiedDocumentModal').find('.verified-photo').prop('src', d.photo);
			                	$('#verifiedDocumentModal').find('.verified-name').text(d.mid + ' - ' + d.name);
			                	$('#verifiedDocumentModal').find('.verified-email').text(d.email);
			                	$('#verifiedDocumentModal').find('.verified-contact').text(d.contact);
			                	$('#verifiedDocumentModal').find('.verified-docname').text(d.docname);
			                	$('#verifiedDocumentModal').find('.verified-date').text(d.date);
			                	$('#verifiedDocumentModal').find('.verified-expiration').text(d.expire);
			                	$('#verifiedDocumentModal').find('.verified-document_link').prop('href',window.public_url('get/document/' + d.doccode + '/' + d.id));
			                	$('#verifiedDocumentModal').modal({
			                		backdrop : 'static',
		            				keyboard : false
			                	});
			                    $('#verifyDocModal').modal('hide');
			                } else {
			                	bootbox.alert({
									size: 'medium',
									message: response.message
								});
			                }
			            },
			            complete: function() {
			                $('body').LoadingOverlay('hide');
			                requesting = false;
			            },
			            cache: false,
			            contentType: false,
			            processData: false
			        });
				}
			}
		});
	})
</script>