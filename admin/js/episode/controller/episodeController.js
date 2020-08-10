(function ($) {
    'use strict';
  
    $(function () {

      let videoFile = {
        Image: [],
        Video: null,
        Error: false
      };
      let movieData = {
        video_name :'',
        video_details :'',
        category :'',
        popular :'',
        author:''
      }
      /**
       * Check for Empty fields
       * @return boolean
       */
      function checkEmptyField(){
        let ep_name = $('#ep_number').val();
        let ep_details = $('#about_video').val();
        let fields = [ep_name, ep_details]
        if (videoFile.Image.length < 1) {
          alert("no image selected")
          return false
        }
  
        for (let field = 0; field < fields.length; field++) {
          if (fields[field] == '') {
            videoFile.Error = true
            alert("All fields are required")
            return false
          }
          else {
            videoFile.Error = false
          }
        }
        return true
      }
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
      let uploader = new plupload.Uploader({
        runtimes : 'html5,flash,silverlight,html4',
        browse_button : document.getElementById('pickfiles'), // you can pass an id...
        chunk_size : '4mb',
        unique_names : true,
        multi_selection: false,
        url : 'http://127.0.0.1:8090/api/v1/file',
        flash_swf_url : '../js/Moxie.swf',
        silverlight_xap_url : '../js/Moxie.xap',
        max_file_count: 1,
        multipart_params: movieData,
        filters : {
            max_file_size : '1000mb',
            mime_types: [
                {title : "Video files", extensions : "mp4,avi,mkv,mov"}
            ]
        },
        
        init: {
            PostInit: function() {
                $("#handleSubmit").on('click', function(){
                  if (checkEmptyField()) {
                    uploader.start()
                  }
                  
                })
            },

            FilesAdded: function(up, files) {
                plupload.each(files, function(file) {
                  $(".video-active").html(
                      `<li class='el-upload-list__item is-ready'>
                        <div id="${file.id}" class='el-upload-list__item-thumbnail'>
                            name: ${(file.name).bold()}
                            <br>
                            size: ${(plupload.formatSize(file.size)).bold()}
                        </div>
                      </li>`
                    )
                });
            },

            UploadProgress: function(up, file) {
                $(".progress-bar").width(`${file.percent}%`);
                $(".progress-bar").html(`${file.percent}%`);
            },
            FileUploaded: (up, files, info)=>{
                let path = JSON.parse(info.response)
                console.log(path.info.path)

                handleSubmit(path.info.path, path.info.size)
                
            },
            Error: function(up, err) {
                document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
            }
        }
      });
      uploader.init();
      
      $(".del-thumbnail").on("click", function () {
        $(".image-list").html("");
        videoFile.Image = [];
        $(this).hide();
      })
      function handleSubmit(video_File_url, filebyte) {
        let series_name = $('.series-name').val();
        let ep_name = $('#ep_number').val();
        let ep_details = $('#about_video').val();
        let season_key = $('.key').val()
        videoFile.Video = video_File_url
        if (!checkEmptyField()) {
          return false
        }
        $(this).text("Uploading...")
  
        let formData = new FormData();
  
        formData.append('series_name', series_name)
        formData.append('ep_details', ep_details)
        formData.append('ep_name', "EP_"+ep_name)
        formData.append('season_key', season_key)
        $.each(videoFile.Image, function (key, image) {
          formData.append(`video_images[${key}]`, image)
        })
        formData.append('video_file', videoFile.Video)
        formData.append('video_file_byte', filebyte)
  
        $.ajax({
          url: "http://127.0.0.1:8090/api/v1/episode",
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false
        })
        .done(function () {
          $(".status-msg").show()
          //reset All State to default
          $("#handleSubmit").html('<i class="mdi mdi-upload btn-icon-prepend"></i>Upload</button>')
          //Changes ProgressBar colour on success
          $(".progress-bar").css({
            background: "#cccc0c"
          })
          
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
      }
    });
  })(jQuery);