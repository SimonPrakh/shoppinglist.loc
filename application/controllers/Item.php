<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item extends CI_Controller {


	public function __construct()
	{
		parent::__construct();
		$this->load->model('ItemModel');
		$this->load->model('CategoryModel');
		$this->load->helper('url'); // Загрузка URL-хелпераsss
	}

	public function index()
	{
		$data['items'] = $this->ItemModel->get_all_items();
		$this->load->view('items/index', $data);
	}
	public function getItem($id)
	{
		// Получаем товар по ID
		$item = $this->ItemModel->get_item_by_id($id);

		// Проверяем, найден ли товар
		if ($item) {
			// Отправляем ответ в формате JSON
			$this->output
					  ->set_content_type('application/json')
					  ->set_status_header(200)
					  ->set_output(json_encode($item, JSON_UNESCAPED_UNICODE));
		} else {
			// Отправляем ошибку, если товар не найден
			$this->output
				->set_content_type('application/json')
				->set_status_header(404)
				->set_output(json_encode(['message' => 'Товар не найден']));
		}
	}

	// Форма добавления новой покупки
	public function create()
	{
		$this->load->view('items/create');
	}

	// Добавление новой записи
	public function store()
	{
		// Пример отладочного вывода для проверки попадания в контроллер
		log_message('debug', 'Метод store в контроллере Item вызван.');

		// Код для обработки данных и сохранения товара
		$data = array(
			'name' => $this->input->post('name'),
			'category_id' => $this->input->post('category'),
			'status' => $this->input->post('status'),
		);

		// Проверка получения данных
		if (!$data['name'] || !$data['category_id'] || !$data['status']) {
			$this->output
				->set_content_type('application/json')
				->set_status_header(400)
				->set_output(json_encode(['message' => 'Недостаточно данных для сохранения товара']));
			return;
		}

		// Добавление товара через модель
		// Убедитесь, что метод insert_item существует в вашей модели
		if ($this->ItemModel->insert_item($data)) {
			// Отправка успешного ответа с отладочной информацией
			$this->output
				->set_content_type('application/json')
				->set_status_header(200)
				->set_output(json_encode(['message' => 'Товар успешно добавлен', 'debug' => 'Метод store выполнен успешно']));
		} else {
			// Обработка ошибки сохранения
			$this->output
				->set_content_type('application/json')
				->set_status_header(500)
				->set_output(json_encode(['message' => 'Ошибка при добавлении товара']));
		}
	}


	// Удаление записи
	public function delete($id)
	{
		// Проверяем, существует ли товар с таким ID
		if ($this->ItemModel->delete_item($id)) { // Убедитесь, что метод delete_item существует в модели
			$this->output
				->set_content_type('application/json')
				->set_status_header(200)
				->set_output(json_encode(['message' => 'Товар успешно удален']));
		} else {
			$this->output
				->set_content_type('application/json')
				->set_status_header(404)
				->set_output(json_encode(['message' => 'Товар не найден']));
		}
	}


	// Пометка записи как купленной
	public function mark_as_purchased($id)
	{
		$this->ItemModel->update_item($id, ['status' => 'куплено']);
		redirect('items');
	}
	public function loadItems()
	{
		// Получаем все товары через модель
		$items = $this->ItemModel->get_all_items();

		// Отправляем данные в формате JSON
		$this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode(['items' => $items], JSON_UNESCAPED_UNICODE));
	}
	public function update($id)
	{
		// Получаем данные из тела запроса
		$data = json_decode(file_get_contents('php://input'), true);

		// Проверяем, получены ли все необходимые данные
		if (empty($data['name']) || empty($data['category']) || !isset($data['status'])) {
			$this->output
				->set_content_type('application/json')
				->set_status_header(400)
				->set_output(json_encode(['message' => 'Некорректные данные для обновления товара']));
			return;
		}

		// Подготовка данных для обновления
		$update_data = [
			'name' => $data['name'],
			'category_id' => $data['category'],
			'status' => $data['status']
		];

		// Обновляем товар с помощью модели
		if ($this->ItemModel->update_item($id, $update_data)) {
			$this->output
				->set_content_type('application/json')
				->set_status_header(200)
				->set_output(json_encode(['message' => 'Товар успешно обновлен']));
		} else {
			$this->output
				->set_content_type('application/json')
				->set_status_header(500)
				->set_output(json_encode(['message' => 'Ошибка при обновлении товара']));
		}
	}
	// Функция для обработки JSON запроса
	public function fetch_sorted_items()
	{
		// Получаем JSON данные из тела запроса
		$input_data = json_decode(file_get_contents('php://input'), true);

		// Проверка наличия необходимых данных
		if (!isset($input_data['category_id']) || !isset($input_data['purchase'])) {
			$this->output
				->set_content_type('application/json')
				->set_status_header(400)
				->set_output(json_encode(['error' => 'Некорректные данные запроса']));
			return;
		}

		// Извлекаем данные из запроса
		$category_id = $input_data['category_id'];
		$purchase = $input_data['purchase'];

		// Вызов функции модели с переданными параметрами
		$items = $this->ItemModel->get_items_by_category_and_status($category_id, $purchase);

		// Возвращаем JSON ответ с данными
		$this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode(['items' => $items], JSON_UNESCAPED_UNICODE));
	}


}
