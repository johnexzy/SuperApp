(function ($) {
    'use strict';
  
    $(function () {

      let videoFile = {
        Image: [],
        Video: null
      };
      $('.openfile').on("click", function () {
        $(this).parent().find('.file-upload-default').trigger('click')
        // alert($('input[name=Category]:checked').val())
      })
      $('.image-upload').on('change', function (e) {
        $.each(e.target.files, function(key, images){
          const selectedImg = images;
          videoFile.Image.push(selectedImg);
          // const selectedImg = elem
          const reader = new FileReader();
          reader.onload = f => {
            
            $(".del-thumbnail").show();
            $(".image-list").append(
              `<li tabindex='0' class='el-upload-list__item is-ready'>
              <img src='${f.target.result}' alt='' class='el-upload-list__item-thumbnail'>
              </li>`
            );
          };
          reader.readAsDataURL(selectedImg);
        });
      });
  
      $('.video-upload').on('change', function (e) {
        let selectedVideo = e.target.files[0];
        videoFile.Video = selectedVideo;
        // console.log(selectedVideo)
          $(".video-active").html(
            `<li class='el-upload-list__item is-ready'>
            <div class='el-upload-list__item-thumbnail'>
                name: ${(selectedVideo.name).bold()}
                <br>
                size: ${(selectedVideo.size/(1024 * 1024)).toFixed(1).bold()}mb
            </div>
            </li>`
          )

      });
      $(".del-thumbnail").on("click", function () {
        $(".image-list").html("");
        videoFile.Image = [];
        $(this).hide();
      })
      $('#handleSubmit').on('click', function () {
        
        let video_name = $('#video_title').val();
        let video_details = $('#about_video').val();
        let uploaded_by = $('#author').val();
        let category= $('input[name=Category]:checked').val();
        let popular = $('#popular').prop("checked") === true ? 1 : 0
        let fields = [video_name, video_details, artist, uploaded_by]
        //check for empty fields
        if (videoFile.Image.length < 1 || videoFile.Video === null) {
          return alert("no image or Audio selected")
        }
  
        for (let field = 0; field < fields.length; field++) {
          if (fields[field] == '') {
            return alert("All fields are required")
          }
  
        }
        $(this).text("Uploading...")
  
        let formData = new FormData();
  
        formData.append('video_name', video_name)
        formData.append('video_details', video_details)
        formData.append('category', category)
        formData.append('popular', popular)
        $.each(videoFile.Image, function (key, image) {
          formData.append(`video_images[${key}]`, image)
        })
        formData.append('video_file', videoFile.Video)
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
          url: "http://127.0.0.1:8090/api/v1/videos",
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false
  
        })
        .done(function () {
          $(".status-msg").show()
          //reset All State to default
          $("#handleSubmit").html('<i class="mdi mdi-upload btn-icon-prepend"></i>Upload</button>')
        //   $('#music_title').val() == "";
        //   $('#music_title').val() == "";
        //   $('#music_title').val() == "";
  
          $(".del-thumbnail").click();
          $('body,html').animate({
            scrollTop: -1
          }, 1000);
          $(".status-msg").slideUp(4000)
        })
        .fail(function(err){
          alert("Sorry, Something went wrong \nif problem persist contact developer")
            console.log(err)
            
        })
      })
  
    });
  })(jQuery);