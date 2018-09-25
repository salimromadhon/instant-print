<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client_model extends CI_Model {

	public function is_current_valid()
	{
		$count = $this->db
				->where('token', $this->session->userdata('token'))
				->from('clients')
				->count_all_results();

		return ($count > 0);
	}

	public function new_token($name)
	{
		$name = trim(htmlentities($name));

		if ($name == NULL) return FALSE;

		do
		{
			$token = bin2hex(openssl_random_pseudo_bytes(64));

			$count = $this->db
				->where('token', $token)
				->from('clients')
				->count_all_results();

		} while ($count > 0);

		$query = $this->db->insert('clients', array(
			'token' => $token,
			'name' => $name,
			'created_at' => time()
		));

		if ($query !== FALSE) return $token;
	}

	public function get($token = NULL)
	{
		if ($token == NULL)
		{
			$token = $this->session->userdata('token');
		}

		return $this->db
			->get_where('clients', array('token' => $token))
			->row_array();
	}

	public function login($token)
	{
		$count = $this->db
				->where('token', $token)
				->from('clients')
				->count_all_results();

		if ($count > 0) {
			$this->session->set_userdata('token', $token);

			return TRUE;
		}

		return FALSE;
	}

	/**
	 * ---------------
	 * FILE PROCESSING
	 * ---------------
	 */

	public function save_file($name, $identifier)
	{
		$data = array(
			'client_token'	=> $this->session->userdata('token'),
			'name'			=> $name,
			'identifier'	=> $identifier,
			'progress'		=> 0,
			'created_at'	=> time()
		);

		return $this->db->insert('files', $data);
	}

	public function get_files()
	{
		return $this->db
			->select(array('name', 'identifier', 'progress', 'created_at'))
			->where(array('client_token' => $this->session->userdata('token')))
			->order_by('created_at', 'DESC')
			->get('files')
			->result_array();
	}

	public function get_file($identifier)
	{
		return $this->db->get_where('files', array('identifier' => $identifier))->row();
	}

	public function blob_file($identifier)
	{
		$this->load->helper('file');

		$path = './uploads/'.$this->session->userdata('token').'/'.$identifier;

		if(file_exists($path))
		{
			return array(
				'name' => self::get_file($identifier)->name,
				'type' => get_mime_by_extension($identifier),
				'size' => filesize($path),
				'content' => file_get_contents($path)
			);
		}

		return FALSE;
	}

	public function print_file($identifier)
	{
		return $this->db
			->set(array(
				'progress' => 1

			))
			->where(array(
				'client_token' => $this->session->userdata('token'),
				'identifier' => $identifier
			))
			->update('files');
	}

}
