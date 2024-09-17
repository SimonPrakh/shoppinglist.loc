let currentItemId; // Переменная для хранения ID текущего удаляемого товара

$(document).ready(function() {

	// Показать модальное окно для удаления товара
	$(document).on('click', '.delete-item', function() {
		currentItemId = $(this).data('id'); // Сохранить ID товара
		$('#deleteModal').modal('show'); // Показать модальное окно
	});

	// Обработчик для кнопки подтверждения удаления
	$('#confirmDeleteBtn').click(function() {
		deleteItem(currentItemId); // Вызов функции для удаления товара
	});
});

// Функция для удаления товара
function deleteItem(itemId) {
	fetch('index.php/items/delete/'+ itemId, {
		method: 'DELETE'
	})
.then(response => {
		if (!response.ok) {
			throw new Error('Ошибка при удалении товара');
		}
		return response.json();
	})
		.then(data => {
			console.log(data.message); // Выводим сообщение об успешном удалении
			$('#deleteModal').modal('hide'); // Скрыть модальное окно после удаления
			loadItems(); // Обновить список товаров после удаления
		})
		.catch(error => {
			console.error('Ошибка при удалении товара:', error); // Обработка ошибок
		});
}
