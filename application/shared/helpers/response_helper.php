<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* output content type json
*/
function response_json($array)
{
	$ci =& get_instance();
	$ci->output->set_content_type('application/json')->set_output(json_encode($array));
}