<script type="text/javascript">

	/**
	* set global variables
	*/

	window.base_url = function(url) {
		url = (typeof(url) == 'undefined' ? '' : url);
		return '<?php echo base_url() ?>' + url;
	}

	window.public_url = function(url) {
		url = (typeof(url) == 'undefined' ? '' : url);
		return '<?php echo public_url() ?>' + url;
	}

	window.isoDate = '<?php echo date('c'); ?>'
	window.timestamp = '<?php echo time(); ?>'

	window.emptySelectOption = '<option value="">--</option>';

	var $global = {
		location_scope: <?php echo json_encode(lookup("location_scope"), JSON_HEX_TAG);?>,
		livefeed_limit: 100, // max visible item
		livefeed_interval: 20000, // mili sec
	}

</script>