<?php
$request = $_SERVER['REQUEST_URI'];
$uri_names = explode("/", $request);
$view = $uri_names[2];
$actual_slug = $uri_names[3];
$root_path = $_SERVER['DOCUMENT_ROOT'];
switch($view){
    case "post":
        include $root_path . "/Kamublog/post.php";
    default:
        http_response_code(404);
        break;
}