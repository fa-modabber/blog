<?php

function abort404()
{
    http_response_code(404);
    include(BASE_PATH . '/404.php');
    exit;
}

function test_form_input($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

function flash($entity, $action, $result, $customMessage = null)
{
    $messages = [
        'user' => [
            'fetch' => [
                'success' => '',
                'error' => 'error in fetch user',
                'not_found' => 'user not found'
            ],
            'create' => [
                'success' => 'you succeefully signedup',
                'error'   => 'problem in signup',
            ]
        ],
        'post' => [
            'fetch' => [
                'success' => '',
                'error' => 'error in fetch posts',
                'not_found' => 'post not found'
            ],
            'create' => [
                'success' => 'you succeefully created the post',
                'error'   => 'problem in creating the post',
            ],
            'delete' => [
                'success' => 'you succeefully deleted the post',
                'error'   => 'problem in deleting the post',
                'not_found' => 'post not found',
            ],
            'update' => [
                'success' => 'you succeefully updated the post',
                'error' => 'problem in updating the post',
                'not_found' => 'post not found'
            ]
        ],
        'category' => [
            'fetch' => [
                'success' => '',
                'error' => 'problem in fetch categories',
                'not_found' => 'category not found'
            ],
        ],
        'comment' => [
            'create' => [
                'success' => 'You successfully left a comment!',
                'error'   => 'problem in creating the comment',
            ],
            'delete' => [
                'success' => 'you succeefully deleted the comment',
                'error'   => 'problem in deleting the comment',
            ],
            'accept' => [
                'success' => 'you succeefully accepted the comment',
                'error'   => 'problem in accepting the comment',
            ]
        ],
        'newsletter' => [
            'create' => [
                'success' => 'you successfully subscribed to newsletter',
                'error' => 'problem in subscribing to newsletter'
            ]
        ]
    ];

    $type_map = [
        'success' => 'success',
        'error' => 'danger',
        'not_found' => 'warning',
    ];

    if (isset($messages[$entity][$action][$result])) {
        $msg = $messages[$entity][$action][$result];
        $type = $type_map[$result] ?? 'info';
        $_SESSION['flash_messages'][$type][] = $msg;
    }
}

function display_flash_messages()
{
    if (!empty($_SESSION['flash_messages'])) {
        foreach ($_SESSION['flash_messages'] as $type => $messages) {
            foreach ($messages as $msg) {
                echo "<div class='alert alert-$type'>$msg</div>";
            }
        }
        unset($_SESSION['flash_messages']);
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
