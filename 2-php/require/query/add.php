<?
if ($_POST['action']=='addData' && $_POST['name']){
        $name=check_input($_POST['name']);
        if ($_POST['description'] && $_POST['name'] && !$_POST['parent']){
            $desc=check_input($_POST['description']);
            $sql = "INSERT INTO `data`(`name`, `description`) VALUES (:names,:descr)";
            $sth=$dbh->prepare($sql);
            $sth->execute(array('names' => $name,'descr'=> $desc));
        }
        if ($_POST['parent'] && !$_POST['description'] && $_POST['name']){
            $parent=check_input($_POST['parent']);
            $sql = "INSERT INTO `data`(`parent`,`name`) VALUES (:parent,:names)";
            $sth=$dbh->prepare($sql);
            $sth->execute(array('parent'=> $parent, 'names' => $name));
        }
        if ($_POST['parent'] && $_POST['description'] && $_POST['name']){
            $parent=check_input($_POST['parent']);
            $desc=check_input($_POST['description']);
            $sql = "INSERT INTO `data`(`parent`, `name`, `description`) VALUES (:parent, :names, :descr)";
            $sth=$dbh->prepare($sql);
            $sth->execute(array('names' => $name, 'parent'=> $parent, 'descr'=> $desc));
        }
        if (!$_POST['parent'] && !$_POST['description']){
            $sql = "INSERT INTO `data`(`name`) VALUES (:names)";
            $sth=$dbh->prepare($sql);
            $sth->execute(array('names' => $name));
        }
                $row = $sth->fetchAll();
                header('location: admin');
    }
    ?>