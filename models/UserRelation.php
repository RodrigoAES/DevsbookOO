<?php
class UserRelation {
    public $id;
    public $User_from;
    public $user_to;
}

interface UserRelationDAO {
    public function insert (UserRelation $u);
    public function getFollowing($id);
    public function getFollowers($id);
}