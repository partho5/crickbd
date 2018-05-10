$(document).ready(function(){
    $('.dropdown-submenu .test').on("click", function(e){
        console.log('kaj kore');
        $(this).next('ul').toggle();
        e.stopPropagation();
        e.preventDefault();
    });
});