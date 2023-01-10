function toggleDesc(id){
    let block=document.getElementById('description-name_'+id);
    if (block.style.display=='none' || block.style.display=='' ){
        block.style.display='block';
    }else{
        block.style.display='none';
    }
}