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

	window.emptySelectOption = '<option value="">--</option>';

	var $global = {
		department_type: <?php echo json_encode(lookup("child_department_types"), JSON_HEX_TAG);?>,
		department_function_type: <?php echo json_encode(lookup("department_function_type"), JSON_HEX_TAG);?>,
		function_type: <?php echo json_encode(lookup("function_type"), JSON_HEX_TAG);?>,
		location_scope: <?php echo json_encode(lookup("location_scope"), JSON_HEX_TAG);?>,
	}

</script>