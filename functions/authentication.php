<?php
require_once "connection.php";

function generate_tokens(): array
{
    $selector = bin2hex(random_bytes(16));
    $validator = bin2hex(random_bytes(32));

    return [$selector, $validator, $selector . ':' . $validator];
}

function parse_token(string $token): ?array
{
    $parts = explode(':', $token);

    if ($parts && count($parts) == 2) {
        return [$parts[0], $parts[1]];
    }
    return null;
}

function find_user_token_by_selector(string $selector)
{
    $query = "SELECT validator, user_id, expiry FROM auth_token WHERE selector = '" . $selector . "' AND expiry >= now() LIMIT 1";
    $db_handle = new DBController();
        
    if ($db_handle->runQuery($query)){
    return $db_handle->runQuery($query);
    }
    return false;
}

function delete_cookie_by_selector(string $selector)
{
    $query = "DELETE FROM auth_token WHERE selector = '" . $selector . "'";
    $db_handle = new DBController();
    if($db_handle->deleteQuery($query))
    {
        unset($db_handle);
        return true;
    }
}


function remember_me(int $user_id, int $years = 4)
{
    // delete old cookie if it exists
    if (isset($_COOKIE['remember_me'])) {
        $token = filter_input(INPUT_COOKIE, 'remember_me', FILTER_SANITIZE_STRING);
        $selector = parse_token($token)[0];
        delete_cookie_by_selector($selector);
    }
    
    [$selector, $validator, $token] = generate_tokens();

    $expired_seconds = time() + 60 * 60 * 24 * 365 * $years;
    
    // insert a token to the database
    $hash_validator = password_hash($validator, PASSWORD_DEFAULT);
    $expiry = date('Y-m-d H:i:s', $expired_seconds);
    $db_handle = new DBController();
    $query = "INSERT INTO auth_token (user_id, selector, validator, expiry) VALUES('" . $user_id . "','" . $selector . "','" . $hash_validator. "','" . $expiry. "')";
    if ($db_handle->insertQuery($query)) {
        setcookie('remember_me', $token, $expired_seconds, '/');
        unset($db_handle);
    }
}


function token_is_valid(string $token)
{
    $selector = parse_token($token)[0];
    $validator = parse_token($token)[1];
    if (password_verify($validator,find_user_token_by_selector($selector)[0]["validator"]))
    {
        return find_user_token_by_selector($selector)[0]["user_id"];
    }
    return false;
}

function is_logged_in(): bool
{
    // check the session
    if (isset($_SESSION['user_id'])) {
        if(isUserDisabled($_SESSION['user_id'])){
            return true;
        }

        if (isset($_COOKIE['remember_me'])) {
            $token = filter_input(INPUT_COOKIE, 'remember_me', FILTER_SANITIZE_STRING);
            $selector = parse_token($token)[0];
            delete_cookie_by_selector($selector);

            unset($_COOKIE['remember_me']);
            setcookie('remember_user', null, -1);
        }
        unset($_SESSION['user_id']);
        session_destroy();
        header("Location: \home");
        return false;
    }

    // check the remember_me in cookie
    if(filter_input(INPUT_COOKIE, 'remember_me', FILTER_SANITIZE_STRING)){
        $token = filter_input(INPUT_COOKIE, 'remember_me', FILTER_SANITIZE_STRING);
    }else{
        return false;
    }
    
    if (!$token){
        return false;
    }
    if (token_is_valid($token)) {
        session_start(); 
        $_SESSION["user_id"] = token_is_valid($token); 
        $_SESSION["email"] = getUserEmail($_SESSION["user_id"]);
        $_SESSION["type"] = getUserType($_SESSION["user_id"]);
        return true;
    }
    return false;
}


function is_admin(): bool
{
    if($_SESSION["type"]==2){
        return true;
    }
    return false;
}


function logout(): void
{
    if (is_logged_in()) {
        
         // remove the remember_me cookie
         if (isset($_COOKIE['remember_me'])) {
            $token = filter_input(INPUT_COOKIE, 'remember_me', FILTER_SANITIZE_STRING);
            $selector = parse_token($token)[0];
            delete_cookie_by_selector($selector);

            unset($_COOKIE['remember_me']);
            setcookie('remember_user', null, -1);
        }


        // delete session
        unset($_SESSION['user_id']);

       

        // remove all session data
        session_destroy();

        // redirect to the login page
        header("Location: \home");
    }
}


function getUserEmail(int $userId): string
{
    $db_handle = new DBController();
    $query = "SELECT email FROM user WHERE ID = '" . $userId . "'";
    $dbuser=$db_handle->runQuery($query);
    unset($db_handle);
    return $dbuser[0]["email"];
}

function getUserType(int $userId): int
{
    $db_handle = new DBController();
    $query = "SELECT type FROM user WHERE ID = '" . $userId . "'";
    $dbuser=$db_handle->runQuery($query);
    unset($db_handle);
    return $dbuser[0]["type"];
}


function isUserDisabled(int $userId): bool
{
    $db_handle = new DBController();
    $query = "SELECT status FROM user WHERE ID = '" . $userId . "'";
    $dbuser=$db_handle->runQuery($query);
    unset($db_handle);
    if($dbuser[0]["status"]==1){
        return true;
    }
    return false;
}