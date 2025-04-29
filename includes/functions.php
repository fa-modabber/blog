<?php
function test_form_input($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

function flash($entity = null, $action = null, $result = null, $customMessage = null)
{
    if ($entity && $action && $result) {
        $key = "{$entity}_{$action}_{$result}";
        if ($customMessage) {
        } else {
            $entityName = ucfirst($entity);
            switch ($result) {
                case 'success':
                    $_SESSION['flash'][$key] = "<div class='alert alert-success'> {$entityName} {$action} operation was successful!</div>";
                    break;
                case 'error':
                    $_SESSION['flash'][$key] = "<div class='alert alert-danger'>There was a problem during {$action} {$entityName}.</div>";
                    break;
                case 'notFound':
                    $_SESSION['flash'][$key] = "<div class='alert alert-warning'>{$entityName} not found for {$action}.</div>";
                    break;
            }
        }
        return;
    }
    if (!empty($_SESSION['flash'])) {
        foreach ($_SESSION['flash'] as $key => $msg) {
            echo $msg;
        }
        unset($_SESSION['flash']);
    }
}


function imageUpload($file, $upload_dir)
{
    $error = validateImageUpload($file);
    if ($error) return $error;
    $imageName = time() . '_' . basename($file["name"]);
    $target_dir = $upload_dir . $imageName;
    if (move_uploaded_file($file['tmp_name'], $target_dir)) {
        return null;
    } else {
        return "Error in uploading the image ttt";
    }
}

function validateImageUpload($file)
{
    if ($file['error'] !== 0) return "Error in uploading the image";

    $check = getimagesize($file["tmp_name"]);
    if ($check == false) return "File is not an image.";

    $target_file = basename($file["name"]);
    $imageExt = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageExt, $allowedTypes)) return "Only jpg, jpeg, png and gif are allowed";

    if ($file['size'] > 3 * 1024 * 1024) return "image size should be less than 3MB";

    return null; // no error
}

function getPostById($id){

}

function getCategories(){
    
}