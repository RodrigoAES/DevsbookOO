<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/UserDaoMysql.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();
$activeMenu = 'config';

$userDAO = new UserDaoMysql($pdo);;


require 'partials/header.php';
require 'partials/menu.php' ;
?>
<section class="feed mt-10">
    <h1>Configurações</h1>

    <form class="config-form" method="POST" enctype="multipart/form-data" action="<?=$base;?>/configs_action.php" >
        <label>
            Novo avatar:</br></br>
            <input type="file" name="avatar" />
        </label></br></br>

        <label>
            Nova capa:</br></br>
            <input type="file" name="cover" />
        </label></br></br>
    </form>

</section>
<?php require 'partials/footer.php' ?>