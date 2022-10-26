$.ajaxSetup({
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

function get_datas(url) {
  return new Promise((resolve) => {
      $.get(url, function(data) {
          resolve(data)
      });
  })
}

function makeOptionSelected(formId, fieldId, id) {
  $(`#${formId} #${fieldId} option[value=${id}]`)
      .attr(
          'selected',
          'selected');
}


function generate_code(len) {
  var result           = '';
  var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  var charactersLength = characters.length;
  for ( var i = 0; i < len; i++ ) {
    result += characters.charAt(Math.floor(Math.random() * 
    charactersLength));
  }
  return result;
}


$(".modal").each(function(l) {
  $(this).on("show.bs.modal", function(l) {
      let o = $(this).attr("data-easein");
      "shake" == o ? $(".modal-dialog").velocity("callout." + o) : "pulse" == o ? $(".modal-dialog").velocity("callout." + o) : "tada" == o ? $(".modal-dialog").velocity("callout." + o) : "flash" == o ? $(".modal-dialog").velocity("callout." + o) : "bounce" == o ? $(".modal-dialog").velocity("callout." + o) : "swing" == o ? $(".modal-dialog").velocity("callout." + o) : $(".modal-dialog").velocity("transition." + o)
  })
});