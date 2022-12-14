<?php
class Post {
    public $id;
    public $id_user;
    public $type;
    public $created_at;
    public $body;
}

interface PostDAO {
    public function insert (Post $post);
    public function delete($id_post, $id_user);
    public function getUserFeed($id_user);
    public function getHomeFeed($id_user);
    public function getPhotosFrom($id_user);
}