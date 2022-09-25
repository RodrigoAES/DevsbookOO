<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/UserRelationDaoMysql.php';
require_once 'dao/UserDaoMysql.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();

$id_user = filter_input(INPUT_GET, 'id');

if($id_user) {
    $userRelationDAO = new UserRelationDaoMysql($pdo);
    $userDAO = new UserDaoMysql($pdo);

    if($userDAO->findById($id_user)) {
        $relation = new UserRelation();
        $relation->user_from = $userInfo->id;
        $relation->user_to = $id_user;

        if($userRelationDAO->isFollowing($userInfo->id, $id_user)) {
            $userRelationDAO->delete($relation);
        } else {
            $userRelationDAO->insert($relation);
        }
        
        header("location:$base/perfil.php?id=$id_user");
        exit;
    }
}

header("location:$base");
exit;