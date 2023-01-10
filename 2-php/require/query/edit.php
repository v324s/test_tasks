<? 
if ($_POST['action']=='edit' && $_POST['id'] && $_POST['name'] && $_POST['pid']){
        $id=check_input($_POST['id']);
        $name=check_input($_POST['name']);
        $pid=check_input($_POST['pid']);
        if ($pid=='-1')
            $pid=null;
        $desc=check_input($_POST['description']);
        if (mb_strlen($desk)==0)
            $desk=null;
        $sql = "UPDATE `data` SET `name`=:names, `parent`=:pid, `description`=:descr WHERE `id`=:id";
        $sth=$dbh->prepare($sql);
        $sth->execute(array('names' => $name, 'pid' => $pid, 'descr'=> $desc, 'id' => $id));
        header('location: admin');
    }
    ?>