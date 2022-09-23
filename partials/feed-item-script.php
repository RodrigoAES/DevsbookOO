<script type="text/javascript">
window.onload = function() {
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
};
</script>