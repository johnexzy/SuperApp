(function($){
    'use strict';

    $(function(){
        $(".delete").on("click", function(){
            let video_id = $(this).parent().prop("id")
            let video_name = $(this).prop("name")
            let ask = confirm(`Proceed to Delete "${video_name}" and all of its components ?`);
            if (!ask) {
                return null;
            }
            $.ajax({
                url:`http://127.0.0.1:8090/api/v1/videos/delete/${video_id}`,
                type: "DELETE"
            })
            .done((data)=> {
                data == 1 ? $(this).parent().parent().parent().parent().hide() : null;
                console.log(data)
            })
            .fail((err)=>console.log(err.responseText))

            // 
        })
    })
})(jQuery)