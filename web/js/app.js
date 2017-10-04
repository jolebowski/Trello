$( document ).ready(function() {

    $( ".show_add" ).click(function() {
        $(this).hide( "slow" );
        $(".list-content").hide( "slow" );
        $(this).parent().parent().children(".list-content").show( "slow" );
    });

    $(".hide_add").click(function(){
        $(".list-content" ).hide("slow");
    });

    $(".hide_add").click(function(){
        $(".show_add").show("slow");

    });

    $(".show_add").click(function(){
        $(this).addClass(".list-content");
    });

    var inputVal = document.getElementById("fh5co-main");
   // $("#fh5co-hero section:nth-child(1)").attr("style", "background: red !important");

    $('.color0').click(function(){
        console.log( $("#fh5co-main").html()  );
        inputVal.style.backgroundColor = "#fff";
    });

    $('.color1').click(function(){
        console.log( $("#fh5co-main").html()  );
        inputVal.style.backgroundColor = "blue";
    });

    $('.color2').click(function(){
        console.log( $("#fh5co-main").html()  );
        inputVal.style.backgroundColor = "#4cae4c";
    });

    $('.color3').click(function(){
        console.log( $("#fh5co-main").html()  );
        inputVal.style.backgroundColor = "#7a43b6";
    });

    $('.color4').click(function(){
        console.log( $("#fh5co-main").html()  );
        inputVal.style.backgroundColor = "#d9edf7";
    });

    $('.color5').click(function(){
        console.log( $("#fh5co-main").html()  );
        inputVal.style.backgroundColor = "coral";
    });

    $('.color6').click(function(){
        console.log( $("#fh5co-main").html()  );
        inputVal.style.backgroundColor = "yellow";
    });


});