<div class="box feed-new">
    <div class="box-body">
        <div class="feed-new-editor m-10 row">
            <div class="feed-new-avatar">
                <img src="<?=$base;?>/media/avatars/<?=$userInfo->avatar;?>" />
            </div>
            <div class="feed-new-input-placeholder">O que você está pensando, <?=$firstName;;?>?</div>
            <div class="feed-new-input" contenteditable="true"></div>
            <div class="feed-new-send">
                <img src="<?=$base;?>/assets/images/send.png" />
            </div>
            <form class="feed-new-form" method="POST" action="<?=$base;?>/feed_editor_action.php">
                <input type="hidden" name="body" />
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    let sendButton = document.querySelector('.feed-new-send');
    let feedInput = document.querySelector('.feed-new-input');
    let form = document.querySelector('.feed-new-form');
    let body = form.querySelector('input[name="body"]');
    
    sendButton.addEventListener('click', ()=>{
        body.value = feedInput.innerText;
        form.submit();
    })
</script>