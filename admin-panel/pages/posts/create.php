<?php
include(__DIR__ . "/../../../includes/config.php");
include(__DIR__ . "/../../../includes/db.php");
include(__DIR__ . "/../../../includes/functions.php");
include "../../layout/includes/header.php";
include "../../layout/includes/sidebar.php";

$categories = $db->query("SELECT * FROM categories");

// create post form handling
$title = $category_id = $body = "";
$image = null;
$formInputsToValidate = ['title', 'image', 'body'];
$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addPost'])) {
    foreach ($formInputsToValidate as $input) {
        if (empty($_POST[$input])) {
            $errors[$input] = "$input is required";
        } else {
            $file = $_FILES['image'];
            if ($file['error'] === 0) {
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array($file['type'], $allowedTypes)) {
                    if ($file['size'] < 3 * 1024 * 1024) {
                        //success
                        $title = test_form_input($_POST['title']);
                        $category_id = test_form_input($_POST['categoryId']);
                        $body = test_form_input($_POST['body']);

                        // image upload handling
                    } else {
                        $errors['image'] = "image size should be less than 3MB";
                    }
                } else {
                    $errors['image'] = "only jpg, jpeg, png and gif are allowed";
                }
            } else {
                $errors['image'] = "error in uploading the image";
            }
        }
    }
}

?>

<!-- main section -->
<div class="main col-md-9 col-lg-10">
    <h1>create post</h1>
    <form method="post" action="" class="row g-3" enctype="multipart/form-data">
        <div class="col-sm-6 mb-4">
            <label for="exampleInputTitle" class="form-label">Title</label>
            <input type="text" class="form-control" id="exampleInputTitle" name="title">
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
        </div>
        <div class="col-sm-12 mb-4">
            <label for="formControlTextarea" class="form-label">Post Body</label>
            <textarea class="form-control" id="FormControlTextarea" rows="3" name="body"></textarea>
        </div>
        <div>
            <button type="submit" class="btn btn-dark" name="addPost">create</button>
        </div>
    </form>

</div>
</div>
</div>
</section>

<?php
include "../../layout/includes/footer.php";
?>