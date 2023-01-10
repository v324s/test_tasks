// const NUMBER_TASK_ON_PAGE = 20; // Если строго 20



// Объявление переменных
var NUMBER_TASK_ON_PAGE=20;
var offset=0;
var number_page=1;

var filter_enabled=false;
var filter__status, filter__author_id, filter__issue_id, filter__assigned_id;

var btn_prev_down=false;
var btn_next_down=false;



// Нажатии на кнопку "Предыдущая страница"
$('#btn_prev_page').click(function(){
    offset-=NUMBER_TASK_ON_PAGE;
    number_page--;
    btn_prev_down=true;
    getIssues();
});

// Нажатии на кнопку "Следующая страница"
$('#btn_next_page').click(function(){
    offset+=NUMBER_TASK_ON_PAGE;
    number_page++;
    btn_next_down=true;
    getIssues();
});

// Нажатии на кнопку "Сортировать"
$('#btn_filter-sort').click(function(){
    getIssues();
});

// Нажатии на кнопку "Поиск" (по номеру задачи)
$('#btn_filter-search-issue').click(function(){
    getIssues('issue');
});

// Нажатии на кнопку "Показать/скрыть фильтр"
$('#btn_toggle-filter').click(function(){
    $('#filter_menu').css('display')=='none' ? $('#filter_menu').slideDown(300) : $('#filter_menu').slideUp(300);
});



// Функция запроса задач
function getIssues(search=null){
    // Очищается содержимое таблицы
    $('#tbody_issues').html("");

    // Показывается анимация загрузки, запрашиваемый номер страницы и скрываются кнопки "Предыдущая/следующая страница"
    if ($('#table_loading').hasClass('d-none')){
        $('#table_loading').removeClass('d-none');
        $('#btn_prev_page').addClass('notVisible');
        $('#btn_next_page').addClass('notVisible');    
        $('#number_page').text(number_page);
    }

    // Функция сброса смещения(offset), при сортировки
    if (filter__status || filter__author_id || filter__issue_id || filter__assigned_id){
        if (!filter_enabled){
            filter_enabled=true;
            number_page=1;
            offset=0;
        }else{
            if (!btn_prev_down && !btn_next_down){
                number_page=1;
                offset=0;
            }else{
                btn_prev_down=false;
                btn_next_down=false;
            }
        }
    }else{
        filter_enabled=false;
        btn_prev_down=false;
        btn_prev_down=false;
    }

    // Если осуществляется поиск задачи, то формируем конкретный запрос
    if (search=='issue' && filter__issue_id && filter__issue_id!=NaN && typeof filter__issue_id == 'number'){
        let method='api/searchIssue';
        let data_query={};
        data_query.issue_id=filter__issue_id;
        $.get(method, data_query, function (e){printOnPage(e)});
    }else{
        let method='api/getIssues';
        data_query={};
        offset>=0 ? data_query.offset=offset : data_query.offset=0;
        NUMBER_TASK_ON_PAGE>=1 && NUMBER_TASK_ON_PAGE<=25 ? data_query.count=NUMBER_TASK_ON_PAGE : data_query.count=20;
        filter__status>=1 && filter__status<=10 ? data_query.status_id=filter__status : '';
        typeof filter__author_id == 'number' && !isNaN(filter__author_id) ? data_query.author_id=filter__author_id : '';
        typeof filter__assigned_id == 'number' && !isNaN(filter__assigned_id) ? data_query.assigned_id=filter__assigned_id : '';
        $.get(method, data_query, function (e){printOnPage(e)});
    }
}



// Функция получения и размещеная данных в таблицу
function printOnPage(data){
    data=JSON.parse(data);

    // Формируем верстку с данными
    let html;
    for (let i = 0; i < data.length; i++) {
        html+="<tr>";
        html+="<th scope='row'>"+(offset+i+1)+"</th>";
        html+="<td>"+data[i]['id']+"</td>";
        html+="<td>"+data[i]['project']+"</td>";
        html+="<td>"+data[i]['whom']['name']+"<br> (id - "+data[i]['whom']['id']+")</td>";
        html+="<td>"+data[i]['issue']['name']+"<br><a href='"+data[i]['issue']['link']+"'>"+data[i]['issue']['link']+"</a></td>";
        html+="<td>"+new Date(data[i]['created'])+"</td>";
        html+="<td>"+data[i]['hours']+"</td>";
        html+="<td>"+data[i]['wasted_time']+"</td>";
        html+="<td>"+data[i]['coeff']+"</td>";
        html+="</tr>";
    }

    // Наполняем таблицу нашей версткой с данными
    $('#tbody_issues').html(html);

    // Отключаем анимацию загрузки
    if (!$('#table_loading').hasClass('d-none'))
        $('#table_loading').addClass('d-none');

    // Отключаем видимость кнопки "Предыдущая страница", если текущая страница = 1
    if (number_page==1 && !$('#btn_prev_page').hasClass('notVisible')) {
        $('#btn_prev_page').addClass('notVisible');
    }else if (number_page>1 && $('#btn_prev_page').hasClass('notVisible')) {
        $('#btn_prev_page').removeClass('notVisible');
    }
    
    // Отображаем номер страницы  
    $('#number_page').text(number_page);

    // Отключаем видимость кнопки "Следующая страница", если кол-во полученных данных < кол-ву запрашиваемыхданных
    if (data.length<NUMBER_TASK_ON_PAGE) {
        $('#btn_next_page').addClass('notVisible');
    }else{
        $('#btn_next_page').removeClass('notVisible');
    }
}