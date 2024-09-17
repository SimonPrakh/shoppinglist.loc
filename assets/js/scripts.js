console.log('lld')
$(document).ready(function() {
	// Загрузка данных при загрузке страницы

	loadCategories();

	// Показать модальное окно для добавления товара
	$('#addItemBtn').click(function() {
		$('#itemForm')[0].reset();
		$('#itemId').val('');
		$('#itemModalLabel').text('Добавить товар');
		$('#itemModal').modal('show');
	});


	// Сохранить товар
	$('#saveItemBtn').click(function() {
		let itemData = {
			id: $('#itemId').val(),
			name: $('#itemName').val(),
			category: $('#itemCategory').val(),
			status: $('#itemStatus').val()
		};
		saveItem(itemData);
	});

	// Сохранить категорию
	$('#saveCategoryBtn').click(function() {
		let categoryName = $('#categoryName').val();
		saveCategory(categoryName);
	});



});


// Функция для загрузки категорий в выпадающий список
function loadCategories() {
	$.ajax({
		url: 'index.php/category', // Убедитесь, что URL правильный для вашего контроллера
		method: 'GET',
		success: function(response) {
			let categoriesHtml = '';
			response.categories.forEach(category => {
				categoriesHtml += `<option value="${category.id}">${category.name}</option>`;
			});
			$('#itemCategory').html(categoriesHtml);
		}
	});
}

// Функция для сохранения товара
function saveItem(itemData) {
	$.ajax({
		url: 'index.php/item/store', // Убедитесь, что URL правильный для вашего контроллера
		method: 'POST',
		data: itemData,
		success: function(response) {
			$('#itemModal').modal('hide');
		}
	});
}

// Функция для сохранения категории
function saveCategory(categoryName) {
	$.ajax({
		url: 'index.php/item/saveCategory', // Убедитесь, что URL правильный для вашего контроллера
		method: 'POST',
		data: { name: categoryName },
		success: function(response) {
			$('#categoryModal').modal('hide');
			loadCategories();
		}
	});


}
function loadItems() {
	fetch('index.php/item/loadItems') // URL к методу loadItems в контроллере
.then(response => {
		if (!response.ok) {
			throw new Error(`Ошибка сети: ${response.status}`);
		}
		return response.json(); // Парсим JSON из ответа
	})
		.then(data => {
			// Очистка таблицы перед обновлением
			const itemsTable = document.getElementById('itemsTable');
			itemsTable.innerHTML = '';

			// Проверяем, есть ли товары
			if (data.items && data.items.length > 0) {
				// Генерация строк таблицы с данными товаров
				data.items.forEach(item => {
					const itemRow = document.createElement('tr');
					itemRow.innerHTML = `
                        <td>${item.name}</td>
                        <td>${item.category_name}</td>
                        <td>${item.status}</td>
                        <td>${item.created_at}</td>
                        <td>
                            <button class="btn btn-warning btn-sm edit-item" data-id="${item.id}">
                                <i class="bi bi-pencil"></i> <!-- Иконка карандаша -->
                            </button>
                            <button class="btn btn-danger btn-sm delete-item" data-id="${item.id}">
                                <i class="bi bi-trash"></i> <!-- Иконка корзины -->
                            </button>
                        </td>
                    `;
					itemsTable.appendChild(itemRow); // Добавление строки в таблицу
				});
			} else {
				// Если нет товаров, отображаем сообщение
				itemsTable.innerHTML = '<tr><td colspan="5" class="text-center">Нет товаров для отображения</td></tr>';
			}
		})
		.catch(error => {
			console.error('Ошибка при загрузке товаров:', error); // Обработка ошибок
		});
}
