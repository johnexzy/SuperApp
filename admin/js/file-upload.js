(function($) {
  'use strict';

  $(function() {
    let err = {
      errTitle: 0,
      errBody: 0
    }
    $("#postTitle").on("input", function() {
      $(this).css({
        borderColor: ($(this).val() == "") ? "red" : "#f3f3f3"
      })
      err.errTitle = ($(this).val() == "") ? 1 : 0

      $(this).val() == "" ? $(this).parent().find("#titleCap").show() : $(this).parent().find("#titleCap").hide()
    })
    $("#postBody").on("input", function() {
      $(this).css({
        borderColor: ($(this).val() == "") ? "red" : "#f3f3f3"
      })
      err.errBody = ($(this).val() == "") ? 1 : 0
      $(this).val() == "" ? $(this).parent().find("#titleCap").show() : $(this).parent().find("#titleCap").hide()
    })
    let formData = {
      postTitle: '',
      postBody: '',
      postCategory: '',
      postImages: [],
      author: 'JohnOba'
    }
    $('.openfile').on("click", function() {
        $(this).parent().find('.file-upload-default').trigger('click')
      })
      // $('.file-upload-browse').on('click', function() {
      //   var file = $(this).parent().parent().parent().find('.file-upload-default');
      //   file.trigger('click');
      // });
    $('.file-upload-default').on('change', function(e) {

      $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
      for (let i = 0; i < e.target.files.length; i++) {
        const selectedImg = e.target.files[i];
        // const selectedImg = elem
        const reader = new FileReader();
        reader.onload = f => {
          formData.postImages.push(f.target.result);
          $(".el-upload-list--picture-card").append(
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
    $('#handleSubmit').on('click', function() {

      formData.postTitle = $('#postTitle').val();
      formData.postBody = $('#postBody').val();
      formData.postCategory = $('#Category').val();

      if (postTitle == "" || postBody == "" || formData.postImages == "") {
        return false;
      }
      $(this).text("Posting...")
      let res;
      let mydata = {
        post_title: formData.postTitle,
        post_body: formData.postBody,
        post_category: formData.postCategory,
        post_images: formData.postImages,
        author: "JohnOba"
      }
      $.ajax({
          url: 'http://127.0.0.1:8090/api/news',
          type: 'POST',
          data: JSON.stringify(mydata),
          dataType: 'json',
          crossDomain: true,
          headers: { 'Content-Type': 'application/json' }
        })
        .done(function(data) {
          // alert(data)
          formData.postImages = [];
          $(".status-msg").show()

          $("#handleSubmit").text("Submit")
          $('#postTitle').val("")
          $('#postBody').val("")
          $(".el-upload-list--picture-card").html("");
          $('body,html').animate({
            scrollTop: -1
          }, 1000);
          $(".status-msg").slideUp(4000)
        })
        .fail(function(err) {
          // console.log(err);
          alert("failed to add Comment");
        })
    })

  });
})(jQuery);