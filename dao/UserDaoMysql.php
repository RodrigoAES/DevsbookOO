<?php
require_once 'models/User.php';
require_once 'dao/UserRelationDaoMysql.php';
require_once 'dao/PostDaoMysql.php';

class UserDaoMysql implements UserDAO {
    private $pdo;

    public function __construct(PDO $driver) {
        $this->pdo = $driver;

    }

    private function generateUser($data, $full = false) {
        $user = new User;
        $user->id = $data['id'] ?? 0;
        $user->email = $data['email'] ?? '';
        $user->password = $data['password'] ?? '';
        $user->name = $data['name'] ?? '';
        $user->birthdate = $data['birthdate'] ?? '';
        $user->city = $data['city'] ?? '';
        $user->work = $data['work'] ?? '';
        $user->avatar = $data['avatar'] ?? '';
        $user->cover = $data['cover'] ?? '';
        $user->token = $data['token'] ?? '';

        if($full) {
            $urDAO = new UserRelationDaoMysql($this->pdo);
            $postDAO = new PostDaoMysql($this->pdo);

            $user->followers = $urDAO->getFollowers($user->id);
            foreach($user->followers as $key => $follower_id) {
                $newUser = $this->findById($follower_id);
                $user->follower[$key] = $newUser;
            }

            $user->following = $urDAO->getFollowing($user->id);
            foreach($user->following as $key => $followed_id) {
                $newUser = $this->findById($followed_id);
                $user->following[$key] = $newUser;
            }
            //photos
            $user->photos = $postDAO->getPhotosFrom($user->id);
        }

        return $user;
    }

    public function findByToken($token) {
        if(!empty($token)){
            $sql = $this->pdo->prepare("SELECT * FROM users WHERE token = :token");
            $sql->bindValue(':token', $token);
            $sql->execute();

            if($sql->rowCount() > 0) {
                $data = $sql->fetch(PDO::FETCH_ASSOC);
                $user = $this->generateUser($data);

                return $user;
            }
        }

        return false;
    }
    
    public function findByEmail($email) {
        if(!empty($email)){
            $sql = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
            $sql->bindValue(':email', $email);
            $sql->execute();

            if($sql->rowCount() > 0) {
                $data = $sql->fetch(PDO::FETCH_ASSOC);
                $user = $this->generateUser($data);

                return $user;
            }
        }
    }

    public function findById($id, $full = false) {
        if(!empty($id)){
            $sql = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
            $sql->bindValue(':id', $id);
            $sql->execute();

            if($sql->rowCount() > 0) {
                $data = $sql->fetch(PDO::FETCH_ASSOC);
                $user = $this->generateUser($data, $full);

                return $user;
            }

            return false;
        }
    }

    public function update(User $user) {
        $sql = $this->pdo->prepare('UPDATE users SET 
            email = :email,
            password = :password,
            name = :name,
            birthdate = :birthdate,
            city = :city,
            work = :work,
            avatar = :avatar,
            cover = :cover,
            token = :token
            WHERE id = :id');

        $sql->bindValue(':email', $user->email);
        $sql->bindValue(':password', $user->password);
        $sql->bindValue(':name', $user->name);
        $sql->bindValue(':birthdate', $user->birthdate);
        $sql->bindValue(':city', $user->city);
        $sql->bindValue(':work', $user->work);
        $sql->bindValue(':avatar', $user->avatar);
        $sql->bindValue(':cover', $user->cover);
        $sql->bindValue(':token', $user->token);
        $sql->bindValue(':id', $user->id);
        $sql->execute();

        return true;
    }

    public function insert(User $user) {
        $sql = $this->pdo->prepare('INSERT INTO users 
            (email, password, name, birthdate, token) 
        VALUES 
            (:email, :password, :name, :birthdate, :token)');

        $sql->bindValue(':name', $user->name);
        $sql->bindValue(':email', $user->email);
        $sql->bindValue(':password', $user->password);
        $sql->bindValue(':birthdate', $user->birthdate);
        $sql->bindValue(':token', $user->token);
        $sql->execute();

        return true;
    }
}