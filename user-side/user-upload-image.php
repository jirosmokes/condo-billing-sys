<?php
function upload_image($username, $conn) {
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $image = $_FILES["image"];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
       
        $check = getimagesize($image["tmp_name"]);
        if ($check === false) {
            return "file is not an image.";
        }

       
        if ($image["size"] > 500000) {
            return "sorry, your file is too large.";
        }

       
        $allowedFormats = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowedFormats)) {
            return "sorry, only jpg, jpeg, png & gif files are allowed.";
        }

        
        if (file_exists($target_file)) {
            return "sorry, file already exists.";
        }

        if (move_uploaded_file($image["tmp_name"], $target_file)) {
            
            $sql = "UPDATE users SET image = ? WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $image["name"], $username);
            if ($stmt->execute()) {
                $stmt->close();
                return "the file " . htmlspecialchars(basename($image["name"])) . " has been uploaded.";
            } else {
                $stmt->close();
                return "sorry, there was an error updating your profile.";
            }
        } else {
            return "sorry, there was an error uploading your file.";
        }
    } else {
        return "no file was uploaded or there was an upload error.";
    }
}
?>
