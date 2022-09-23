<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostLikeDaoMysql.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();

$id_post = filter_input(INPUT_GET, 'id'); 

if(!empty($id_post)) {
    $postLikeDAO = new PostLikeDaoMysql($pdo);
    $postLikeDAO->likeToggle($id_post, $userInfo->id);
}
