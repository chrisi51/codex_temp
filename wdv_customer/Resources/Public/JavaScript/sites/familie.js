$(document).ready(function () {
    /* prevent mobile category link clicks */
    $("#mainnav .menu .menu_item > a").on("click", function(e){
        if($(this).parent().hasClass("subs"))
        {
            e.preventDefault();
            return false;
        }else{
            return true;
        }
    });

    $(".menu_backlink").on("click", function(e){
        e.preventDefault();
    })
});