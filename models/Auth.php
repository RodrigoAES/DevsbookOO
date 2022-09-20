<?php
require_once 'dao/UserDaoMysql.php';

class Auth {
    private $pdo;
    private $base;
    private $userDAO;

    public function __construct(PDO $pdo, $base) {
        $this->pdo = $pdo;
        $this->base = $base;
        $this->userDAO = new UserDaoMysql($this->pdo);;
    }

    public function checkToken() {
        if(!empty($_SESSION['token'])) {
            $token = $_SESSION['token'];

            $user = $this->userDAO->findByToken($token);

            if($user){
                return $user;
            }
        }

        header('location:'.$this->base.'/login.php');
        exit;
    }

    public function validateLogin($email, $password) {
        $user = $this->userDAO->findByEmail($email);

        if($user) {

            if(password_verify($password, $user->password)) {
                $token = md5(time().rand(0, 9999));

                $_SESSION['token'] = $token;
                $user->token = $token;
                $this->userDAO->update($user);

                return true;
            }
        }

        return false;
    }

    public function emailExists($email) {
        return ($this->userDAO->findByEmail($email)) ? true : false;
    }

    public function registerUser($name, $email, $password, $birthdate){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $token = md5(time().rand(0, 9999));

        $newUser = new User();
        $newUser->name = $name;
        $newUser->email = $email;
        $newUser->password = $hash;
        $newUser->birthdate = $birthdate;
        $newUser->token = $token;

        $this->userDAO->insert($newUser);

        $_SESSION['token'] = $token;
    }
}