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



//db functions

function fetchAllPosts(PDO $db)
{
    try {
        $stmt = $db->query("SELECT * FROM posts ORDER BY id DESC");
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $posts;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}

function fetchAllPostsWithDetails(){
    
}

function fetchPostsByCategory(PDO $db, $categoryId)
{
    try {
        $stmt = $db->prepare("SELECT * FROM posts where category_id= :cid ORDER BY id DESC");
        $stmt->execute(['cid' => $categoryId]);
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $posts;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}

function fetchPostById(PDO $db, $postId)
{
    try {
        $stmt = $db->prepare("SELECT * FROM posts WHERE id=:id");
        $stmt->execute(['id' => $postId]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        return $post;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}

function fetchPostWithDetailsById(PDO $db, $postId)
{
    $query = "
    SELECT posts.*, categories.name AS category_name, users.first_name, users.last_name
    FROM posts
    JOIN categories ON posts.category_id = categories.id
    JOIN users ON posts.user_id = users.id
    WHERE posts.id = :id
";

    try {
        $stmt = $db->prepare($query);
        $stmt->execute(['id' => $postId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}

function fetchAllCategories(PDO $db)
{
    try {
        $stmt  = $db->query("SELECT * FROM categories");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $categories;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}

function fetchCategoryById(PDO $db, $categoryId) {}



function fetchUserById(PDO $db, $userId) {}
