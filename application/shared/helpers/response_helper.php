<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* output content type json
*/
function response_json($array, $cache = false)
{
	$ci =& get_instance();

	if ($cache !== false) {
		$ci->output->cache($cache);
	}
	$ci->output->set_content_type('application/json')->set_output(json_encode($array));
}