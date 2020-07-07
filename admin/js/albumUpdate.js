(function ($) {
    'use strict';
    $(function () {

        let _key = $(".key").val();
        let _id = $(".id").val()
        $('.openfile').on("click", function () {
            $(this).parent().find('.file-upload-default').trigger('click')
        })

        $('.image-upload').on('change', function (e) {
            $(".image-upload-progress").show()
            let imagefiles = new FormData();
            $.each(e.target.files, (key, image) => {
                imagefiles.append(`images[${key}]`, image)
            })
            $.ajax({
                xhr: function () {
                    let xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (ext) {
                        if (ext.lengthComputable) {
                            let perCentComplete = ((ext.loaded / ext.total) * 100).toFixed();
                            $(".image-bar").width(perCentComplete + '%');
                            $(".image-bar").html(perCentComplete + '% (' +
                                (ext.loaded / (1024 * 1024)).toFixed(2) + 'mb of ' + (ext.total / (1024 * 1024)).toFixed(2) + 'mb)');
                        }
                    }, false)
                    return xhr;
                },
                url: `http://127.0.0.1:8090/api/v1/images/${_key}`,
                type: 'POST',
                data: imagefiles,
                processData: false,
                contentType: false,
                responseType:'application/json'

            })
                .done((msg) => {
                    let images = JSON.parse(msg);
                    $(".image-list").html("")
                    $.each(images, function (key, image) {
                            
                            $(".image-list").append(`
                                <li tabindex='0' class='el-upload-list__item is-ready'>
                                <img src='/${image}' alt='' class='el-upload-list__item-thumbnail'>
                                </li>
                            `);
                    });
                    $(".del-thumbnail").show();
                })
                .fail((err) => {
                    console.log(err.responseText)
                    alert("unexpected error occured")
                })

            // alert($(this).val())

        });
        
        $('.audio-upload').on('change', function (e) {
            const selectedAudio = e.target.files[0];
            albumFile.Audio = selectedAudio;
            // alert(selectedAudio); [Object] [Object]
            const reader = new FileReader();
            reader.onload = f => {
                $(".audio-active").html(`
                    <li class='el-upload-list__item is-ready'>
                    <div class='el-upload-list__item-thumbnail'>
                    <div class='el-upload el-upload-item_song'>
                    <i class='mdi mdi-48px mdi-headphones'></i>
                    </div>
                    <audio src='${f.target.result}' class='el-upload-list__item-song' controls></audio>
                    </div>
                    </li>
                `)
                // alert(formData.postImages);
            }
            reader.readAsDataURL(selectedAudio);
        });
        $(".del-thumbnail").on("click", function () {
            let perm = confirm("Confirm to Erase this images");
            if (!perm) {
                return false
            }
            $.ajax({
                url: `http://127.0.0.1:8090/api/v1/images/delete/${_key}`,
                type: "DELETE"
            })
                .done((msg) => {
                    $(".image-list").html("");
                    $(this).hide();
                    console.log(msg)
                })
                .fail((err) => {
                    console.log(err.responseText);
                    alert("Unexpected error occured")
                })
        })
        $('#handleSubmit').on('click', function () {
            let album_name = $('#album_title').val();
            let album_details = $('#about_album').val();
            let year = $('#album_year').val();
            let artist = $('#artist').val();
            let popular = $('.popular').prop("checked") === true ? 1 : 0
            let fields = [album_name, album_details, artist]
            //check for empty fields
            for (let field = 0; field < fields.length; field++) {
                if (fields[field] == '') {
                    return alert("All fields are required")
                }
            }
            $(this).text("Updating...")

            let data = {
                album_name: album_name,
                album_details: album_details,
                artist: artist,
                year: year,
                popular: popular
            };
            console.log(data)
            $.ajax({
                url: `http://127.0.0.1:8090/api/v1/album/${_id}`,
                type: 'PUT',
                data: JSON.stringify(data),
                dataType: 'json',
                headers: { 'Content-Type': 'application/json' },
                crossDomain: true
                
            })
                .done(function (msg) {
                    $(".status-msg").show()
                    //reset All State to default
                    console.log(msg)
                    $('#handleSubmit').html(`<i class="mdi mdi-content-save-all btn-icon-prepend"></i>
                    Save All`)
                    $('body,html').animate({
                        scrollTop: -1,
                        // opacity: 0
                      }, 1000);
                    $(".status-msg").slideUp(3000)
                })
                .fail(function (err) {
                    alert("Sorry, Something went wrong \nif problem persist contact developer")
                    console.log(err)

                })
        })

    });
})(jQuery);