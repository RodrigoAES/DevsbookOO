<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostDaoMysql.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();

$id_post = filter_input(INPUT_GET, 'id');


if($id_post) {
    $postDAO = new PostDaoMysql($pdo);
    $postDAO->delete($id_post, $userInfo->id);
}

header("location:$base");
exit;