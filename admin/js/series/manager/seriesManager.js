(function($){
    'use strict';

    $(function(){
        $(".delete").on("click", function(){
            let series_id = $(this).parent().prop("id")
            let series_name = $(this).prop("name")
            let ask = confirm(`Proceed to Delete "${series_name}" and all of its components ?`);
            if (!ask) {
                return null;
            }
            $.ajax({
                url:`http://127.0.0.1:8090/api/v1/series/delete/${series_id}`,
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