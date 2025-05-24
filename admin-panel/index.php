<?php

require_once 'C:/xampp/htdocs/weblog-project/init.php';

//layout
include(BASE_PATH . '/admin-panel/pages/includes/header.php');
include(BASE_PATH . "/admin-panel/pages/includes/sidebar.php");

$userId = $_SESSION['user_id'];

try {
  $categories = fetchCategories($db);
} catch (PDOException $e) {
  flash('category', 'fetch', 'error');
}

try {
  $posts = fetchPostsByUserId($db, $userId);
  if (!empty($posts)) {
    $posts = array_slice($posts, 0, 5);
  }
  //  else flash('post', 'fetch', 'not_found');
} catch (PDOException $e) {
  flash('post', 'fetch', 'error');
}

try {
  $comments = fetchCommentsByUserId($db, $userId);
} catch (PDOException $e) {
  flash('comment', 'fetch', 'error');
}

?>


<!-- Main Section -->
<div class="main col-md-9 col-lg-10 mt-3">
  <?php
  display_flash_messages();

  ?>

  <div class="d-flex justify-content-center align-items-center">
    <a class="btn btn-dark" href="<?= BASE_URL ?>/admin-panel/pages/posts/view/create.php"> create a post</a>
  </div>
  <h1>Recent Posts</h1>
  <?php if (!empty($posts)): ?>
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
              <td>
                <a href="<?= BASE_URL ?>/admin-panel/pages/posts/view/single.php?id=<?= $post['id'] ?>"><?= $post['title'] ?></a>
              </td>
              <td>Otto</td>
              <td>
                <div class="d-grid gap-2 d-md-block">
                  <a class="btn btn-secondary" href="<?= BASE_URL ?>/admin-panel/pages/posts/view/edit.php?id=<?= $post['id'] ?>&source=index">Edit</a>

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
                          <a class="btn btn-danger" href="<?= BASE_URL ?>/admin-panel/pages/posts/controller/delete.php?id=<?= $post['id'] ?>">Delete</a>
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
    <div class="alert alert-warning" role="alert">
      no post found!
    </div>
  <?php endif ?>

  <h1>Recent Comments</h1>
  <?php if (!empty($comments)): ?>
    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">Name</th>
            <th scope="col">Comment Text</th>
            <th scope="col">Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($comments as $comment): ?>
            <tr>
              <td><?= $comment['author'] ?></td>
              <td><?= $comment['body'] ?></td>
              <td>
                <div class="d-grid gap-2 d-md-block">
                  <?php if ($comment['is_accepted'] == 0): ?>
                    <button type="button" class="btn btn-secondary disabled">wait for accept</button>
                  <?php else: ?>
                    <button type="button" class="btn btn-success disabled">accepted</button>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-warning" role="alert">
      no comment found!
    </div>
  <?php endif; ?>

  <h1>Categories</h1>
  <?php if (!empty($categories)): ?>

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
    <div class="alert alert-warning" role="alert">
      no category found!
    </div>
  <?php endif; ?>

</div>
</div>
</div>
</section>

<?php
include(BASE_PATH . "/admin-panel/pages/includes/footer.php");

?>