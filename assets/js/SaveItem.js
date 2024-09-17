$(document).ready(function() {
	loadEditCategories()
	loadItems(); // Загрузка товаров при загрузке страницы

	// Показать модальное окно для редактирования товара
	$(document).on('click', '.edit-item', function() {
		currentItemId = $(this).data('id'); // Сохранить ID товара
		loadItemDetails(currentItemId); // Загрузить детали товара для редактирования
	});

	// Обработчик кнопки сохранения изменений
	$('#saveEditItemBtn').click(function() {
		let itemData = {
			id: $('#editItemId').val(),
			name: $('#editItemName').val(),
			category: $('#editItemCategory').val(),
			status: $('#editItemStatus').val()
		};
		updateItem(itemData); // Вызов функции для обновления товара
	});
});

// Функция для загрузки категорий и заполнения выпадающего списка
function loadEditCategories() {
	fetch('index.php/category') // URL к методу получения категорий
.then(response => response.json())
		.then(data => {
			const categorySelect = document.getElementById('editItemCategory');
			categorySelect.innerHTML = ''; // Очистка списка перед заполнением

			// Заполнение выпадающего списка категориями
			data.categories.forEach(category => {
				const option = document.createElement('option');
				option.value = category.id;
				option.textContent = category.name;
				categorySelect.appendChild(option);
			});
		})
		.catch(error => {
			console.error('Ошибка при загрузке категорий:', error); // Обработка ошибок
		});
}

// Функция для загрузки деталей товара для редактирования
function loadItemDetails(itemId) {
	// Сначала загрузите категории, затем детали товара
	fetch('index.php/item/getItem/' + itemId)
	.then(response => response.json())
			.then(data => {
				// Заполняем форму данными товара
				$('#editItemName').val(data.name);
				$('#editItemCategory').val(data.category_id);
				$('#editItemStatus').val(data.status);
				$('#editItemId').val(data.id);
				$('#editModal').modal('show'); // Показать модальное окно редактирования
			})
			.catch(error => {
				console.error('Ошибка при загрузке деталей товара:', error); // Обработка ошибок
			});


}

// Функция для обновления товара
function updateItem(itemData) {
	fetch('http://shoppinglist.loc/index.php/item/update/' + itemData.id, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
		},
		body: JSON.stringify(itemData),
	})
		.then(response => {
			if (!response.ok) {
				// Если статус не 200-299, выбрасываем ошибку
				return response.text().then(text => { throw new Error(text) });
			}
			return response.json(); // Преобразование ответа в JSON
		})
		.then(data => {
			console.log(data.message); // Сообщение об успешном обновлении
			// Дополнительные действия, например закрытие модального окна и обновление списка
			$('#editModal').modal('hide');
			loadItems();
		})
		.catch(error => {
			console.error('Ошибка при обновлении товара:', error); // Обработка ошибок
		});
}

