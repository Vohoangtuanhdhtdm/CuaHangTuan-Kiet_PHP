<?php
session_start();

if (!isset($_SESSION['initialized'])) {
    $_SESSION['initialized'] = true;
}

// Định nghĩa các hằng số đường dẫn gốc để dễ require file
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('VIEW_PATH', ROOT_PATH . '/views');

// Tự động load các class trong thư mục app/
spl_autoload_register(function ($className) {
    $file = APP_PATH . '/' . str_replace('\\', '/', $className) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Nạp Router và điều hướng request
require_once APP_PATH . '/Core/Router.php';

$router = new Core\Router();

$router->get('/', 'HomeController@index');
$router->get('/login', 'AuthController@loginView');  
$router->get('/logout', 'AuthController@logout'); 
$router->get('/register', 'AuthController@registerView');
$router->get('/forgot-password', 'AuthController@forgotPasswordView');
$router->get('/profile', 'AuthController@profileView');  
$router->get('/product/{slug}', 'ProductController@detail');
$router->get('/cart', 'CartController@viewCart');
$router->get('/checkout', 'OrderController@checkoutView');
$router->get('/checkout/success/{code}', 'OrderController@successView'); // Route động
$router->get('/admin/dashboard', 'AdminController@dashboard');
$router->get('/admin/products', 'AdminProductController@index');
$router->get('/admin/products/create', 'AdminProductController@createView');
$router->get('/admin/products/edit/{id}', 'AdminProductController@editView');
$router->get('/admin/orders', 'AdminOrderController@index');
$router->get('/admin/orders/detail/{id}', 'AdminOrderController@detailView');
$router->get('/admin/categories', 'AdminCategoryController@index');
$router->get('/admin/posts', 'AdminPostController@index');
$router->get('/admin/posts/create', 'AdminPostController@createView');
$router->get('/admin/posts/edit/{id}', 'AdminPostController@editView');
$router->get('/profile/orders', 'OrderController@historyView');
$router->get('/profile/orders/{id}', 'OrderController@historyDetailView');
$router->get('/about', 'HomeController@about');
$router->get('/contact', 'HomeController@contact');
$router->get('/blog', 'BlogController@index');
$router->get('/blog/{slug}', 'BlogController@detail');

// $router->post('/api/order/place', 'OrderController@placeOrderAPI');
// $router->get('/order/success/{id}', 'OrderController@successView');



// --- ROUTE API (Xử lý AJAX) ---
$router->post('/api/admin/posts/create', 'AdminPostController@storeAPI');
$router->post('/api/admin/posts/update', 'AdminPostController@updateAPI');
$router->post('/api/admin/posts/delete', 'AdminPostController@deleteAPI');
$router->post('/api/admin/categories/create', 'AdminCategoryController@storeAPI');
$router->post('/api/admin/categories/update', 'AdminCategoryController@updateAPI');
$router->post('/api/admin/categories/delete', 'AdminCategoryController@deleteAPI');
$router->post('/api/admin/orders/status', 'AdminOrderController@updateStatusAPI');
$router->post('/api/admin/products/update', 'AdminProductController@updateAPI');
$router->post('/api/admin/products/delete', 'AdminProductController@deleteAPI');
$router->post('/api/admin/products/create', 'AdminProductController@storeAPI');
$router->post('/api/checkout', 'OrderController@processAPI');
$router->post('/api/cart/update', 'CartController@updateAPI');
$router->post('/api/cart/remove', 'CartController@removeAPI');
$router->post('/api/cart/add', 'CartController@addAPI');
$router->post('/api/auth/login', 'AuthController@loginAPI'); 
$router->get('/api/products', 'ProductController@getProductsAPI');      
$router->post('/api/auth/register', 'AuthController@registerAPI');
$router->post('/api/auth/forgot-password', 'AuthController@forgotPasswordAPI');
$router->post('/api/auth/profile/update', 'AuthController@updateProfileAPI');
$router->get('/api/auth/logout', 'AuthController@logout');

// Khởi chạy
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($requestUri);