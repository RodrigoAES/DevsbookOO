<?php
class UserRelation {
    public $id;
    public $User_from;
    public $user_to;
}

interface UserRelationDAO {
    public function insert (UserRelation $ur);
    public function delete (UserRelation $ur);
    public function getFollowing($id);
    public function getFollowers($id);
    public function isFollowing($me, $id_user);
}