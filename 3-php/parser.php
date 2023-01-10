<?php

$headers = array(
    // На всякий случай \/
    // 'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
    // 'Content-Type' => 'application/x-www-form-urlencoded',
    // 'Accept-Language' => 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7', 
    'Accept-Encoding' => 'json',
    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.5060.114 Safari/537.36 OPR/89.0.4447.64',
    'Cookie' => 'ssid=2726236022; ROBINBOBIN=84230a6366e55e70de19c9c2e9; refreg=1661405424~; _ym_uid=1661319752526018913; _ym_isad=2; _ym_d=1661405425'
   );

if ($_POST['url']){
    $url = $_POST['url'];  
}else{
    // $url = 'http://otzovik.com/reviews/myasoet_shop-internet_magazin_myasa/';
    echo 'Вы не указали ссылку';
    exit();
}
    
// получаем запрашиваему страницу
$html=get($url, array(
    'headers' => array(
     // На всякий случай
     // 'Accept: '.$headers['Accept'], 
     // 'Accept-Language: '.$headers['Accept-Language'], 
     // 'Content-Type: '.$headers['Content-Type'], 
        'Accept-Encoding: '.$headers['Accept-Encoding'],
        'User-Agent: '.$headers['User-Agent']
    ),
        'cookies' => $headers['Cookie']
));


// сбор дат в $arr_dateTime
preg_match_all('#<span class="tooltip-right" title="Дата публикации отзыва: .+?">(.+?)</span>#su',$html['headers'],$arr_dateTime);
// сбор авторов в $arr_author
preg_match_all('#<span itemprop="name">(.+?)</span>#su',$html['headers'],$arr_author);
// сбор оценок в arr_grade
preg_match_all('#<meta itemprop="ratingValue" content="([0-9])">#su',$html['headers'],$arr_grade);
// сбор отзывов в arr_text
preg_match_all('#<div class="review-teaser" itemprop="description">(.+?)</div>#su',$html['headers'],$arr_text);
// во всех массивах все данные будут лежать под 1 номером $arr_author[1]

// создаем массив для ответа на запрос и заполняем его полученными данными
$mass=[];
for ($i=0; $i < count($arr_dateTime[1]) ; $i++) { 
    $mass[] = [
        "date" => $arr_dateTime[1][$i],
        "author" => $arr_author[1][$i],
        "grade" => $arr_grade[1][$i],
        "text" => $arr_text[1][$i]
    ];
}

if ($_POST['json']=='true')
    print_r(json_encode($mass));
elseif ($_POST['json']=='false')
    print_r($mass);


function get($url = null, $params = null) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    
    if(isset($params['params'])) {
        curl_setopt($ch, CURLOPT_GET, 1);
        curl_setopt($ch, CURLOPT_GETFIELDS, $params['params']);
    }
    
    if(isset($params['headers'])) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $params['headers']);
    }
    
    if(isset($params['cookies'])) {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $path);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $path);
        curl_setopt($ch, CURLOPT_COOKIE, $params['cookies']);
    }
    curl_setopt($ch, CURLOPT_COOKIEJAR, $path);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $path);

    $result = curl_exec($ch);
    $result_explode = explode("\r\n\r\n", $result);
    
    $headers = ((isset($result_explode[0])) ? $result_explode[0]."\r\n" : '').''.((isset($result_explode[1])) ? $result_explode[1] : '');
    $content = $result_explode[count($result_explode) - 1];
    
    preg_match_all('|Set-Cookie: (.*);|U', $headers, $parse_cookies);
    
    $cookies = implode(';', $parse_cookies[1]);
    
    curl_close($ch);
    
    return array('headers' => $headers, 'cookies' => $cookies, 'content' => $content);
}
function curl($url){
	global $path;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    if(isset($params['params'])) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params['params']);
    }
    if(isset($params['headers'])) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $params['headers']);
    }
    
    if(isset($params['cookies'])) {
        curl_setopt($ch, CURLOPT_COOKIE, $params['cookies']);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $path);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $path);
    }
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_USERAGENT, $headers['User-Agent']);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $path);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $path);
    $response = curl_exec($ch);
    curl_close($ch);
    return iconv("Windows-1251", "UTF-8", $response);
}

?>
