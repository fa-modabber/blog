<?php

include(__DIR__ . "/../includes/config.php");
include(__DIR__ . "/../includes/db.php");
include(__DIR__ . "/../includes/functions.php");
include "./layout/includes/header.php";
include "./layout/includes/sidebar.php";

$userId = 1;
$categories = $db->query("SELECT * FROM categories ");
$posts = $db->query("SELECT * FROM posts ORDER BY id DESC LIMIT 5");
$comments = $db->prepare("
    SELECT comments.*
    FROM comments
    JOIN posts ON comments.post_id = posts.id
    WHERE posts.user_id = :user_id
");
$comments->execute(['user_id' => $userId]);
?>


<!-- Main Section -->
<div class="main col-md-9 col-lg-10">
  <h1>Recent Articles</h1>
  <?php if ($posts->rowCount() > 0): ?>
    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">Date</th>
            <th scope="col">Title</th>
            <th scope="col">Writer</th>
            <th scope="col">Operation</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($posts as $post): ?>
            <tr>
              <td><?= $post['created_at'] ?></td>
              <td><?= $post['title'] ?></td>
              <td>Otto</td>
              <td>
                <div class="d-grid gap-2 d-md-block">
                  <button type="button" class="btn btn-danger">
                    Delete
                  </button>
                  <button type="button" class="btn btn-secondary">
                    Edit
                  </button>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="col">
      <div class="alert alert-danger" role="alert">
        no post found!
      </div>
    </div>
  <?php endif ?>


  <h1>Recent Comments</h1>
  <?php if ($comments->rowCount() > 0): ?>
    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">Name</th>
            <th scope="col">Comment Text</th>
            <th scope="col">Status</th>
            <th scope="col">Operation</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($comments as $comment): ?>
            <tr>
              <td><?= $comment['author'] ?></td>
              <td><?= $comment['body'] ?></td>
              <td></td>
              <td>
                <div class="d-grid gap-2 d-md-block">
                  <?php if ($comment['is_accepted'] == 0): ?>
                    <button type="button" class="btn btn-secondary">
                      Not-accepted
                    </button>
                  <?php else: ?>
                    <a class="btn btn-success" href="index.php?entity=comment&action=accept&id=<?= $comment['id'] ?>">accepted</a>
                  <?php endif; ?>
                  <a class="btn btn-danger" href="index.php?entity=comment&action=delete&id=<?= $comment['id'] ?>">Delete</a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="col">
      <div class="alert alert-danger" role="alert">
        no comment found!
      </div>
    </div>
  <?php endif; ?>

  <h1>Categories</h1>
  <?php if ($categories->rowCount() > 0): ?>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Title</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($categories as $category): ?>
            <tr>
              <td>1</td>
              <td><?= $category['title'] ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="col">
      <div class="alert alert-danger" role="alert">
        no category found!
      </div>
    </div>
  <?php endif; ?>
</div>
</div>
</div>
</section>

<?php
include "./layout/includes/footer.php";

?>