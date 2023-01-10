<?
if ($_GET['action']=='logout'){
    session_unset();
    session_destroy();
    setcookie("PHPSESSID",session_create_id());
    setcookie('id','');
    setcookie('uid','');
    header('location: admin');
}
?>