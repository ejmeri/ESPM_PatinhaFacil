<?php

namespace helper;


class Upload {

    public function SaveFile($file = '')
    {
            $a = 0;
            $new = basename($file['name']);
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($file["name"]);
            
            $uploadOk = 1;


            // // Checar se o arquivo existe, se sim, laço para inserir numero na frente e checar novamente
            while (true) {
                
                if (file_exists($target_file)) {
                    // echo "Sorry, file already exists.";
                    $uploadOk = 0;
                    $target_file = $target_dir .'('.$a.')'. basename($file["name"]);
                    $new = '('.$a.')'.basename($file["name"]);
                    $a++;                    
                } else {
                    $uploadOk = 1;
                    break;
                }
            }
            
            // Check file size
            // if ($file["size"] > 500000) {
            //     echo "Sorry, your file is too large.";
            //     $uploadOk = 0;
            // }
            // Allow certain file formats
            // if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            // && $imageFileType != "gif" ) {
            //     echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            //     $uploadOk = 0;
            // }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($file["tmp_name"], $target_file)) {
                    return $new;
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }
}

?>
