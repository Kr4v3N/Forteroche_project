jQuery(function($){

    let alert = $('#alert');
    if(alert.length > 0){
        alert.hide().slideDown(500).delay(3500).slideUp();
    }
    let alert2 = $('#alert1');
    if(alert.length > 0){
        alert2.hide().slideDown(500);
        alert2.find('.close').click(function(e){
            e.preventDefault();
            alert.slideUp();
        })
    }

});
