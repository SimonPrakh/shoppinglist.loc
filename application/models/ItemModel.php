<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ItemModel extends CI_Model {

	protected $table = 'items'; // Имя таблицы

	public function __construct()
	{
		parent::__construct();
		$this->load->database(); // Загрузка библиотеки базы данных
	}

	// Получение всех записей
	public function get_all_items()
	{

			$this->db->select('items.*, categories.name as category_name');
			$this->db->from($this->table);
			$this->db->join('categories', 'categories.id = items.category_id');
			return $this->db->get()->result_array();
		}

	// Получение записи по ID
	public function get_item($id)
	{
		return $this->db->get_where($this->table, ['id' => $id])->row_array();
	}
	public function get_item_by_id($id)
	{
		return $this->db->where('id', $id)->get('items')->row_array(); // Таблица 'items'
	}


	// Добавление новой записи
	public function insert_item($data)
	{
		return $this->db->insert($this->table, $data);
	}

	// Обновление записи по ID
	public function update_item($id, $data)
	{
		$this->db->where('id', $id);
		return $this->db->update($this->table, $data);
	}

	// Удаление записи по ID
	public function delete_item($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete($this->table);
	}

	// Функция для получения товаров с фильтрацией по категории и статусу
	public function get_items_by_category_and_status($category_id = null, $status = null)
	{
		$this->db->select('items.id, items.name, items.status, items.created_at, categories.name as category_name');
		$this->db->from($this->table);
		$this->db->join('categories', 'items.category_id = categories.id', 'inner'); // INNER JOIN с таблицей категорий

		// Фильтрация по ID категории, если указана и не пустая
		if ($category_id !== null && $category_id !== '') {
			$this->db->where('categories.id', $category_id);
		}

		// Фильтрация по статусу, если указан и не пустой
		if ($status !== null && $status !== '') {
			$this->db->where('items.status', $status);
		}

		// Сортировка по имени категории и статусу
		$this->db->order_by('categories.name', 'ASC');
		$this->db->order_by('items.status', 'ASC');

		return $this->db->get()->result_array();
	}
}
