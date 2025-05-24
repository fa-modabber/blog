<?php

function queryExecuteWithParams(PDO $db, $query, $params)
{
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    return $stmt;
}

function queryFetchAll(PDO $db, string $query, array $params = [], int $fetchMode = PDO::FETCH_ASSOC)
{
    $stmt = empty($params)
        ? $db->query($query)
        : queryExecuteWithParams($db, $query, $params);

    return $stmt->fetchAll($fetchMode);
}

function queryFetchOne(PDO $db, string $query, array $params = [], int $fetchMode = PDO::FETCH_ASSOC)
{
    $stmt = empty($params)
        ? $db->query($query)
        : queryExecuteWithParams($db, $query, $params);

    return $stmt->fetch($fetchMode); // might return false if not found
}

function executeStatement(PDO $db, string $query, array $params = [])
{
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    return $stmt->rowCount() > 0;
}

//posts

function fetchPosts(PDO $db): array|string
{
    $query = "SELECT * FROM posts ORDER BY id DESC";
    return queryFetchAll($db, $query);
}

function fetchPostsByUserId(PDO $db, $userId)
{
    $query = "SELECT * FROM posts WHERE user_id=:id ORDER BY id DESC";
    $params = ["id" => $userId];
    return queryFetchAll($db, $query, $params);
}

function fetchPostsWithDetails(PDO $db): array|string
{
    $query = "
    SELECT posts.*, categories.title AS category_title, users.first_name, users.last_name
    FROM posts
    JOIN categories ON posts.category_id = categories.id
    JOIN users ON posts.user_id = users.id
    ORDER BY posts.id DESC
";
    return queryFetchAll($db, $query);
}

function fetchPostsByCategory(PDO $db, $categoryId): array|string
{
    $query = "SELECT * FROM posts where category_id= :id ORDER BY posts.id DESC";
    $params = ['id' => $categoryId];
    return queryFetchAll($db, $query, $params);
}

function fetchPostsWithDetailsByCategory(PDO $db, int $categoryId): array|string
{
    $query = "
            SELECT posts.*, categories.title AS category_title, users.first_name, users.last_name
            FROM posts
            JOIN categories ON posts.category_id = categories.id
            JOIN users ON posts.user_id = users.id
            WHERE categories.id = :id
            ORDER BY posts.id DESC
        ";
    $params = ['id' => $categoryId];
    return queryFetchAll($db, $query, $params);
}

function fetchPostById(PDO $db, $postId): array|null|string
{
    $query = "SELECT * FROM posts WHERE id=:id";
    $params = ['id' => $postId];
    return queryFetchOne($db, $query, $params);
}

function fetchPostWithDetailsById(PDO $db, $postId): array|null|string
{
    $query = "
    SELECT posts.*, categories.title AS category_title, users.first_name, users.last_name
    FROM posts
    JOIN categories ON posts.category_id = categories.id
    JOIN users ON posts.user_id = users.id
    WHERE posts.id = :id
";
    $params = ['id' => $postId];
    return queryFetchOne($db, $query, $params);
}

function fetchPostByIdWithCategory(PDO $db, $postId)
{
    $query = "
    SELECT posts.*, categories.title AS category_title
    FROM posts
    JOIN categories ON posts.category_id = categories.id
    WHERE posts.id = :id
";
    $params = ['id' => $postId];
    return queryFetchOne($db, $query, $params);
}

function searchInPosts(PDO $db, string $keyword)
{
    $query = "SELECT * FROM posts WHERE body LIKE :keyword OR title LIKE :keyword ORDER BY id DESC";
    $params = ['keyword' => "%$keyword%"];
    return queryFetchAll($db, $query, $params);
}

function deletePostById(PDO $db, $postId)
{
    $query = "DELETE FROM posts WHERE id = :id";
    $params = ['id' => $postId];
    return executeStatement($db, $query, $params);
}

