<?
// Заносим полученные данные в переменную
$data = json_decode(file_get_contents('php://input'), true);
if ($data['lname'] && $data['fname'] && $data['email']){
    // Если запрос содержит все необходимые нам данные для регистрации, то начинаем обрабатывать эти значения
    $lname=check_input($data['lname']);
    $fname=check_input($data['fname']);
    $email=check_input($data['email']);

    // создаем массив для хранения ошибок и для будущего ответа на текущий запрос
    $err=[];
    $answer=[];

    // Валидация Имени, Фамилии и Email
    if (mb_strlen($lname)<2 || mb_strlen($lname)>30 || 
        mb_strlen($fname)<2 || mb_strlen($fname)>30)
        $err[] = "Длина имени/фамилии должна быть от 2 до 30 символов"; 
    if (preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]|[\d]/",$lname.$fname)) /* если в имени содержатся недопустимые символы */
        $err[] = "В написании имени/фамилии допустимы только буквы латинского и русского алфавита";
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $err[] = "E-mail адрес указан неверно.";
    } 

    // Если в процессе валидации были обнаружены ошибки, то формируем ответ
    if (count($err)>0){
        $tempMessage="";
        foreach ($err as $val) {
            $tempMessage.=$val."\n";
        }
        $answer=['error' => 1, 'text' => $tempMessage];
    }else{
        // Если ошибок не обнаружено, то подключаемся к БД ...
        try {
            $dbh = new PDO('mysql:dbname=phpdev;host=127.0.0.1', 'gusev', '123123');
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        // ... выполняем запрос ...
        $sth = $dbh->prepare("INSERT INTO `users` SET `first_name` = :fname, `last_name` = :lname, `email` = :email");
        try{
            // ... Указываем параметры и проверяем на успех выполнения запроса. За одно, формируем ответ
            $sth->execute(array('fname' => $fname, 'lname' => $lname, 'email' => $email));
            $answer=['error' => 0, 'text' => 'Вы успешно зарегистрированы!'];
        }catch (PDOException $e) {
            die($e->getMessage());
            $answer=['error' => 1, 'text' => 'Серверная ошибка регистрации'];
        }
    }
    // формируем JSON и отвечаем
    if ($answer){
        $answer=json_encode($answer,true);
        echo $answer;
    }
}

// функция обработки значений
function check_input($val)
{
    $val = trim($val);
    $val = stripslashes($val);
    $val = htmlspecialchars($val);
    return $val;
}
?>