<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends CI_Controller {

	/**
	 * Main Controller
	 */

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Client_model', 'Client');
		$this->load->helper('form');
		$this->load->library('form_validation');
	}

	public function index()
	{
		$data = array(
			'description' => 'Instant Print'
		);

		$this->load->view('layouts/header', $data);
		$this->load->view('client__index');
		$this->load->view('layouts/footer');
	}

	public function join()
	{
		if ($this->Client->is_current_valid()) redirect('client/desk');

		$this->form_validation->set_rules('name', 'Name', 'required|min_length[5]|max_length[50]');

		if ($this->form_validation->run())
		{
			$token = $this->Client->new_token($this->input->post('name'));

			if ($token !== FALSE AND $this->Client->login($token))
			{
				$data = array(
					'title' => 'Join success',
					'description' => 'Instant Print',
					'token' => $token
				);

				$this->load->view('layouts/header', $data);
				$this->load->view('client__join_splash');
				$this->load->view('layouts/footer');
			}
		}
		else
		{
			$data = array(
				'title' => 'Join',
				'description' => 'Instant Print'
			);

			$this->load->view('layouts/header', $data);
			$this->load->view('client__join');
			$this->load->view('layouts/footer');
		}	
	}

	public function in()
	{
		if ($this->Client->is_current_valid()) redirect('client/desk');

		$this->form_validation->set_rules('token', 'Token', 'required');

		if ($this->form_validation->run())
		{
			if ($this->Client->login($this->input->post('token')))
			{
				redirect('client/desk');
			}
			else
			{
				$message = '<div class="message message-error">Token is invalid.</div>';
			}
		}

		$data = array(
			'title' => 'Check-in',
			'description' => 'Instant Print',
			'message' => (isset($message) ? $message : '')
		);

		$this->load->view('layouts/header', $data);
		$this->load->view('client__in');
		$this->load->view('layouts/footer');	
	}

	public function out()
	{
		$this->session->unset_userdata('token');

		redirect();
	}

	public function desk()
	{
		if ( ! $this->Client->is_current_valid()) self::out();

		$client = $this->Client->get();

		$data = array(
			'title'			=> 'Desk',
			'description'	=> 'Instant Print',
			'client'		=> $client,
			'message'		=> $this->session->flashdata('message'),
			'files'			=> $this->Client->get_files()
		);

		$this->load->view('layouts/header', $data);
		$this->load->view('client__desk');
		$this->load->view('layouts/footer');
	}

	public function do_upload()
	{
		if ( ! $this->Client->is_current_valid()) self::out();

		$config = array(
			'upload_path'		=> './uploads/'.$this->session->userdata('token'),
			'allowed_types'		=> 'txt',
			'max_size'			=> 200,
			'encrypt_name'		=> TRUE,
			'file_ext_tolower'	=> TRUE
		);

		if (( ! file_exists($config['upload_path'])) OR ( ! is_dir($config['upload_path'])))
		{
			mkdir($config['upload_path'], 0777, TRUE);
		}

		$this->load->library('upload', $config);

		if ($this->upload->do_upload('clientfile') AND $this->Client->save_file($this->upload->data('orig_name'), $this->upload->data('file_name')))
		{
			$this->session->set_flashdata('message', '<div class="message message-success">Success.</div>');
		}
		else
		{
			$this->session->set_flashdata('message', '<div class="message message-error">' . $this->upload->display_errors('', '') . '</div>');
		}

		redirect('client/desk');
	}

	public function load_file($identifier = NULL)
	{
		if ( ! $this->Client->is_current_valid()) self::out();

		$file = $this->Client->blob_file($identifier);

		if($file !== FALSE)
		{
			header('Content-Disposition: inline; filename="'.$file['name'].'"');
			header('Content-Type: '.$file['type']);
			header('Content-Length: '.$file['size']);
			echo $file['content'];
		}
		else
		{
			redirect('client/desk');
		}
	}

	public function print_file($identifier = NULL)
	{
		if ( ! $this->Client->is_current_valid()) self::out();

		if ($this->Client->print_file($identifier))
		{
			$this->session->set_flashdata('message', '<div class="message message-success">Your file has been queued.</div>');
		}
		else
		{
			$this->session->set_flashdata('message', '<div class="message message-error">Failed. Please try again.</div>');
		}

		redirect('client/desk');
	}

}
