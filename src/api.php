<?php


    use Controllers\ChatRoomController;
    use Controllers\UserController;
    use Controllers\MessageController;

    require __DIR__ . "/inc/bootstrap.php";

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode('/', $uri);
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header('Access-Control-Allow-Origin: http://localhost:3000');
        header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Authorization, Accept, Client-Security-Token, Accept-Encoding');
        header('Access-Control-Max-Age: 1000');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Headers: token, Content-Type');
        header('Access-Control-Max-Age: 1728000');
        header('Content-Length: 0');
        header('Content-Type: text/plain');
        die();
    }
    header('Access-Control-Allow-Origin: http://localhost:3000');
    header('Access-Control-Allow-Credentials: true');
    header('Content-Type: application/json');

    if (isset($uri[2]) ){
        switch ($uri[2]){
            case 'user':
                $userController = new UserController();
                $userController->{$uri[3] . 'Action'}();
                break;
            case 'chatroom':
                $chatRoomController = new ChatRoomController();
                $chatRoomController->{$uri[3] . 'Action'}();
                break;
            case 'message':
                $messageController = new MessageController();
                $messageController->{$uri[3] . 'Action'}();
                break;
            default:
                header("HTTP/1.1 404 Not Found");
                exit();
        }
    }
    else {
        header("HTTP/1.1 404 Not Found");
        exit();
    }