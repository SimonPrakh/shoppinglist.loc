<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CategoryModel extends CI_Model {

	protected $table = 'categories';

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	// Получить все категории
	public function get_all_categories()
	{
		return $this->db->get($this->table)->result_array();
	}

	// Получить категорию по ID
	public function get_category_by_id($id)
	{
		return $this->db->where('id', $id)->get($this->table)->row_array();
	}

	// Добавить новую категорию
	public function add_category($data)
	{
		return $this->db->insert($this->table, $data);
	}

	// Обновить категорию
	public function update_category($id, $data)
	{
		return $this->db->where('id', $id)->update($this->table, $data);
	}

	// Удалить категорию
	public function delete_category($id)
	{
		return $this->db->where('id', $id)->delete($this->table);
	}
}
