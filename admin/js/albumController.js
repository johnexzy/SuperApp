(function ($) {
    'use strict';
  
    $(function () {

      let musicFile = {
        Image: [],
        Audio: []
      };
      $('.openfile').on("click", function () {
        $(this).parent().find('.file-upload-default').trigger('click');
        alert($("#popular").val())
      });
      $('.image-upload').on('change', function (e) {
          $.each(e.target.files, function(key, images){
            const selectedImg = images;
            musicFile.Image.push(selectedImg);
            // const selectedImg = elem
            const reader = new FileReader();
            reader.onload = f => {
              
              $(".del-thumbnail").show();
              $(".image-list").append(
                "<li tabindex='0' class='el-upload-list__item is-ready'>" +
                "<img src='" + f.target.result + "' alt='' class='el-upload-list__item-thumbnail'>" +
    
                "</li>"
              );
              // alert(formData.postImages);
            };
            reader.readAsDataURL(selectedImg);
          });
      });
  
      $('.audio-upload').on('change', function (e) {
        
        // console.log(hrefs)
        $.each(e.target.files, function(key, file){
            const selectedAudio = file;
            musicFile.Audio.push(selectedAudio);
            // alert(selectedAudio); [Object] [Object]
            const reader = new FileReader();
            reader.onload = f => {
            $(".del-song").show();
            $(".audio-active").append(
                "<li class='el-upload-list__item is-ready'>" +
                "<div class='el-upload-list__item-thumbnail'>" +
                "<div class='el-upload el-upload-item_song'>" +
                "<i class='mdi mdi-48px mdi-headphones'></i>" +
                "</div>" +
                "<audio src='" + f.target.result + "' class='el-upload-list__item-song' controls></audio>" +
                "</div>" +
                "</li>"
            );
            // alert(formData.postImages);
            };
            reader.readAsDataURL(selectedAudio);

        });
        
  
  
        // alert($(this).val())
      });
      $(".del-thumbnail").on("click", function () {
        $(".image-list").html("");
        musicFile.Image = [];
        $(this).hide();
      });
      $(".del-song").on("click", function(){
          $(".audio-active").html("");
          musicFile.Audio = [];
          $(this).hide();
      });
      
      $('#handleSubmit').on('click', function () {
        let hrefs = new String(window.location);
        hrefs = hrefs.split('8090');
        let album_name = $('#album_title').val();
        let album_details = $('#about_album').val();
        let artist = $('#artist').val();
        let uploaded_by = $('#author').val();
        let year = $('#album_year').val();
        let fields = [album_name, album_details, artist, uploaded_by];
        //check for empty fields
        if (musicFile.Image.length < 1 || musicFile.Audio.length < 1) {
          return alert("no image or Audio selected");
        }
  
        for (let field = 0; field < fields.length; field++) {
          if (fields[field] === '') {
            return alert("All fields are required");
          }
  
        }
        $(this).text("Posting...");
  
        let formData = new FormData();
  
        formData.append('album_name', album_name);
        formData.append('album_details', album_details);
        formData.append('artist', artist);
        formData.append('year', year);
        $.each(musicFile.Image, function (key, image) {
          formData.append(`album_images[${key}]`, image);
        });
        $.each(musicFile.Audio, function(key, audio){
            formData.append(`album_files[${key}]`, audio);
        });
        
        formData.append('author', uploaded_by);
  
        $.ajax({
          xhr: function () {
            let xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function (ext) {
              if (ext.lengthComputable) {
                let perCentComplete = ((ext.loaded / ext.total) * 100).toFixed();
                $(".progress-bar").width(perCentComplete + '%');
                $(".progress-bar").html(perCentComplete + '%');
              }
            }, false);
            return xhr;
          },
          beforeSend: function () {
            $(".progress-bar").html('0%');
  
            $(".progress-bar").width('0%');
          },
          url: "http://127.0.0.1:8090/api/v1/album",
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false
  
        })
        .done(function () {
          $(".status-msg").show();
          //reset All State to default
          $("#handleSubmit").html('<i class="mdi mdi-upload btn-icon-prepend"></i>Upload</button>');
          $('#album_title').val("");
          $('#album_name').val("");
          $('#album_year').val("");
          $('#about_album').val("");
          $('#artist').val("");


  
          $(".del-thumbnail").click();
          $(".del-song").click();

          $('body,html').animate({
            scrollTop: -1
          }, 1000);
          $(".status-msg").slideUp(4000);
        })
        .fail(function(err){
            console.log(err);
            alert("Sorry, something went wrong");
            
        });
      });
  
    });
  })(jQuery);