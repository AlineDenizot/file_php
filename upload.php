<form action="" method="post" enctype="multipart/form-data">
    <label for="imageUpload">Upload some pictures</label>  
    <input type="file" name="files[]" multiple="multiple" />
    
    <input type="submit" value="Upload">
</form>

<?php

if (!empty($_FILES['files']['name'][0])) {
    
    $files = $_FILES['files'];

    $uploaded = [];
    $failed = [];

    $allowed = ['jpg', 'png', 'gif'];

    foreach ($files['name'] as $position => $file_name) {

        $file_tmp = $files['tmp_name'][$position];
        $file_size = $files['size'][$position];
        $file_error = $files['error'][$position];

        $file_ext = explode('.',$file_name);
        $file_ext = strtolower(end($file_ext));

        if(in_array($file_ext, $allowed)) {


            if ($file_error === 0) {

                if($file_size <= 1000000 ) {

                    $file_name_new = uniqid('',true) . '.' . $file_ext;
                    $file_destination = 'uploads/' . $file_name_new;

                    if (move_uploaded_file($file_tmp, $file_destination)){
                      $uploaded[$position] = $file_destination;  
                    } else {
                        $failed[$position] = "[{$file_name}] failed to upload";
                    }

                } else {
                $failed[$position] = "[{$file_name}] is too large "; 
                }
            }

        } else {

            $failed[$position]="[{$file_name}]'s file extension is not allowed";
            
        }

    }

    if (!empty($uploaded)) {
        echo "Téléchargement réussi ! ";
    }
     if(!empty($failed)) {
        echo $failed[$position];
        }  
}

?>

<div class=images>

<?php
$dir = './uploads';
$files_liste = scandir($dir);
unset($files_liste[0]);
unset($files_liste[1]);

$deleteMode = null;

if (isset($_GET['id'])) {
    $deleteMode = $_GET['id'];
}

foreach ($files_liste as $key => $file) {
    if ($deleteMode && $deleteMode == $key) {
        $path = './uploads/' . $file;
        unlink($path);
        header('location:/upload.php');
    }
?> <img src="<?php echo './uploads/' . $file ?>" alt="Image téléchargé"/> 
<a href="?id=<?php echo $key ?>">Delete</a>
<?php
}

?> 
</div>




