<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

	public function is_current_valid()
	{
		return (password_verify($this->session->userdata('username'), '$2y$10$qgSJEEuuQeCYAKRadcbyu.pP0AJ8aC9aXVfqB7WptbfVaimHFeb4q') AND password_verify($this->session->userdata('password'), '$2y$10$ItQdwLj3HyqkZdZfeT3VQeuxZ/fLj1o/O22XWl9Uw4WfqBOTaXcr6'));
	}

	public function login($username, $password)
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		if (password_verify($username, '$2y$10$qgSJEEuuQeCYAKRadcbyu.pP0AJ8aC9aXVfqB7WptbfVaimHFeb4q') AND password_verify($password, '$2y$10$ItQdwLj3HyqkZdZfeT3VQeuxZ/fLj1o/O22XWl9Uw4WfqBOTaXcr6'))
		{
			$this->session->set_userdata('username', $username);
			$this->session->set_userdata('password', $password);

			return TRUE;
		}

		return FALSE;
	}

	/**
	 * ---------------
	 * FILE PROCESSING
	 * ---------------
	 */

	public function get_files()
	{
		return $this->db
			->query('SELECT clients.name AS cname, files.name AS fname, files.client_token, files.identifier, files.progress, files.created_at FROM clients, files WHERE clients.token = files.client_token AND files.progress > 0 ORDER BY files.created_at DESC')
			->result_array();
	}

	public function get_file($token, $identifier)
	{
		return $this->db->get_where('files', array('client_token' => $token, 'identifier' => $identifier))->row();
	}

	public function blob_file($token, $identifier)
	{
		$this->load->helper('file');

		$path = './uploads/'.$token.'/'.$identifier;

		if(file_exists($path))
		{
			return array(
				'name' => self::get_file($token, $identifier)->name,
				'type' => get_mime_by_extension($identifier),
				'size' => filesize($path),
				'content' => file_get_contents($path)
			);
		}

		return FALSE;
	}

	public function print_file($token, $identifier)
	{
		return $this->db
			->set(array(
				'progress' => 2

			))
			->where(array(
				'client_token' => $token,
				'identifier' => $identifier
			))
			->update('files');
	}

}
