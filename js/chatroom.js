$('.chatroom__form__inputfile').bind('change', function () {
    var filename = $(".chatroom__form__inputfile").val();
    if (/^\s*$/.test(filename)) {
      $(".chatroom__form__attach").removeClass('chatroom__form__attach--active');
    }
    else {
      $(".chatroom__form__attach").addClass('chatroom__form__attach--active');
    }
  });
  