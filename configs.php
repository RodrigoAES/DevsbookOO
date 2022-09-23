<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/UserDaoMysql.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();
$activeMenu = 'config';

$userDAO = new UserDaoMysql($pdo);


require 'partials/header.php';
require 'partials/menu.php' ;
?>
<section class="feed mt-10">
    <h1>Configurações</h1>

    <form class="config-form" method="POST" enctype="multipart/form-data" action="<?=$base;?>/configs_action.php" >
        <label>
            Novo avatar:</br></br>
            <input type="file" name="avatar" /></br>
            <!-<img src="<?=$base;?>/media/avatars/<?=$userInfo->avatar;?>" />
        </label></br></br>

        <label>
            Nova capa:</br></br>
            <input type="file" name="cover" width="300px" /></br>
            <!-<img src="<?=$base;?>/media/covers/<?=$userInfo->cover;?>" />
        </label></br></br>

        <hr/>

        <?php if(!empty($_SESSION['flash'])):?>
            <div class="flash"><?=$_SESSION['flash'];?></div>
            <?php $_SESSION['flash'] = '';?>
        <?php endif;?></br>

        <label>
            Nome completo:</br>
            <input type="text" name="name" value="<?=$userInfo->name;?>" />
        </label></br></br>

        <label>
            Email:</br>
            <input type="email" name="email" value="<?=$userInfo->email;?>">
        </label></br></br>

        <label>
            Data de nascimento:</br>
            <input type="text" name="birthdate" id="birthdate" value="<?=date('d/m/Y', strtotime($userInfo->birthdate));?>">
        </label></br></br>

        <label>
            Cidade:</br>
            <input type="text" name="city" value="<?=$userInfo->city;?>" />
        </label></br></br>

        <label>
            Trabalho:</br>
            <input type="text" name="work" value="<?=$userInfo->work;?>" />
        </label></br></br>

        <hr/>

        <label>
            Nova senha:</br>
            <input type="password" name="password">
        </label></br></br>

        <label>
           Confirmar nova senha:</br>
            <input type="password" name="password_confirm">
        </label></br></br>  

        <button class="button">Salvar</button>
    </form>

<script src="https://unpkg.com/imask"></script>
<script>
IMask(document.getElementById('birthdate'), {mask:'00/00/0000'});
</script>
</section>
<?php require 'partials/footer.php' ?>