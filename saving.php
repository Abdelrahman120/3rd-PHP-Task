<?php
$errors = [];
$old_data = [];

// foreach ($_POST as  $K=>$V){
//     if(empty($v)){
//         $errors[$k]="{$k} is required";
//     }else{
//         $old_data[$k]=$v;
//     }
// }

if (empty($_POST['name'])) {
    $errors['name'] = "name is required";
} else {
    $old_data['name'] = $_POST['name'];
}

if (empty($_POST['email'])) {
    $errors['email'] = "email is required";
} else {
    $old_data['email'] = $_POST['email'];
}

if (empty($_POST['password'])) {
    $errors['password'] = "Password is required";
}

if (empty($_POST['conpassword'])) {
    $errors['conpassword'] = "confirm password is required";
}

if (empty($_POST['ext'])) {
    $errors['ext'] = "extention is required";
} else {
    $old_data['ext'] = $_POST['ext'];
}

if (empty($_FILES['pic']['tmp_name'])) {
    $errors['pic'] = "Image is required";
} else {
    $ext = pathinfo($_FILES['pic']['name'], PATHINFO_EXTENSION);
    if (!in_array($ext, ["jpg", "jpeg", "png"])) {
        $errors['pic'] = "Only JPG, JPEG, PNG files are allowed";
    }
}

if ($errors) {
    $errors = json_encode($errors);
    $url = "Location: index.php?errors={$errors}";
    if ($old_data) {
        $old_data = json_encode($old_data);
        $url .= "&old_data={$old_data}";
    }
    header($url);
} else {

    $old_id = file_get_contents('ids.txt');
    $old_id = (int)$old_id;
    $id = $old_id + 1;
    file_put_contents('ids.txt', $id);

    $temp_name = $_FILES['pic']['tmp_name'];
    $image_name = $_FILES['pic']['name'];
    $image_path = "images/{$id}.{$ext}";
    $saved = move_uploaded_file($temp_name, $image_path);
    
    $data = "{$id}:{$_POST['name']}:{$_POST['email']}:{$_POST['password']}:{$_POST['roomno']}:{$_POST['ext']}:{$image_path}\n";
    $filobj = fopen("Data.txt", 'a');
    if (is_resource($filobj)) {
        fwrite($filobj, $data);
        fclose($filobj);
        header('Location: login.php');
    }
}