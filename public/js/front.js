var _token = $('input[name="_token"]').val();
var contact_form = '#fm_enviar_email';

$(contact_form).on('submit',function(e){
  e.preventDefault();
})

$.validate({
  form : contact_form,
  onError : function($form) {
    //alert('Validation of form '+$form.attr('id')+' failed!');
  },
  onSuccess : function($form) {
    contact_form_submit();
    return false; // Will stop the submission of the form
  }
});



function contact_form_submit(){

  $('#fm_submit').attr('disabled','disabled').html('Enviando..')
  var form_data = $(contact_form).serializeArray();

  $.ajax({
    url: server.rutaEmail,
    type : "POST",
    data: form_data,
    headers : { 'X-CSRF-Token': _token },
    dataType: 'json'
  }).done(function(data) {
    if( data.success ){
      $('#fm_success').removeClass('hidden');
      $(contact_form).hide();
    }
  });

}
