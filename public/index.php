<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/services/auth.php';
require_once __DIR__ . '/../src/services/ticketService.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig = new \Twig\Environment($loader);

$twigData = ['session' => $_SESSION];

$page = $_GET['page'] ?? 'landing';

// Protect dashboard and tickets routes
$protected = ['dashboard', 'tickets'];

if (in_array($page, $protected) && !isset($_SESSION['ticketapp_session'])) {
    header('Location: ?page=login');
    exit;
}

// Handle logout
if ($page === 'logout') {
    session_destroy();
    header('Location: ?page=landing');
    exit;
}

// Handle login POST
if ($page === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $res = loginUser($email, $password);
    if ($res['success']) {
        $_SESSION['ticketapp_session'] = $res['user'];
        header('Location: ?page=dashboard');
        exit;
    } else {
        echo $twig->render('auth/login.twig', ['error' => $res['message']]);
        exit;
    }
}

// Handle signup POST
if ($page === 'signup' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $res = registerUser($email, $password);
    if ($res['success']) {
        $_SESSION['ticketapp_session'] = $res['user'];
        header('Location: ?page=dashboard');
        exit;
    } else {
        echo $twig->render('auth/signup.twig', ['error' => $res['message']]);
        exit;
    }
}

// Handle tickets CRUD POST
if ($page === 'tickets' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        if ($action === 'create') {
            createTicket($_POST['title'], $_POST['status']);
        }
        if ($action === 'update') {
            updateTicket($_POST['id'], $_POST['title'], $_POST['status']);
        }
        if ($action === 'delete') {
            deleteTicket($_POST['id']);
        }
    }
    header('Location: ?page=tickets');
    exit;
}

// Render templates
switch ($page) {
    case 'login':
        echo $twig->render('auth/login.twig', $twigData);
        break;
    case 'signup':
        echo $twig->render('auth/signup.twig', $twigData);
        break;
    case 'dashboard':
        $tickets = getTickets();
        echo $twig->render('dashboard.twig', array_merge($twigData, ['tickets' => $tickets]));
        break;
    case 'tickets':
        $tickets = getTickets();
        echo $twig->render('tickets/page.twig', array_merge($twigData, ['tickets' => $tickets]));
        break;
    default:
        echo $twig->render('landing.twig', $twigData);
}
