<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Устанавливаем маршрут по умолчанию
$route['default_controller'] = 'item'; // Контроллер по умолчанию

// Определяем маршрут для вывода всех товаров
$route['items'] = 'item/index';

$route['category'] = 'category/index';
$route['category/store'] = 'category/store';


// Определяем маршрут для добавления товара (store)
$route['items/store'] = 'item/store';

// Определяем маршрут для удаления товара
$route['items/delete/(:num)'] = 'item/delete/$1';

// Определяем маршрут для пометки товара как купленного
$route['items/mark_as_purchased/(:num)'] = 'item/mark_as_purchased/$1';

$route['item/getItem/(:num)'] = 'item/getItem/$1';
$route['item/update/(:num)'] = 'item/update/$1';

// Маршрут для ошибки 404
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['items/fetch_sorted'] = 'item/fetch_sorted_items';


