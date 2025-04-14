<?php

include(__DIR__ . "/../includes/config.php");
include(__DIR__ . "/../includes/db.php");
include(__DIR__ . "/../includes/functions.php");
include "./layout/includes/header.php";
include "./layout/includes/sidebar.php";

$messages = [
  'post' => [
    'delete' => [
      'success' => "You successfully deleted a post!",
      'error'   => "Problem in deleting the post, try again!"
    ]
  ],
  'comment' => [
    'delete' => [
      'success' => "You successfully deleted a comment!",
      'error'   => "Problem in deleting the comment, try again!"
    ],
    'accept' => [
      'success' => "You successfully accepted a comment!",
      'error'   => "Problem in accepting the comment, try again!"
    ]
  ]
];

$actions = [
  'post' => [
    'delete' => 'DELETE FROM posts WHERE id = :id'
  ],
  'comment' => [
    'delete' => 'DELETE FROM comments WHERE id = :id',
    'accept' => 'UPDATE comments SET is_accepted = 1 WHERE id = :id'
  ]
];

if (isset($_GET['entity'], $_GET['action'], $_GET['id'])) {
  $entity = $_GET['entity'];
  $action = $_GET['action'];
  $id = $_GET['id'];

  if (isset($actions[$entity][$action])) {
    try {
      $query = $db->prepare($actions[$entity][$action]);
      $query->execute(['id' => $id]);
      $_SESSION[$entity][$action]['success'] = $messages[$entity][$action]['success'];
    } catch (PDOException $e) {
      $_SESSION[$entity][$action]['error'] = $messages[$entity][$action]['error'];
    }
  }
}

$postDeleteSuccess      = $_SESSION['post']['delete']['success'] ?? "";
$commentDeleteSuccess   = $_SESSION['comment']['delete']['success'] ?? "";
$commentAcceptSuccess   = $_SESSION['comment']['accept']['success'] ?? "";

unset($_SESSION['post']['delete']['success'], $_SESSION['comment']['delete']['success'], $_SESSION['comment']['accept']['success']);

$userId = 1;

$categories = $db->query("SELECT * FROM categories");

$posts = $db->query("SELECT * FROM posts ORDER BY id DESC LIMIT 5");

$comments = $db->prepare("
    SELECT comments.*
    FROM comments
    JOIN posts ON comments.post_id = posts.id
    WHERE posts.user_id = :user_id
    ORDER BY comments.id DESC
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
                  <a class="btn btn-secondary" href="edit.php">Edit</a>

                  <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Delete
                  </button>
                  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h1 class="modal-title fs-5" id="exampleModalLabel">Delete a Post</h1>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          Are you sure you want to delete this post?
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <a class="btn btn-danger" href="index.php?entity=post&action=delete&id=<?= $post['id'] ?>">Delete</a>
                        </div>
                      </div>
                    </div>
                  </div>
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
                    <a class="btn btn-secondary" href="index.php?entity=comment&action=accept&id=<?= $comment['id'] ?>">wait for accept</a>
                  <?php else: ?>
                    <button type="button" class="btn btn-success">accepted</button>
                  <?php endif; ?>
                  <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Delete
                  </button>
                  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h1 class="modal-title fs-5" id="exampleModalLabel">Delete a Comment</h1>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          Are you sure you want to delete this comment?
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <a class="btn btn-danger" href="index.php?entity=comment&action=delete&id=<?= $comment['id'] ?>">Delete</a>
                        </div>
                      </div>
                    </div>
                  </div>
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
          <?php foreach ($categories as $key => $category): ?>
            <tr>
              <td><?= $key + 1 ?></td>
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