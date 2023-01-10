<?php
session_start();
$userLogined=false;

require_once 'require/query/login.php';
require_once 'require/query/logout.php';


if ($_SESSION['id'] == $_COOKIE['PHPSESSID'] && $_SESSION['key'] == $_COOKIE['id'] && $_SESSION['user_id'] == $_COOKIE['uid']) {
    $userLogined=true;
    try {
        $dbh = new PDO('mysql:dbname=php_test;host=127.0.0.1', 'root', 'root');
    } catch (PDOException $e) {
        die($e->getMessage());
    }

    function getValOnKey($array, $type){
        $str=[];
        $rec = new RecursiveIteratorIterator(new RecursiveArrayIterator($array),RecursiveIteratorIterator::SELF_FIRST);
        foreach ($rec as $key => $val){
            if($key == $type && !is_array($val))
            $str[]=$val;
        }
        return $str;
    }
    
    require_once 'require/query/deleteBranch.php';
    require_once 'require/query/edit.php';
    require_once 'require/query/add.php';
    
    

    
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Страница администратора</title>
	<link rel="stylesheet" href="css/admin.css">
</head>
<body>
<?php

if ($userLogined==true) {
                
    $sql = "SELECT * FROM data";
    $sth=$dbh->prepare($sql);
    $sth->execute();
    $row = $sth->fetchAll();
    ?>
    <div class="notification flex" onclick="hideNotific(event)">
        <section class="flex_form" onclick="event.stopPropagation()">
            <form id="form_addData" class="forma" method="POST" action="admin">
                <div>
                    <h3 class="align-center">Добавление данных</h3>
                </div>
                <div>
                    <span>Название</span><input type="text" name="name" placeholder="Название" autocomplete="off">
                </div>
                <div>
                    <span>Описание</span><input type="text" name="description" placeholder="Описание" autocomplete="off">
                </div>   
                <input id="input_parent_id" type="hidden" name="parent" value="">
                <input type="hidden" name="action" value="addData">
                <div>
                    <input type="submit" value="Добавить">
                </div>        
            </form>
            <form id="form_editData" class="forma" method="POST" action="admin">
                <div>
                    <h3 class="align-center">Редактирование данных</h3>
                </div>
                <div>
                    <span>Родитель</span>
                    <select id='sel_par' name="pid">
                        <option value="-1"></option>
                        <?
                        foreach ($row as $sel){
                            echo "<option value=".$sel['id'].">".$sel['name']."</option>";
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <span>Название</span><input type="text" id="edit_name" name="name" placeholder="Название" autocomplete="off">
                </div>
                <div>
                    <span>Описание</span><input type="text" id="edit_desc" name="description" placeholder="Описание" autocomplete="off">
                </div>   
                <input type="hidden" id="edit_id" name="id" value="">
                <input type="hidden" name="action" value="edit">
                <div>
                    <input type="submit" value="Обновить">
                </div>        
            </form>
        </section>    
    </div>
    <main class="flex zIndex-child">
        <menu class="leftMenu">
            <ul class="leftMenu_ul">
                </li>
                <li>
                    <a class="leftMenu_link" href="admin?action=logout">🚪 Выйти</a>
                </li>
            </ul>
        </menu>
        <section class="content">
            <header class="content_header">
                <h4 class="content_header_h4">Структура данных</h4>
            </header>
            <section class="content_data">
                <button class="but-addMainBranch" onclick="showNotific()">Добавить данные в главную ветку</button>
              <?

                $tempRow=$row;
                $goodArr = array();
                
                foreach($tempRow as $key => &$item) {
                   $goodArr[$item['id']] = &$item;
                   $goodArr[$item['id']]['children'] = array();
                }
                
                foreach($tempRow as $key => &$item)
                   if($item['parent'] && isset($goodArr[$item['parent']]))
                      $goodArr [$item['parent']]['children'][] = &$item;
                
                foreach($tempRow as $key => &$item) {
                   if($item['parent'] && isset($goodArr[$item['parent']]))
                      unset($tempRow[$key]);
                }




                function child($item,$brd){
                    $brd+=30;
                    if (!$item['parent'])
                        $item['parent']='-1';
                    ?>
                    <div class="flex item-row" style="padding-left: <? echo $brd; ?>px;">
                                <div class="branch-control">
                                    <button class="mg-auto button-ico but-add cPointer" onclick="showNotific(<? echo $item['id']; ?>)"></button>
                                    <button class="mg-auto button-ico but-del cPointer" onclick="showConfirm(<? echo $item['id'].', \''.$item['name'].'\''; ?>)"></button>
                                    <button class="mg-auto button-ico but-edit cPointer" onclick="showEdit(<? echo $item['id'].', '.$item['parent'].', \''.$item['name'].'\''.', \''.$item['description'].'\''; ?>)"></button>
                                </div>
                                <div class="branch-info" style="width: calc(100% - 100px -  <? echo $brd; ?>px);">
                                    <div class="cPointer" onclick="toggleDesc(<? echo $item['id']; ?>)"><? echo $item['name']; ?></div>
                                    <div id="description-name_<? echo $item['id']; ?>" class="branch-info_decription"><? echo $item['description']; ?></div>
                                </div>
                            </div>
                    <?
                    if (count($item['children']>0)){
                        foreach($item['children'] as $ch){
                            child($ch,$brd);
                        }
                    }
                }
                

                foreach ($tempRow as $key) {
                    if (!$key['parent'])
                        $key['parent']='-1';
                    ?>
                        <div class="flex item-row">
                            <div class="branch-control">
                                <button class="mg-auto button-ico but-add cPointer" onclick="showNotific(<? echo $key['id']; ?>)"></button>
                                <button class="mg-auto button-ico but-del cPointer" onclick="showConfirm(<? echo $key['id'].', \''.$key['name'].'\''; ?>)"></button>
                                <button class="mg-auto button-ico but-edit cPointer" onclick="showEdit(<? echo $key['id'].', '.$key['parent'].', \''.$key['name'].'\''.', \''.$key['description'].'\''; ?>)"></button>
                            </div>
                            <div class="branch-info">
                                <div class="cPointer" onclick="toggleDesc(<? echo $key['id']; ?>)"><? echo $key['name']; ?></div>
                                <div id="description-name_<? echo $key['id']; ?>" class="branch-info_decription"><? echo $key['description']; ?></div>
                            </div>
                        </div>
                    <?
                $brd=0;
                
                    if (count($key['children']>0)){
                        foreach($key['children'] as $ch){
                            child($ch,$brd);
                        }
                    }
                }
               
                ?>
            </section>
        </section>
        <script src="js/adminfuncs.js"></script>
        <script src="js/funcs.js"></script>
    </main>

    <?
}else{
    
    ?>
    <main class="flex">
        <section class="flex_form">
            <form method="POST" action="admin">
                <div>
                    <h3 class="align-center">Авторизация</h3>
                    <p>Вход в панель управления</p>
                    <p><? if ($error) foreach ($error as $k){ echo $k.'<br>'; } ?></p>
                </div>
                <div>
                    <span>🙍‍♂️</span><input type="text" name="login" placeholder="login">
                </div>
                <div>
                    <span>🔒</span><input type="password" name="password" placeholder="password">
                </div>   
                <div>
                    <input type="submit" value="Войти">
                </div>        
            </form>
        </section>    
    </main>
    <?
}












?>
</body>
</html>

<?php


// функция обработки значений
function check_input($val)
{
    $val = trim($val);
    $val = stripslashes($val);
    $val = htmlspecialchars($val);
    return $val;
}
?>