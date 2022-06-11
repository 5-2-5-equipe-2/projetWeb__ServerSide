<?php
    require_once 'inc/bootstrap.php';

    use Auth\Exceptions\InvalidEmailException;
    use Auth\Exceptions\InvalidPasswordException;
    use Managers\UserManager;

//    $userManger = new UserManager();
//    try {
//        $userManger->createUser('testUser', "password");
//    } catch (InvalidEmailException $e) {
//        echo 'Invalid email';
//    } catch (InvalidPasswordException $e) {
//        echo 'Invalid password';
//    } catch (Exception $e) {
//        echo 'Error';
//    }
//    echo json_encode($_SESSION);
//    $userManger->login('testUser', "password");
//    $userModel = new \Models\UserModel();
//    echo $userModel->update("UPDATE user SET password = ? WHERE id = ?", ["ss", 1235, 2000]);
//    echo json_encode($_SESSION);
//    $userManger->logout();
//    echo json_encode($_SESSION);

    $pixelModel = new \Models\PixelModel();
//    echo $pixelModel->updatePixel(1,1,2,1);
    for ($x = 0; $x < 100000; $x++) {
        try {
            $pixelModel->createPixel(rand(0,10000), rand(0,10000), rand(1, 7), rand(1, 3));
        } catch (Exception) {
//                echo 'Error<br>';
        }
    }
