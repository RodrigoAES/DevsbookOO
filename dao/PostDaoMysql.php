<?php 
require_once 'models/Post.php';
require_once 'dao/UserRelationDaoMysql.php';
require_once 'dao/UserDaoMysql.php';
require_once 'dao/PostLikeDaoMysql.php';
require_once 'dao/PostCommentDaoMysql.php';

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

    public function delete($id_post, $id_user) {
        $postLikeDAO = new PostLikeDaoMysql($this->pdo);
        $postCommentDAO = new PostCommentDaoMysql($this->pdo);

        $sql = $this->pdo->prepare('SELECT * FROM posts WHERE id = :id AND id_user = :id_user');
        $sql->bindValue(':id', $id_post);
        $sql->bindValue(':id_user', $id_user);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $post = $sql->fetch(PDO::FETCH_ASSOC);
            
            $postLikeDAO->deleteFromPost($id_post);
            $postCommentDAO->deleteFromPost($id_post);

            if($post['type'] === 'photo') {
                $img = "media/uploads/".$post['body'];
                
                if(file_exists($img)) {
                    unlink($img);
                }
            }

            $sql = $this->pdo->prepare('DELETE FROM posts WHERE id = :id AND id_user = :id_user');
            $sql->bindValue(':id', $id_post);
            $sql->bindValue(':id_user', $id_user);
            $sql->execute();

        }
    }

    public function getUserFeed($id_user, $page = 1, $myId = false) { 
        $postList = ['feed' => [], 'pages' => '', 'currentPage' => ''];
        $perPage = 2;
        $offset = ($page - 1) * $perPage;

        $sql = $this->pdo->prepare("SELECT * FROM posts 
            WHERE id_user = :id_user 
            ORDER BY created_at DESC LIMIT $offset,$perPage");
        $sql->bindValue(':id_user', $id_user);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            $postList['feed'] = $this->_postListToObject($data, $id_user, $myId);
        }

        $sql = $this->pdo->prepare("SELECT COUNT(*) as c FROM posts 
            WHERE id_user = :id_user");
        $sql->bindValue(':id_user', $id_user);
        $sql->execute();
        $totalData = $sql->fetch();
        $total = $totalData['c']; 

        $postList['pages'] = ceil($total / $perPage);
        $postList['currentPage'] = $page;

        return $postList;
    }
    
    public function getHomeFeed($id_user, $page = 1) {
        $postList = ['feed' => '', 'pages' => '', 'currentPage' => ''];
        $perPage = 5;
        $offset = ($page - 1) * $perPage;

        // 1. lista dos usuarios que eu sigo 
        $urDAO = new UserRelationDaoMysql($this->pdo);
        $userList = $urDAO->getFollowing($id_user);
        $userList[] = $id_user;

        //2 pegar os posts ordenados pela data
        $list = implode(',', $userList);
        $sql = $this->pdo->query("SELECT * FROM posts 
                WHERE id_user IN ($list)
                ORDER BY created_at DESC LIMIT $offset,$perPage");

        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            // 3 trasformar o resultado em objeto
            $postList['feed'] = $this->_postListToObject($data, $id_user);
        }

        // Pegar o total de posts
        $sql = $this->pdo->query("SELECT COUNT(*) as c FROM posts 
        WHERE id_user IN ($list)");
        $totalData = $sql->fetch();
        $total = $totalData['c'];

        $postList['pages'] = ceil($total / $perPage);
        $postList['currentPage'] = $page;

        return $postList;
    }

    public function getPhotosFrom($id_user) {
        $photos = [];

        $sql = $this->pdo->prepare("SELECT * FROM posts WHERE id_user = :id_user AND type = 'photo' ORDER BY created_at DESC");
        $sql->bindValue('id_user', $id_user);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            $photos = $this->_postListToObject($data, $id_user);
        }
        return $photos;
    }

    private function _postListToObject($post_list, $id_user, $myId = false) { 
        $posts = [];
        $userDAO = new UserDaoMysql($this->pdo);
        $postLikeDAO = new PostLikeDaoMysql($this->pdo);
        $postCommentDAO = new PostCommentDaoMysql($this->pdo);

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

            if($myId) { 
                $id_user = $myId;
            }
            $post->liked = $postLikeDAO->isLiked($post->id, $id_user);

            // comment info
            $post->comments = $postCommentDAO->getComments($post->id);
            
            $posts[] = $post;
        }

        return $posts;
    }
}