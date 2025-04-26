<?php
include $_SERVER['DOCUMENT_ROOT'] . '/weblog-project/includes/config.php';
include(BASE_PATH . '/includes/db.php');
include(BASE_PATH . '/includes/functions.php');
include(BASE_PATH . '/admin-panel/layout/includes/header.php');
include(BASE_PATH . "/admin-panel/layout/includes/sidebar.php");

$userId = 1;
$categories = $db->query("SELECT * FROM categories");

// create post form handling
$title = $categoryId = $body = "";
$image = null;
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    // Validate inputs
    if (empty($_POST['title'])) {
        $_SESSION['errors']['title'] = "Title is required";
    } else {
        $title = test_form_input($_POST['title']);
    }

    if (empty($_POST['body'])) {
        $_SESSION['errors']['body'] = "Body is required";
    } else {
        $body = test_form_input($_POST['body']);
    }

    $categoryId = test_form_input($_POST['categoryId']);

    // Validate image
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== 4) {
        $file = $_FILES['image'];
        $upload_dir = "../../../uploads/posts/";
        $uploadResult = imageUpload($file, $upload_dir);

        if ($uploadResult !== null) {
            $_SESSION['errors']['image'] = $uploadResult;
        } else {
            $image = basename($file["name"]) . '_' . time();
        }
    } else {
        $_SESSION['errors']['image'] = "Image is required";
    }

    // If no errors, insert into database
    if (empty($_SESSION['errors'])) {
        $stmt = $db->prepare("INSERT INTO posts (title, category_id, image, body, user_id) VALUES (:title, :category_id, :image, :body, :user_id)");
        $stmt->execute([
            'title' => $title,
            'category_id' => $categoryId,
            'image' => $image,
            'body' => $body,
            'user_id' => $userId
        ]);
        $_SESSION['success'] = "You successfully created a post!";
    }
    header("Location: " . $_SERVER['PHP_SELF'], true, 303);
    exit();
}

$errors = [
    'title' => $_SESSION['errors']['title'] ?? '',
    'body' => $_SESSION['errors']['body'] ?? '',
    'categoryId' => $_SESSION['errors']['categoryId'] ?? '',
    'image' => $_SESSION['errors']['image'] ?? ''
];

$submitSuccess = $_SESSION['success'] ?? "";

unset($_SESSION['errors'], $_SESSION['success']);

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
?>

<!-- main section -->
<div class="main col-md-9 col-lg-10">
    <?php if (!empty($submitSuccess)): ?>
        <div class="alert alert-success" role="alert">
            <?= $submitSuccess ?>
        </div>
    <?php endif ?>
    <h1>create post</h1>
    <form method="post" action="" class="row g-3" enctype="multipart/form-data">
        <div class="col-sm-6 mb-4">
            <label for="exampleInputTitle" class="form-label">Title</label>
            <input type="text" class="form-control" id="exampleInputTitle" name="title">
            <div class="red-feedback">
                <?= $errors['title'] ?>
            </div>
        </div>
        <div class="col-sm-6 mb-4">
            <label for="exampleSelect" class="form-label">Category</label>
            <select class="form-select" aria-label="Default select example" id="exampleSelect" name="categoryId">
                <?php foreach ($categories as $category) : ?>
                    <option value="<?= $category['id'] ?>"><?= $category['title'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-sm-6 mb-4">
            <input type="file" class="form-control" name="image">
            <div class="red-feedback">
                <?= $errors['image'] ?>
            </div>
        </div>
        <div class="col-sm-12 mb-4">
            <label for="formControlTextarea" class="form-label">Post Body</label>
            <textarea class="form-control" id="FormControlTextarea" rows="3" name="body"></textarea>
            <div class="red-feedback">
                <?= $errors['body'] ?>
            </div>
        </div>
        <div>
            <button type="submit" class="btn btn-dark" name="submit">create</button>
        </div>
    </form>

</div>
</div>
</div>
</section>

<?php
include (BASE_PATH ."/admin-panel/layout/includes/footer.php");

?>