$(document).ready(function() {
	// Загрузка категорий при загрузке страницы
	loadCategories();

	// Функция для загрузки категорий в селект
	function loadCategories() {
		fetch('http://shoppinglist.loc/index.php/category')
			.then(response => response.json())
			.then(data => {
				let categoriesHtml = '<option value="">Все категории</option>';
				data.categories.forEach(category => {
					categoriesHtml += `<option value="${category.id}">${category.name}</option>`;
				});
				$('#categoryFilter').html(categoriesHtml);
			})
			.catch(error => console.error('Ошибка при загрузке категорий:', error));
	}

	// Обработчик кнопки "Отфильтровать"
	$('#filterBtn').click(function() {
		const category = $('#categoryFilter').val(); // Получаем выбранную категорию
		const status = $('#statusFilter').val(); // Получаем выбранный статус

		// Вызов функции для загрузки отсортированных товаров через fetch
		fetchSortedItems(category, status);
	});

	// Обработчик кнопки "Сбросить фильтры"
	$('#resetBtn').click(function() {
		$('#categoryFilter').val(''); // Сброс селекта категории
		$('#statusFilter').val('');   // Сброс селекта статуса
		fetchSortedItems('', '');     // Загрузка всех товаров без фильтров
	});

	// Функция для отправки fetch-запроса и получения отсортированного массива
	function fetchSortedItems(category, status) {
		fetch('http://shoppinglist.loc/index.php/items/fetch_sorted', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify({
				category_id: category,
				purchase: status
			})
		})
			.then(response => {
				if (!response.ok) {
					throw new Error('Ошибка сети');
				}
				return response.json();
			})
			.then(data => {
				console.log(data.items); // Выводим массив в консоль
				updateItemsTable(data.items); // Обновляем таблицу с данными
			})
			.catch(error => console.error('Ошибка при загрузке товаров:', error));
	}

	// Функция для обновления таблицы с товарами
	function updateItemsTable(items) {
		let itemsHtml = '';
		items.forEach(item => {
			itemsHtml += `<tr>
                <td>${item.name}</td>
                <td>${item.category_name}</td>
                <td>${item.status}</td>
                <td>${item.created_at}</td>
                <td>
                    <button class="btn btn-warning btn-sm edit-item" data-id="${item.id}">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-danger btn-sm delete-item" data-id="${item.id}">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>`;
		});
		$('#itemsTable').html(itemsHtml); // Обновление таблицы с товарами
	}

	// Инициальная загрузка всех товаров без фильтров
	fetchSortedItems('', '');
});
