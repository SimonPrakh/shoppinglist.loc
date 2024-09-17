$(document).ready(function() {
	// Показать модальное окно для добавления категории
	$('#addCategoryBtn').click(function() {
		$('#addCategoryForm')[0].reset(); // Сброс формы
		$('#addCategoryModal').modal('show'); // Показать модальное окно
	});

	// Обработчик для кнопки сохранения категории
	$('#saveCategoryBtn').click(function() {
		const categoryName = $('#categoryName').val().trim();

		// Проверка, что название категории не пустое
		if (categoryName === '') {
			alert('Введите название категории');
			return;
		}

		saveCategory(categoryName); // Вызов функции для сохранения категории
	});
});

// Функция для сохранения категории
function saveCategory(categoryName) {
	fetch('http://shoppinglist.loc/index.php/category/store', { // Убедитесь, что URL правильный для вашего контроллера
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify({ name: categoryName })
	})
		.then(response => {
			if (!response.ok) {
				return response.text().then(text => { throw new Error(text) });
			}
			return response.json();
		})
		.then(data => {
			console.log(data.message); // Выводим сообщение об успешном добавлении
			$('#addCategoryModal').modal('hide'); // Скрыть модальное окно после добавления
			loadCategories(); // Обновить список категорий
		})
		.catch(error => {
			console.error('Ошибка при добавлении категории:', error); // Обработка ошибок
		});
}
