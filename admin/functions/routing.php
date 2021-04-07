<?php
$request = $_SERVER['REQUEST_URI'];

switch ($request){
    case '/':
        require __DIR__ . 'post.php';
        break;
    default:
    http_response_code(404);
    break;
}