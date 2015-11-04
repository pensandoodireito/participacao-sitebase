<?php
$path = $_GET{'src'};

$dirs = explode('/', $path);
$img = implode('/', $dirs);

if(strpos($img, ".jpg") !== false || strpos($img, ".jpeg")){
    $contentType = "image/jpeg";
}elseif(strpos($img, ".png") !== false){
    $contentType = "image/png";
}else{
    $contentType = "image/gif";
}

header("Content-Type: {$contentType}", true);
ob_start();
if(file_exists($img)){
    readfile($img);
    die;
}else{
    array_pop($dirs);
    $localDir = implode('/', $dirs);
    if(!file_exists($localDir) || !is_dir($localDir)){
        mkdir($localDir, 777, true);
    }
    $content = file_get_contents("http://pensando.mj.gov.br/{$img}");
    file_put_contents($img, $content);
    ob_clean();
    echo $content;
}
