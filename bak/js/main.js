$('.ea_dark').click(function(){
    $(this).toggleClass('active')
    if ($(this).hasClass('active')) {
        $('body').addClass('dark_mode');
         localStorage.setItem('dark_mode', 'on');
     } else {
         $('body').removeClass('dark_mode');
        localStorage.removeItem('dark_mode');
     }
})


if (localStorage.getItem('dark_mode') === 'on') {
    $('.ea_dark').addClass('active');
    $('body').addClass('dark_mode');
} else {
    $('.ea_dark').removeClass('active');
    $('body').removeClass('dark_mode');
}
