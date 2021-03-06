<?php


    use Controllers\ChatRoomController;
    use Controllers\UserController;
    use Controllers\MessageController;
    use Controllers\PixelController;
    use Controllers\GameController;
    use Controllers\ColorController;
    use Controllers\NumberController;

    require __DIR__ . "/inc/bootstrap.php";

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode('/', $uri);
    $origin = "http://" . $_SERVER['HTTP_HOST']. ":3000";
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header("Access-Control-Allow-Origin: $origin");
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
//    echo "Origin: $origin";
    header("Access-Control-Allow-Origin: $origin");
    header('Access-Control-Allow-Credentials: true');
    header('Content-Type: application/json');

    if (isset($uri[2])) {
        switch ($uri[2]) {
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
            case 'pixel':
                $messageController = new PixelController();
                $messageController->{$uri[3] . 'Action'}();
                break;
            case 'game':
                $gameController = new GameController();
                $gameController->{$uri[3] . 'Action'}();
                break;
            case 'color':
                $colorController = new ColorController();
                $colorController->{$uri[3] . 'Action'}();
                break;
            case 'number':
                $gameController = new NumberController();
                $gameController->{$uri[3] . 'Action'}();
                break;
            default:
                header("HTTP/1.1 404 Not Found");
                echo json_encode(array('error' => "The element {$uri[2]} can't be found in the Controllers","details" => "The available Controllers are: user, chatroom, message, pixel, game, color"));
                exit();
        }
    } else {
        header("HTTP/1.1 404 Not Found");
        exit();
    }