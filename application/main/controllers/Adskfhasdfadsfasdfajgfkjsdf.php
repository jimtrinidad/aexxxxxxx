<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

set_time_limit(0);

class Adskfhasdfadsfasdfajgfkjsdf extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		//i cant run email via command line
		// i'll just call via curl and change class and method to random name

		if (! $this->input->is_cli_request())
			show_404();

		$this->load->library('email');

	}

	public function index()
	{
		// Huh?
		show_404();
	}

	public function jasdfgkjgkjgasd()
	{
		$this->email->send_queue();
	}

	public function aldkslkjfgdsj()
	{
		$this->email->retry_queue();
	}
}