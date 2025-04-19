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
        echo "session not empty";
        foreach ($_SESSION['flash'] as $key => $msg) {
            echo $msg;
        }
        unset($_SESSION['flash']);
    }
    echo "end of flash function";
}
