<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostDaoMysql.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();
$activeMenu = 'photos';

$id = filter_input(INPUT_GET, 'id');
if(!$id) {
    $id = $userInfo->id;
}
$postDAO = new PostDaoMysql($pdo);
$userDAO = new UserDaoMysql($pdo);


//1 pegar informações do usuario
$user = $userDAO->findById($id, true);
if(!$user){
    header("location:$base");
    exit;
}
$dateFrom = new DateTime($user->birthdate);
$dateTo = new DateTime('today');
$user->ageYears = $dateFrom->diff($dateTo)->y; 

//2 pegaer o FEED do usuario
$feed = $postDAO->getUserFeed($id);
//3 verificar se eu sigo este usuario




/*$postDAO = new PostDaoMysql($pdo);
$feed = $postDAO->getHomefeed($userInfo->id);*/


require 'partials/header.php';
require 'partials/menu.php' ;
?>
<section class="feed">

    <div class="row">
        <div class="box flex-1 border-top-flat">
            <div class="box-body">
                <div class="profile-cover" style="background-image: url('<?=$base;?>/media/covers/<?=$user->cover;?>');"></div>
                <div class="profile-info m-20 row">
                    <div class="profile-info-avatar">
                        <img src="<?=$base;?>/media/avatars/<?=$user->avatar;?>" />
                    </div>
                    <div class="profile-info-name">
                        <div class="profile-info-name-text"><?=$user->name;?></div>
                        <?php if(!empty($user->city)): ?>
                            <div class="profile-info-location"><?=$user->city;?></div>
                        <?php endif;?>
                    </div>
                    <div class="profile-info-data row">
                        <div class="profile-info-item m-width-20">
                            <div class="profile-info-item-n"><?=count($user->followers);?></div>
                            <div class="profile-info-item-s">Seguidores</div>
                        </div>
                        <div class="profile-info-item m-width-20">
                            <div class="profile-info-item-n"><?=count($user->following);?></div>
                            <div class="profile-info-item-s">Seguindo</div>
                        </div>
                        <div class="profile-info-item m-width-20">
                            <div class="profile-info-item-n"><?=count($user->photos);?></div>
                            <div class="profile-info-item-s">Fotos</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="column">
                    
            <div class="box">
                <div class="box-body">

                    <div class="full-user-photos">

                        <?php foreach($user->photos as $key => $photo): ?>  
                            <div class="user-photo-item">
                                <a href="#modal-<?=$key;?>" rel="modal:open">
                                    <img src="<?=$base;?>/media/uploads/<?=$photo->body;?>" />
                                </a>
                                <div id="modal-<?=$key;?>" style="display:none">
                                    <img src="<?=$base;?>/media/uploads/<?=$photo->body;?>" />
                                </div>
                            </div>
                        <?php endforeach;?>

                        <?php if(count($user->photos) === 0):?>
                            <div class="warning">Este usuáriio ainda não possui fotos.</div>
                        <?php endif;?>
                    </div>
                    

                </div>
            </div>

        </div>
        
    </div>

</section>
<?php require 'partials/footer.php' ?>