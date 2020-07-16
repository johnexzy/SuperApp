(function ($) {
    'use strict';
  
    $(function () {

      let musicFile = {
        Image: []
      };
      $('.openfile').on("click", function () {
        $(this).parent().find('.file-upload-default').trigger('click');
        
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

      $(".del-thumbnail").on("click", function () {
        $(".image-list").html("");
        musicFile.Image = [];
        $(this).hide();
      });

      $('#handleSubmit').on('click', function () {
        let series_name = $('#series_title').val();
        let series_details = $('#about_series').val();
        let uploaded_by = $('#author').val();
        let popular = $('.popular').prop("checked") === true ? 1 : 0
        let fields = [series_name, series_details, uploaded_by];
        //check for empty fields
        if (musicFile.Image.length < 1) {
          return alert("no image selected");
        }
  
        for (let field = 0; field < fields.length; field++) {
          if (fields[field] === '') {
            return alert("All fields are required");
          }
  
        }
        $(this).text("Creating...");
  
        let formData = new FormData();
  
        formData.append('series_name', series_name);
        formData.append('series_details', series_details);
        formData.append('popular', popular);
        $.each(musicFile.Image, function (key, image) {
          formData.append(`series_images[${key}]`, image);
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
          url: "http://127.0.0.1:8090/api/v1/series",
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false
  
        })
        .done(function () {
          $(".status-msg").show();
          //reset All State to default
          $("#handleSubmit").html('<i class="mdi mdi-creation btn-icon-prepend"></i>Create</button>');
          $('#series_title').val("");
          $('#about_series').val("");
          $('#artist').val("");


  
          $(".del-thumbnail").click();

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