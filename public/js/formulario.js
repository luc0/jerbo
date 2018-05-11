
//---------------------------------------------
// Select Categorias -> Actualiza Subcategorias.
//---------------------------------------------

$('#id_gestor').on('change',function(){

  var url = $(this).data('apiUrl')
  var self = $(this)

  if( url && self ){
    $.ajax({
        url: url + '/' + self.val(),
        type: 'get',
        cache: false,
        dataType: 'json',
        success: function(data) {
            $('#id_subcategoria').html('')
            data.subcategorias.forEach(function(item){
              //console.log(item['nombre'])
              $('#id_subcategoria').append('<option value="' + item['id'] + '">' + item['nombre'] + '</option>')
            })
        },
        error: function(xhr, textStatus, thrownError) {
            console.log('Error al devolver subcategorias!');
        }
    });
  }

});
