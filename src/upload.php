<?php 
if(isset($_POST["submit"])) {
    // Set image placement folder
    $target_dir = "/var/www/html/images/";
    // Get file path
    $target_file = $target_dir . basename($_FILES["fileUpload"]["name"]);
    // Get file extension
    $imageExt = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    // Allowed file types
    $allowd_file_ext = array("jpg", "jpeg", "png");
    var_dump($_FILES);
    

    if (!file_exists($_FILES["fileUpload"]["tmp_name"])) {
       $resMessage = array(
           "status" => "alert-danger",
           "message" => "Select image to upload."
       );
    } else if (!in_array($imageExt, $allowd_file_ext)) {
        $resMessage = array(
            "status" => "alert-danger",
            "message" => "Allowed file formats .jpg, .jpeg and .png."
        );            
    } else if ($_FILES["fileUpload"]["size"] > 2097152) {
        $resMessage = array(
            "status" => "alert-danger",
            "message" => "File is too large. File size should be less than 2 megabytes."
        );
    } else if (file_exists($target_file)) {
        $resMessage = array(
            "status" => "alert-danger",
            "message" => "File already exists."
        );
    } else {
        if (move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $target_file)) {
            echo "The file ". basename($_FILES["fileUpload"]["name"]). " has been uploaded.";
            // $sql = "INSERT INTO user (file_path) VALUES ('$target_file')";
            // $stmt = $conn->prepare($sql);
            //  if($stmt->execute()){
            //     $resMessage = array(
            //         "status" => "alert-success",
            //         "message" => "Image uploaded successfully."
            //     );                 
            //  }
        } else {
            $resMessage = array(
                "status" => "alert-danger",
                "message" => "Image coudn't be uploaded."
            );
        }
    }

}
