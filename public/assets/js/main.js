
$(document).ready(function($){

    var alert = $('#alert');
    if(alert.length > 0){
        alert.hide().slideDown(500).delay(3500).slideUp();
    }
    var alert = $('#alert1');

    if(alert.length > 0){
        alert.hide().slideDown(500);
        alert.find('.close').click(function(e){
            e.preventDefault();
            alert.slideUp();
        })
    }

});
