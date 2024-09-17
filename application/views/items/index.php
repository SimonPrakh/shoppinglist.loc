<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Shopping List</title>
	<!-- Bootstrap CSS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
	<h2>Список покупок</h2>

	<div class="mb-3">
		<button class="btn btn-primary" id="addItemBtn">Добавить товар</button>
		<button class="btn btn-secondary" id="addCategoryBtn">Добавить категорию</button>
	</div>


	<table class="table table-striped">
		<thead>
		<tr>
			<th>Название</th>
			<th>Категория</th>
			<th>Статус</th>
			<th>Дата добавления</th>
			<th>Действия</th>
		</tr>
		</thead>
		<tbody id="itemsTable">
		<?php if (!empty($items)): ?>
			<?php foreach ($items as $item): ?>
				<tr>
					<td><?php echo htmlspecialchars($item['name']); ?></td>
					<td><?php echo htmlspecialchars($item['category_name']); ?></td>
					<td><?php echo htmlspecialchars($item['status']); ?></td>
					<td><?php echo htmlspecialchars($item['created_at']); ?></td>
					<td>
						<button class="btn btn-warning btn-sm edit-item" data-id="<?php echo $item['id']; ?>">
							<i class="bi bi-pencil"></i> <!-- Иконка карандаша -->
						</button>
						<button class="btn btn-danger btn-sm delete-item" data-id="<?php echo $item['id']; ?>">
							<i class="bi bi-trash"></i> <!-- Иконка корзины -->
						</button>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td colspan="5" class="text-center">Нет данных для отображения</td>
			</tr>
		<?php endif; ?>
		</tbody>
	</table>
	<div class="row mb-3">
		<div class="col-md-3">
			<label for="categoryFilter" class="form-label">Категория</label>
			<select id="categoryFilter" class="form-select">
				<option value="">Все категории</option>
				<!-- Опции категорий будут загружены через AJAX -->
			</select>
		</div>
		<div class="col-md-3">
			<label for="statusFilter" class="form-label">Статус покупки</label>
			<select id="statusFilter" class="form-select">
				<option value="">Все</option>
				<option value="куплено">Куплено</option>
				<option value="не куплено">Не куплено</option>
			</select>
		</div>
		<div class="col-md-3 d-flex align-items-end">
			<button class="btn btn-primary" id="filterBtn">Отфильтровать</button>
<!--			<button class="btn btn-secondary ms-2" id="resetBtn">Сбросить фильтры</button>-->
		</div>
	</div>
</div>

<!-- Модальные окна -->
<!-- Модальное окно добавления/редактирования товара -->
<div class="modal fade" id="itemModal" tabindex="-1" aria-labelledby="itemModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="itemModalLabel">Добавить товар</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="itemForm">
					<div class="mb-3">
						<label for="itemName" class="form-label">Название товара</label>
						<input type="text" class="form-control" id="itemName" required>
					</div>
					<div class="mb-3">
						<label for="itemCategory" class="form-label">Категория</label>
						<select class="form-control" id="itemCategory">
							<!-- Опции категорий будут загружены через AJAX -->
						</select>
					</div>
					<div class="mb-3">
						<label for="itemStatus" class="form-label">Статус</label>
						<select class="form-control" id="itemStatus">
							<option value="не куплено">Не куплено</option>
							<option value="куплено">Куплено</option>
						</select>
					</div>
					<input type="hidden" id="itemId">
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
				<button type="button" class="btn btn-primary" id="saveItemBtn">Сохранить</button>
			</div>
		</div>
	</div>
</div>

<!-- Модальное окно добавления категории -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="categoryModalLabel">Добавить категорию</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="categoryForm">
					<div class="mb-3">
						<label for="categoryName" class="form-label">Название категории</label>
						<input type="text" class="form-control" id="categoryName" required>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
				<button type="button" class="btn btn-primary" id="saveCategoryBtn">Сохранить</button>
			</div>
		</div>
	</div>


</div>
<!-- Модальное окно для добавления категории -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addCategoryModalLabel">Добавить категорию</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="addCategoryForm">
					<div class="mb-3">
						<label for="categoryName" class="form-label">Название категории</label>
						<input type="text" class="form-control" id="categoryName" required>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
				<button type="button" class="btn btn-primary" id="saveCategoryBtn">Сохранить</button>
			</div>
		</div>
	</div>
</div>

<!-- Модальное окно для редактирования товара -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editModalLabel">Редактировать товар</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="editItemForm">
					<div class="mb-3">
						<label for="editItemName" class="form-label">Название товара</label>
						<input type="text" class="form-control" id="editItemName" required>
					</div>
					<div class="mb-3">
						<label for="editItemCategory" class="form-label">Категория</label>
						<select class="form-control" id="editItemCategory">
							<!-- Опции категорий будут загружены через AJAX -->
						</select>
					</div>
					<div class="mb-3">
						<label for="editItemStatus" class="form-label">Статус</label>
						<select class="form-control" id="editItemStatus">
							<option value="не куплено">Не куплено</option>
							<option value="куплено">Куплено</option>
						</select>
					</div>
					<input type="hidden" id="editItemId">
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
				<button type="button" class="btn btn-primary" id="saveEditItemBtn">Сохранить</button>
			</div>
		</div>
	</div>
</div>

<!-- Модальное окно для подтверждения удаления -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="deleteModalLabel">Подтвердить удаление</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				Вы уверены, что хотите удалить этот товар?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
				<button type="button" class="btn btn-danger" id="confirmDeleteBtn">Удалить</button>
			</div>
		</div>
	</div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url('assets/js/scripts.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/SaveItem.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/dleteitem.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/addcat.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/filtration.js'); ?>"></script>
</body>
</html>
<?php
