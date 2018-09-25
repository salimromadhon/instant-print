<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	/**
	 * Admin Controller
	 */

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Admin_model', 'Admin');
		$this->load->helper('form');
		$this->load->library('form_validation');
	}

	public function out()
	{
		$this->session->unset_userdata('username');
		$this->session->unset_userdata('password');

		redirect();
	}

	public function desk()
	{
		if ( ! $this->Admin->is_current_valid()) self::out();

		$data = array(
			'title'			=> 'Desk',
			'description'	=> 'Instant Print',
			'message'		=> $this->session->flashdata('message'),
			'files'			=> $this->Admin->get_files()
		);

		$this->load->view('layouts/header', $data);
		$this->load->view('admin__desk');
		$this->load->view('layouts/footer');
	}

	public function lab_si()
	{
		if ($this->Admin->is_current_valid()) redirect('admin/desk');

		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run())
		{
			if ($this->Admin->login($this->input->post('username'), $this->input->post('password')))
			{
				redirect('admin/desk');
			}
			else
			{
				$message = '<div class="message message-error">Credential is invalid.</div>';
			}
		}

		$data = array(
			'title' => 'Check-in',
			'description' => 'Instant Print',
			'message' => (isset($message) ? $message : '')
		);

		$this->load->view('layouts/header', $data);
		$this->load->view('admin__lab_si');
		$this->load->view('layouts/footer');	
	}

	public function load_file($token = NULL, $identifier = NULL)
	{
		if ( ! $this->Admin->is_current_valid()) self::out();

		$file = $this->Admin->blob_file($token, $identifier);

		if($file !== FALSE)
		{
			header('Content-Disposition: inline; filename="'.$file['name'].'"');
			header('Content-Type: '.$file['type']);
			header('Content-Length: '.$file['size']);
			echo $file['content'];
		}
		else
		{
			redirect('admin/desk');
		}
	}

	public function print_file($token = NULL, $identifier = NULL)
	{
		if ( ! $this->Admin->is_current_valid()) self::out();

		if ($this->Admin->print_file($token, $identifier))
		{
			$this->session->set_flashdata('message', '<div class="message message-success">The file has been printed.</div>');
		}
		else
		{
			$this->session->set_flashdata('message', '<div class="message message-error">Failed. Please try again.</div>');
		}

		redirect('admin/desk');
	}

}
