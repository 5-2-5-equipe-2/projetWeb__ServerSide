<?php
    session_start();
    const PROJECT_ROOT_PATH = __DIR__ . "/../";

    // include main configuration file
    require_once PROJECT_ROOT_PATH . "/inc/config.php";

    // include the exceptions
    foreach (glob(PROJECT_ROOT_PATH . "exceptions/*") as $filename) {
        require_once $filename;
    }

    // include the models
    require_once PROJECT_ROOT_PATH . "Model/Database.php";
    foreach (glob(PROJECT_ROOT_PATH . "Model/*") as $filename) {
        if ($filename!= "Model/Database.php") {
            require_once $filename;
        }
    }

    // include the managers
    foreach (glob(PROJECT_ROOT_PATH . "manager/*") as $filename) {
        require_once $filename;
    }

    // include the controllers
    require_once PROJECT_ROOT_PATH . "Controller/Api/BaseController.php";
    foreach (glob(PROJECT_ROOT_PATH . "Controller/Api/*") as $filename) {
        if($filename != "Controller/Api/BaseController.php") {
            require_once $filename;
        }

    }
