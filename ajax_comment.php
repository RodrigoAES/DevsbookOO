<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostCommentDaoMysql.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();

$id_post = filter_input(INPUT_POST, 'id'); 
$body = filter_input(INPUT_POST, 'body');

$comment = ['error' => ''];
if($id_post && $body) {
    $postCommentDAO = new PostCommentDaoMysql($pdo);
    $newComment = new PostComment();

    $newComment->id_post = $id_post;
    $newComment->id_user = $userInfo->id;
    $newComment->created_at = date('Y-m-d H:i:s');
    $newComment->body = $body;

    $comment = [
        'error' => '',
        'link' => "$base/perfil.php?id=$userInfo->id",
        'avatar' => "$base/media/avatars/$userInfo->avatar",
        'name' => $userInfo->name,
        'body' => $body
    ];

    $postCommentDAO->addComment($newComment);

} else {
    $comment['error'] = 'ID do post e/ou comentário não enviados';
}

header('content: application/json');
echo json_encode($comment);
exit;