function updatePostById(PDO $db, $postId, $title, $categoryId, $image, $body)
{
    $query = "UPDATE posts SET title=:title, category_id=:category_id, image=:image, body=:body WHERE id=:id";
    $params = [
        'id' => $postId,
        'title' => $title,
        'category_id' => $categoryId,
        'image' => $image,
        'body' => $body,
    ];
    return executeStatement($db, $query, $params);
}

function storePost(PDO $db, $title, $categoryId, $image, $body, $userId)
{
    $query = "INSERT INTO posts (title, category_id, image, body, user_id) VALUES (:title, :category_id, :image, :body, :user_id)";
    $params = [
        'title' => $title,
        'category_id' => $categoryId,
        'image' => $image,
        'body' => $body,
        'user_id' => $userId
    ];
    $stmt = $db->prepare($query);
    if ($stmt->execute($params)) {
        return $db->lastInsertId();
    }

    return false;
}
//***************** categories
function fetchCategories(PDO $db): array|string
{
    $query = "SELECT * FROM categories";
    return queryFetchAll($db, $query);
}

function fetchCategoryById(PDO $db, $categoryId)
{
    $query = "SELECT * FROM categories WHERE id=:id";
    $params = ['id' => $categoryId];
    return queryFetchOne($db, $query, $params);
}


//***************** user
function fetchUserById(PDO $db, $userId)
{
    $query = "SELECT * FROM users WHERE id=:id";
    $params = ['id' => $userId];
    return queryFetchOne($db, $query, $params);
}

function fetchUser(PDO $db, $email, $password)
{
    $query = "SELECT * FROM users WHERE email=:email AND password=:password";
    $params = ['email' => $email, 'password' => $password];
    return queryFetchOne($db, $query, $params);
}

function  storeUser(PDO $db, $email, $password)
{
    $query = "INSERT INTO users (email, password) VALUES (:email,:password)";
    $params = ['email' => $email, 'password' => $password];
    return executeStatement($db, $query, $params);
}
//***************** sliders
function fetchSlidersWithDetails(PDO $db)
{
    $query = "
    SELECT post_sliders.*, posts.title AS post_title, posts.body AS post_body, posts.image AS post_image
    FROM post_sliders
    JOIN posts ON post_sliders.post_id = posts.id
";
    return queryFetchAll($db, $query);
}


//***************** comments
function fetchCommentsByUserId(PDO $db, $userId)
{
    $query = "
    SELECT comments.*
    FROM comments
    JOIN posts ON comments.post_id = posts.id
    WHERE posts.user_id = :user_id
    ORDER BY comments.id DESC
";
    $params = ['user_id' => $userId];
    return queryFetchAll($db, $query, $params);
}

function fetchCommentsAcceptedByPostId(PDO $db, $postId)
{
    $query = "SELECT * FROM comments WHERE post_id=:id AND is_accepted='1'";
    $params = ['id' => $postId];
    return queryFetchAll($db, $query, $params);
}

function storeComment(PDO $db, $name, $body, $postId)
{
    $query = "INSERT INTO comments (name, body, post_id, is_accepted) VALUES (:name,:body,:post_id, 0)";
    $params = ['name' => $name, 'body' => $body, 'post_id' => $postId];
    return executeStatement($db, $query, $params);
}

function deletecommentById(PDO $db, $commentId)
{
    $query = "DELETE FROM comments WHERE id = :id";
    $params = ['id' => $commentId];
    return executeStatement($db, $query, $params);
}

function acceptyCommentById(PDO $db, $commentId)
{
    $query = "UPDATE comments SET is_accepted = 1 WHERE id = :id";
    $params = ['id' => $commentId];
    return executeStatement($db, $query, $params);
}

//***************** subscribers
function storeNewsletterSubscriber(PDO $db, $name, $email): bool|string
{
    $query = "INSERT INTO subscribers (name, email) VALUES (:name,:email)";
    $params = ['name' => $name, 'email' => $email];
    return executeStatement($db, $query, $params);
}
