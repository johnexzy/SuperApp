(function($){
    'use strict';

    $(function(){
        $(".delete").on("click", function(){
            let music_id = $(this).parent().prop("id")
            $.ajax({
                url:`http://127.0.0.1:8090/api/v1/music/delete/${music_id}`,
                type: "DELETE"
            })
            .done((data)=> console.log(data))
            .fail((err)=>console.log(err.responseText))

            // $(this).parent().parent().parent().parent().hide()
        })
    })
})(jQuery)