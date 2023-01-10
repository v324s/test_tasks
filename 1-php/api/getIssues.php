<?
require_once 'include/settings.php';
require_once 'include/redmine.php';

use Redmine as class_Redmine;

// Принимаем запрос на получение задач и обращаемся к классу на получение данных
// Отвечаем в формате JSON
if ($_GET['offset']>=0 && $_GET['count']){
    $redmine=new class_Redmine;
    $answer_on_query=$redmine->getIssues($_GET['offset'], $_GET['count'], $_GET['status_id'], $_GET['author_id'], $_GET['assigned_id']);
    print_r(json_encode($answer_on_query));
}
?>