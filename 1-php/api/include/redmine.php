<?
require_once 'settings.php';

use Redmine as class_Redmine;

class Redmine{
    public const HOST = 'http://redmine.org/';

    // Функция поиска задачи 
    public function searchIssue($issue_id = null)
    {
        // Формируем запрос
        $string_query = 'issues/';
        $string_query .= $issue_id ? "{$issue_id}.json" : "";

        // Вытаскиваем данные
        $result_query = file_get_contents(self::HOST.$string_query);
        $result_query=json_decode($result_query, true);

        // Вспомогательная переменная. Служит при формировании массива ответа
        $project_closed = $result_query['issue']['closed_on'] ? True : False;

        // Формируем массив для ответа
        $answer_array = array();
        $answer_array[]=[
            "id" => $result_query['issue']['id'],
            "project" => $result_query['issue']['project']['name'],
            "whom" => [
                "id" => $result_query['issue']['assigned_to'] ? $result_query['issue']['assigned_to']['id'] : "null",
                "name" => $result_query['issue']['assigned_to'] ? $result_query['issue']['assigned_to']['name'] : "null"
            ],
            "issue" => [
                "name" => $result_query['issue']['subject'],
                "link" => "https://redmine.org/issues/".$result_query['issue']['id']
            ],
            "created" => $result_query['issue']['created_on'],
            "hours" => $hours=rand(0,500),
            "wasted_time" => $project_closed ? $this->wastedTime($result_query['issue']['created_on'], $result_query['issue']['closed_on']) : "null",
            "coeff" => $project_closed ? round($this->wastedTime($result_query['issue']['created_on'], $result_query['issue']['closed_on'])/$hours,2) : "null"
            ];

        // отвечаем
        return $answer_array;
    }

    // Функция сбора задач (С сортировкой и без)
    public function getIssues($offset = null, $count = null, $status_id = null, $author_id = null, $assigned_id=null)
    {
        // Формируем запрос
        $string_query = 'issues.json';
        $string_query .= $offset || $count ? "?" : "";
        $string_query .= $offset ? "offset={$offset}" : "";
        $string_query .= $count && !$offset ? "count={$count}" : "";
        $string_query .= $count && $offset ? "&count={$count}" : "";
        $string_query .= $status_id ? "&status_id={$status_id}" : "";
        $string_query .= $author_id ? "&author_id={$author_id}" : "";
        $string_query .= $assigned_id ? "&assigned_to_id={$assigned_id}" : "";

        // Вытаскиваем данные
        $result_query = file_get_contents(self::HOST.$string_query);
        $result_query=json_decode($result_query, true);

        // Формируем массив для ответа
        $answer_array = array();
        foreach ($result_query['issues'] as $key) {
            $project_closed = $key['closed_on'] ? True : False;
            $answer_array[]=[
                "id" => $key['id'],
                "project" => $key['project']['name'],
                "whom" => [
                    "id" => $key['assigned_to'] ? $key['assigned_to']['id'] : "null",
                    "name" => $key['assigned_to'] ? $key['assigned_to']['name'] : "null"
                ], 
                "issue" => [
                    "name" => $key['subject'],
                    "link" => "https://redmine.org/issues/".$key['id']
                ],
                "created" => $key['created_on'],
                "hours" => $hours=rand(0,500),
                "wasted_time" => $project_closed ? $this->wastedTime($key['created_on'], $key['closed_on']) : "null",
                "coeff" => $project_closed ? round($this->wastedTime($key['created_on'], $key['closed_on'])/$hours,2) : "null"
            ];
        }

        // Отвечаем. Если в "ответном" массиве строк больше, чем запрашиют, то обрезаем его
        if (count($answer_array) > $count)
            return array_slice($answer_array, 0, $count);
        else
            return $answer_array;
    } 

    // Функция подсчета затраченного времени (в часах)
    // Дата создания (created_on) - Дата закрытия (closed_on) = Разность
    // Секунды, минуты, дни -> переводим в часы до сотых
    private function wastedTime($created, $closed=null)
    {
        $date_1 = new DateTime($created);
        $date_2 = new DateTime($closed);
        $difference = $date_2->diff($date_1);
        $wasted=0;
        if ($difference->s>0 && round($difference->s/60/60,2)>=0.01)
            $wasted+=round($difference->s/60/60,2);
        if ($difference->i>0)
            $wasted+=round($difference->i/60,2);
        $wasted += $difference->h+($difference->days*24);
        return $wasted;
    }
}

?>