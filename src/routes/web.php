<?php

$basePath = getenv('BASE_PATH');
$uri = str_replace($basePath, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));


session_start();

if (!in_array($uri, ['', '/', '/home', '/signin', '/signup', '/google-callback', '/logout']) && !isset($_SESSION['user'])) {
    header('Location: signin');
    exit;
}
switch ($uri) {

    case '':
    case '/':
    case '/home':
        require __DIR__ . '/../controllers/controllers-frontend/controller-home/HomeController.php';
        (new HomeController)->index();
        break;

    case '/signin':
        require __DIR__ . '/../views/pages/client/content-auth/signin.php';
        break;
    case '/signup':
        require __DIR__ . '/../views/pages/client/content-auth/signup.php';
        break;
    case '/logout';
        require __DIR__ . '/../views/pages/client/content-auth/logout.php';
        break;
    case '/google-callback';
        require __DIR__ . '/../views/pages/client/content-auth/google-callback.php';
        break;
    default:
        http_response_code(404);
        echo "Page Not Found";
        break;
}
