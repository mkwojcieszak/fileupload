<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        if (isset($_POST['submit'])) {
            $file = $_FILES['file'];
            $fileName = $file['name'];
            $fileExt =  strtolower(substr($fileName, strpos($fileName, ".") + 1));
            $fileError = $file['error'];
            $allowedExts = array('jpg', 'png');

            if (in_array($fileExt, $allowedExts)) {
                if ($fileError === 0) {

                    $newFileName = uniqid('', true).'.'.$fileExt;
                    $fileDestination = 'uploads/'.$newFileName;
                    move_uploaded_file($file['tmp_name'], $fileDestination);
                    echo "<img src='$fileDestination'/>";

                    if ($fileExt == 'jpg')
                        $original_image = imagecreatefromjpeg($fileDestination);
                    elseif ($fileExt == 'png')
                        $original_image = imagecreatefrompng($fileDestination);

                    $original_width = imagesx($original_image);
                    $original_height = imagesy($original_image);
                
                    $new_width = $original_width / 1.5;
                    $new_height = $original_height / 1.5;

                    $new_image = imagecreatetruecolor($new_width, $new_height);
                    imagecopyresampled($new_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);

                    imagejpeg($new_image, $fileDestination);

                } else {
                    echo "Upload error.";
                }
            } else {
                echo "Uploaded file has an extension $fileExt, only png and jpg allowed.";
            }
        }
    ?>
    
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="file">
        <br>
        <button type="submit" name="submit">Upload</button>
    </form>
</body>
</html>