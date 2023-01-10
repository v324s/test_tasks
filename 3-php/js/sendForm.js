$(document).ready(function(event) {
    $('form[name=getReviews]').submit(function(event){
        event.preventDefault();
        $.post('parser.php',{
            'url': $('#input_url').val(),
            'json': $('#flexSwitchCheckDefault')[0].checked
        },function(e){
            $('.modal-body').text(e);
            $('#showModal').click();
        });
    });
});