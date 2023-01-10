function showNotific(id=null){
    if (id>=0){
        document.getElementById('input_parent_id').value=id;
    }
    let blockNotific=document.getElementsByClassName('notification')[0];
    blockNotific.style.display='flex';
    document.getElementById('form_addData').style.display='block';
}

function showEdit(id, pid=null, name, desc=null){
    let blockNotific=document.getElementsByClassName('notification')[0];
    document.getElementById('form_editData').style.display='block';
    blockNotific.style.display='flex';
    document.getElementById('edit_id').value=id;
    document.getElementById('edit_name').value=name;
    if (desc)
        document.getElementById('edit_desc').value=desc;
    if (pid)
       document.getElementById('sel_par').value=pid;
}
function hideNotific(e){
    let blockNotific=document.getElementsByClassName('notification')[0];
    blockNotific.style.display='none';
    document.getElementById('form_addData').style.display='none';
    document.getElementById('form_editData').style.display='none';
}
function hideEdit(id, pid=null, name, desc=null){
    let blockNotific=document.getElementsByClassName('notification')[0];
    blockNotific.style.display='none';
    document.getElementById('form_editData').style.display='none';
}
function showConfirm(id,name){
    var answer=confirm("Вы действительно хотите удалить ветку \""+name+"\"?");
    if (answer)
        window.location.href='admin?action=deleteBranch&id='+id;
}