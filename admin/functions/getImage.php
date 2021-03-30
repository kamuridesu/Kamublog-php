<?php
    $filename = basename($_GET['file']);
    $path = "../uploads/";
    $filepath = $path . addslashes($filename);
    $imginfo = getimagesize($filepath);
    header("Content-type: {$imginfo['mime']}");
    readfile($filepath);