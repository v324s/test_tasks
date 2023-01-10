<?
require_once 'include/settings.php';
require_once 'include/redmine.php';

use Redmine as class_Redmine;


// Принимаем запрос на поиск задачи и обращаемся к классу на получение данных
// Отвечаем в формате JSON
if ($_GET['issue_id']){
    $redmine=new class_Redmine;
    $answer_on_query=$redmine->searchIssue($_GET['issue_id']);
    print_r(json_encode($answer_on_query));
}
?>