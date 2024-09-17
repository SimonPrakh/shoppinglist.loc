<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('CategoryModel');
	}

	// Список всех категорий
	public function index()
	{
		// Получаем все категории из модели
		$data['categories'] = $this->CategoryModel->get_all_categories();

		// Устанавливаем заголовок ответа как JSON и возвращаем данные в формате JSON
		$this->output
			->set_content_type('application/json') // Устанавливаем заголовок Content-Type как JSON
			->set_status_header(200) // Устанавливаем статус ответа 200 OK
			->set_output(json_encode($data, JSON_UNESCAPED_UNICODE)); // Кодируем данные в JSON с поддержкой Unicode
	}

	// Добавить категорию
	public function store()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('name', 'Название', 'required');

		if ($this->form_validation->run() === FALSE)
		{
			// Загрузка формы добавления
			$this->load->view('categories/add');
		}
		else
		{
			$data = array(
				'name' => $this->input->post('name'),
			);
			$this->CategoryModel->add_category($data);
			redirect('category');
		}
	}

	// Редактировать категорию
	public function edit($id)
	{
		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['category'] = $this->CategoryModel->get_category_by_id($id);

		$this->form_validation->set_rules('name', 'Название', 'required');

		if ($this->form_validation->run() === FALSE)
		{
			// Загрузка формы редактирования
			$this->load->view('categories/edit', $data);
		}
		else
		{
			$update_data = array(
				'name' => $this->input->post('name'),
			);
			$this->CategoryModel->update_category($id, $update_data);
			redirect('category');
		}
	}

	// Удалить категорию
	public function delete($id)
	{
		$this->CategoryModel->delete_category($id);
		redirect('category');
	}
}
