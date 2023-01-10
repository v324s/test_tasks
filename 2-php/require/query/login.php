<?

if ($_POST['login'] && $_POST['password']){
    $login=check_input($_POST['login']);
    $password=md5(check_input($_POST['password']));
    $error=[];
    try {
        $dbh = new PDO('mysql:dbname=php_test;host=127.0.0.1', 'root', 'root');
    } catch (PDOException $e) {
        die($e->getMessage());
    }
    $sql = "SELECT * FROM admins WHERE `login`=:login";
    $sth=$dbh->prepare($sql);
    $sth->execute(array('login' => $login));
    if ($sth->rowCount()>0){
        $row=$sth->fetch();
        if ($row['password']==$password){
            $_SESSION['id']=$_COOKIE['PHPSESSID'];
            $_SESSION['key']=md5($login.$password);
            setcookie('id',md5($login.$password));
            $_SESSION['user_id']=$row['id'];
            setcookie('uid',$row['id']);
            header('location: admin');
        }else{
            $error[]="Неверный пароль\n";
        }
    }else{
        $error[]="Пользователь с таким именем не найден";
    }
}

?>