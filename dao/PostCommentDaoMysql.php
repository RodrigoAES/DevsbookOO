<?php
require_once 'models/PostComment.php';
require_once 'dao/UserDaoMysql.php';

class PostCommentDaoMySql implements PostCommentDAO {
    private $pdo;

    public function __construct($driver) {
        $this->pdo = $driver;
    }

    public function getComments($id_post) {
        $comments = [];

        $sql = $this->pdo->prepare("SELECT * FROM postcomments WHERE id_post = :id_post");
        $sql->bindValue(':id_post', $id_post);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);

            $userDAO = new UserDaoMysql($this->pdo);

            foreach($data as $item) {
                $comment = new PostComment;
                $comment->id = $item['id'];
                $comment->id_post = $item['id_post'];
                $comment->id_user = $item['id_user'];
                $comment->created_at = $item['created_at'];
                $comment->body = $item['body'];
                $comment->user = $userDAO->findById($item['id_user']);

                $comments[] = $comment;
            }
        }

        return $comments;
    }

    public function addComment(PostComment $pc) {
        $sql = $this->pdo->prepare('INSERT INTO postcomments 
                                                (id_post, id_user, body, created_at)
                                        VALUES (:id_post, :id_user, :body, :created_at)');

        $sql->bindValue(':id_post', $pc->id_post);
        $sql->bindValue(':id_user', $pc->id_user);
        $sql->bindValue(':body', $pc->body);
        $sql->bindValue(':created_at', $pc->created_at);
        $sql->execute();
    } 
    
    public function deleteFromPost($id_post) {
        $sql = $this->pdo->prepare('DELETE FROM postcomments WHERE id_post = :id_post');
        $sql->bindValue(':id_post', $id_post);
        $sql->execute();
    }

}