<?php 
require_once 'models/Post.php';
require_once 'dao/UserRelationDaoMysql.php';
require_once 'dao/UserDaoMysql.php';
require_once 'dao/PostLikeDaoMysql.php';

class PostDaoMysql implements PostDAO {
    private $pdo;

    public function __construct(PDO $driver) {
        $this->pdo = $driver;
    }

    public function insert(Post $post) {
        $sql = $this->pdo->prepare('INSERT INTO posts 
            (id_user, type, created_at, body) 
        VALUES
            (:id_user, :type, :created_at, :body)');

        $sql->bindValue(':id_user', $post->id_user);
        $sql->bindValue(':type', $post->type);
        $sql->bindValue(':created_at', $post->created_at);
        $sql->bindValue(':body', $post->body);
        $sql->execute();
    }

    public function getUserFeed($id_user) {
        $postList = [];
        $sql = $this->pdo->prepare("SELECT * FROM posts WHERE id_user = :id_user ORDER BY created_at DESC");
        $sql->bindValue(':id_user', $id_user);
        $sql->execute();
        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            $postList = $this->_postListToObject($data, $id_user);
        }

            return $postList;
    }
    
    public function getHomeFeed($id_user) {
        $postList = [];
        // 1. lista dos usuarios que eu sigo 
        $urDAO = new UserRelationDaoMysql($this->pdo);
        $userList = $urDAO->getFollowing($id_user);
        $userList[] = $id_user;

        //2 pegar os posts ordenados pela data
        $list = implode(',', $userList);
        $sql = $this->pdo->query("SELECT * FROM posts 
                WHERE id_user IN ($list)
                ORDER BY created_at DESC");
        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            // 3 trasformar o resultado em objeto
            $postList = $this->_postListToObject($data, $id_user);
        }

            return $postList;
    }

    public function getPhotosFrom($id_user) {
        $photos = [];

        $sql = $this->pdo->prepare("SELECT * FROM posts WHERE id = :id_user AND type = 'photo' ORDER BY created_at DESC");
        $sql->bindValue('id_user', $id_user);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            $photos = $this->_postListToObject($data, $id_user);
        }
        return $photos;
    }

    private function _postListToObject($post_list, $id_user) {
        $posts = [];
        $userDAO = new UserDaoMysql($this->pdo);
        $postLikeDAO = new PostLikeDaoMysql($this->pdo);

        foreach($post_list as $post_item) {
            $post = new Post();
            $post->id = $post_item['id'];
            $post->type = $post_item['type'];
            $post->created_at = $post_item['created_at'];
            $post->body = $post_item['body'];
            $post->mine = false;

            if($post_item['id_user'] == $id_user) {
                $post->mine = true;
            }

            $post->user = $userDAO->findById($post_item['id_user']);

            // Like info
            $post->like_count = $postLikeDAO->getLikeCount($post->id);
            $post->liked = $postLikeDAO->isLiked($post->id, $id_user);

            // comment info
            $post->comments = [];
            
            $posts[] = $post;
        }

        return $posts;
    }
}