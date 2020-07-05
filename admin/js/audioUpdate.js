(function ($) {
    'use strict';
    $(function () {
        let _key = $(".key").val();
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
                .done((images) => {
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
            musicFile.Audio = selectedAudio;
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
            let music_name = $('#music_title').val();
            let music_details = $('#about_music').val();
            let artist = $('#artist').val();
            let uploaded_by = $('#author').val();
            let popular = $('.popular').prop("checked") === true ? 1 : 0
            let fields = [music_name, music_details, artist, uploaded_by]
            //check for empty fields
            if (musicFile.Image.length < 1 || musicFile.Audio === null) {
                return alert("no image or Audio selected")
            }

            for (let field = 0; field < fields.length; field++) {
                if (fields[field] == '') {
                    return alert("All fields are required")
                }

            }
            $(this).text("Posting...")

            let formData = new FormData();

            formData.append('music_name', music_name)
            formData.append('music_details', music_details)
            formData.append('artist', artist)
            formData.append('popular', popular)
            $.each(musicFile.Image, function (key, image) {
                formData.append(`music_images[${key}]`, image)
            })
            formData.append('music_file', musicFile.Audio)
            formData.append('author', uploaded_by)

            $.ajax({
                xhr: function () {
                    let xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (ext) {
                        if (ext.lengthComputable) {
                            let perCentComplete = ((ext.loaded / ext.total) * 100).toFixed();
                            $(".progress-bar").width(perCentComplete + '%');
                            $(".progress-bar").html(perCentComplete + '%');
                        }
                    }, false)
                    return xhr;
                },
                beforeSend: function () {
                    $(".progress-bar").html('0%');

                    $(".progress-bar").width('0%');
                },
                url: "http://127.0.0.1:8090/api/v1/music",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false

            })
                .done(function () {
                    $(".status-msg").show()
                    //reset All State to default
                    $("#handleSubmit").html('<i class="mdi mdi-upload btn-icon-prepend"></i>Upload</button>')
                    $('#music_title').val() == "";
                    $('#music_title').val() == "";
                    $('#music_title').val() == "";

                    $(".del-thumbnail").click();
                    $('body,html').animate({
                        scrollTop: -1
                    }, 1000);
                    $(".status-msg").slideUp(4000)
                })
                .fail(function (err) {
                    alert("Sorry, Something went wrong \nif problem persist contact developer")
                    console.log(err)

                })
        })

    });
})(jQuery);