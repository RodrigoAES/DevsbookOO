<script type="text/javascript">
window.onload = function() {
    function closeFeedWindow () {
        document.querySelectorAll('.feed-item-more-window').forEach(item=>{
            item.style.display = 'none';
        });

        document.removeEventListener('click', closeFeedWindow);
    }

    document.querySelectorAll('.feed-item-head-btn').forEach(item=>{
        item.addEventListener('click', ()=>{
            closeFeedWindow();
            item.querySelector('.feed-item-more-window').style.display = 'block';
            setTimeout(() => {
                document.addEventListener('click', closeFeedWindow);
            }, 1);
        });
    });

    document.querySelector('.feed-item-more-window-delete').addEventListener('click', ()=>{
        document.querySelector('.feed-item-more-window .confirm-delete ').showModal();
        document.querySelector('.confirm-delete .cancel').addEventListener('click',()=>{
            document.querySelector('.confirm-delete').close();
            setTimeout(() => {closeFeedWindow()}, 1);
        });
        setTimeout(() => {
                document.addEventListener('click', closeFeedWindow);
            }, 1);
    });

    document.querySelectorAll('.like-btn').forEach(item=>{
        item.addEventListener('click', ()=>{
            let id = item.closest('.feed-item').getAttribute('data-id');
            let count = parseInt(item.innerText);
            console.log(count);
            if(item.classList.contains('on') === false) {
                item.classList.add('on');
                count++
                item.innerText = count;
            } else {
                item.classList.remove('on');
                count--
                item.innerText = count;
            }

            fetch(`ajax_like.php?id=${id}`);
        });
    });

    document.querySelectorAll('.fic-item-field').forEach(item=>{
        item.addEventListener('keyup', async (e)=>{
            if(e.keyCode == 13) {
                let id = item.closest('.feed-item').getAttribute('data-id');
                let txt = item.value;
                item.value = '';

                let data = new FormData();
                data.append('id', id);
                data.append('body', txt);

                let req = await fetch('ajax_comment.php', {
                method: "POST",
                body: data
                });
                let json = await req.json();

                if(json.error == '') {
                    let html = '<div class="fic-item row m-height-10 m-width-20">';
                    html += '<div class="fic-item-photo">';
                    html += '<a href="'+json.link+'"><img src="'+json.avatar+'" /></a>';
                    html += '</div>';
                    html += '<div class="fic-item-info">';
                    html += '<a href="'+json.link+'">'+json.name+'</a>';
                    html += json.body;
                    html += '</div>';
                    html += '</div>';

                    item.closest('.feed-item')
                        .querySelector('.feed-item-comments-area')
                        .innerHTML += html;
                }
            }
        });
    });
}
</script>