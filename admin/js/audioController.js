(function($) {
  'use strict';

  $(function() {
    //   $("#music_title").on("input", function() {
    //     $(this).css({
    //       borderColor: ($(this).val() == "") ? "red" : "#f3f3f3"
    //     })

    //     $(this).val() == "" ? $(this).parent().find("#titleCap").show() : $(this).parent().find("#titleCap").hide()
    //   })
    //   $("#about_music").on("input", function() {
    //     $(this).css({
    //       borderColor: ($(this).val() == "") ? "red" : "#f3f3f3"
    //     })
    //     $(this).val() == "" ? $(this).parent().find("#aboutCap").show() : $(this).parent().find("#aboutCap").hide()
    //   })
    //   $("#artist").on("input", function() {
    //     $(this).css({
    //       borderColor: ($(this).val() == "") ? "red" : "#f3f3f3"
    //     })
    //     $(this).val() == "" ? $(this).parent().find("#artistCap").show() : $(this).parent().find("#artistCap").hide()
    //   })

    let musicFile = {
      Image: [],
      Audio: null
    };
    $('.openfile').on("click", function() {
      $(this).parent().find('.file-upload-default').trigger('click')
    })
    $('.image-upload').on('change', function(e) {

      for (let i = 0; i < e.target.files.length; i++) {
        const selectedImg = e.target.files[i];

        // const selectedImg = elem
        const reader = new FileReader();
        reader.onload = f => {
          musicFile.Image.push(f.target.result);
          $(".del-thumbnail").show();
          $(".image-list").append(
              "<li tabindex='0' class='el-upload-list__item is-ready'>" +
              "<img src='" + f.target.result + "' alt='' class='el-upload-list__item-thumbnail'>" +

              "</li>"
            )
            // alert(formData.postImages);
        }
        reader.readAsDataURL(selectedImg);

      }
      // alert($(this).val())

    });

    $('.audio-upload').on('change', function(e) {
      // alert((window.location).substr(0, (window.location).indexOf('8090')))
      let hrefs = new String(window.location);
      hrefs = hrefs.split('8090');
      // console.log(hrefs)
      const selectedAudio = e.target.files[0];
      musicFile.Audio = selectedAudio;
      // alert(selectedAudio); [Object] [Object]
      const reader = new FileReader();
      reader.onload = f => {
        musicFile.Image.push(f.target.result);
        $(".audio-active").html(
            "<li tabindex='0' class='el-upload-list__item is-ready'>" +
            "<div class='el-upload-list__item-thumbnail'>"+
            "<div class='el-upload-item_song'>"+
            "<i class='mdi mdi-48px mdi-music-note-plus'></i>"+
            "</div>"+
            "<audio src='" + f.target.result + "' class='el-upload-list__item-song' controls></audio>" +
            "</div>"+
            "</li>"
          )
          // alert(formData.postImages);
      }
      reader.readAsDataURL(selectedAudio);


      // alert($(this).val())
    });
    $(".del-thumbnail").on("click", function() {
      $(".image-list").html("");
      musicFile.Image = [];
      $(this).hide();
    })
    $('#handleSubmit').on('click', function() {
      let hrefs = new String(window.location);
      hrefs = hrefs.split('8090')
      let music_name = $('#music_title').val();
      let music_details = $('#about_music').val();
      let artist = $('#artist').val();
      let uploaded_by = $('#author').val();
      let fields = [music_name, music_details, artist, uploaded_by]
        //check for empty fields
      if (musicFile.Image.length < 1 || musicFile.Audio == null) {
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
      $.each(musicFile.Image, function(key, image) {
        formData.append(`music_images[${key}]`, image)
      })
      formData.append('music_file', musicFile.Audio)
      formData.append('author', uploaded_by)

      $.ajax({
        xhr: function() {
          let xhr = new window.XMLHttpRequest();
          xhr.upload.addEventListener("progress", function(ext) {
            if (ext.lengthComputable) {
              let perCentComplete = ((ext.loaded / ext.total) * 100).toFixed();
              $(".progress-bar").width(perCentComplete + '%');
              $(".progress-bar").html(perCentComplete + '%');
            }
          }, false)
          return xhr;
        },
        beforeSend: function() {
          $(".progress-bar").html('0%');

          $(".progress-bar").width('0%');
        },
        url: "http://127.0.0.1:8090/api/music",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
          alert(data)
        },
        error: function(err) {
          alert(err);
        }

      })
    })

  });
})(jQuery);