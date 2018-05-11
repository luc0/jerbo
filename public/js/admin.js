var _token = $('input[name="_token"]').val();
var multiupload = {};
var MAX_FILE_SIZE = 128;

//tinymce.init({ selector:'textarea' });
tinymce.init({
    selector: "textarea",
    plugins: [
      'advlist autolink lists link preview anchor',
      'searchreplace fullscreen',
      'paste media'
    ],
    media_strict: false,
    toolbar: 'undo redo | insert | styleselect | bold italic underline superscript subscript | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | media',
    extended_valid_elements : "iframe[src|frameborder|style|scrolling|class|width|height|name|align]",
    setup: function (editor) {
        editor.on('change', function () {
            editor.save();
        });
    },
})

//console.log('server',server)

modificacion_init();
if( $(".dropzone").length ){
  dropzone_config();
}

//---------------------------------------------
// Eliminar registro
//---------------------------------------------
eliminacion_registro();


//---------------------------------------------
// Input File
//---------------------------------------------
if( (typeof server !== "undefined") && (server !== null) && server ){
  if((typeof server.dropzone !== "undefined") && (server.dropzone !== null)){
    fn_modificacion();
  }
}



check_images_existence();


function dropzone_config(){

    /*
    http://www.dropzonejs.com/#configuration-options
    */

    $.each(server.dropzone, function( campo ){
      //$.each(server.dropzone[dz].campo, function( campo ){
        multiupload[ campo ] = {
          'agregados' : [],
          'borrados' : [],
          'existentes' : [],
          'elemento' : ''
        }
        var dropzone_options = {
          url: server.rutaDropzone,
          paramName: "file", // The name that will be used to transfer the file
          maxFilesize: MAX_FILE_SIZE, // MB
          addRemoveLinks: true,
          dictDefaultMessage : '<em class="glyphicon glyphicon-download-alt"></em><p>Arrastrá los archivos adentro o hace click para subirlos.</p>',
          headers : { 'X-CSRF-Token': _token },
          dictFallbackMessage : 'El Navegador no soporta el drag & drop',
          dictInvalidFileType : 'La extensión del archivo es inválida',
          dictFileTooBig : 'El archivo pesa {{filesize}}MB. Máximo permitido: {{maxFilesize}}MB.',
          dictResponseError : 'No se pudo realizar la operación, intente mas tarde.',
          dictCancelUpload : 'Cancelar',
          dictCancelUploadConfirmation : '¿Estás seguro?',
          dictRemoveFile : 'Eliminar',
          dictMaxFilesExceeded : 'No se pueden subir tantos archivos al mismo tiempo.',
          accept: function(file, done) {
            console.log('>>subiendo img...') 
            var indiceActual = _.last( multiupload[ campo ]['agregados'] );
            var demasAgregados = _.without( multiupload[ campo ]['agregados'] ,indiceActual);
            var existe = _.findIndex(demasAgregados, { name: file.name, lastModified: file.lastModified });
            if (existe >= 0) {
              done("El archivo ya existe");
            }
            else { done(); }
          },
          success: function(file,data){
            file.new_name = data.new_name;
          },
          init: function() {
            
            this.aver = true;
            this.on("removedfile", function(file) {
              if( file.existente ){
                multiupload[ campo ]['borrados'].push(file.name)
                multiupload[ campo ]['existentes'] = _.without(multiupload[ campo ]['existentes'],file.name);
              }else{
                remove_file( file.name, null, campo );
              }

            });

            this.on("addedfile", function(file) {
              if(file.size < MAX_FILE_SIZE * 1048576){
                console.log('>>agregada...',file.size,MAX_FILE_SIZE * 1048576) 
                var agregado = _.findIndex(multiupload[ campo ]['agregados'], { name: file.name, lastModified: file.lastModified });
                var existente = _.indexOf(multiupload[ campo ]['existentes'], file.name );
                if( agregado < 0 && existente < 0 ){
                  multiupload[ campo ]['agregados'].push(file);
                }
              }
              console.log('files',multiupload[ campo ])
            });
          }
        };
        multiupload[ campo ]['elemento'] = new Dropzone("#" + campo, dropzone_options);

      //});
    });

}

ajax_submit();



function ajax_submit(){
  $('#form_abm').on('submit',function(e){
    e.preventDefault();


    var data = $(this).serializeArray(); // convert form to array

    $.each(multiupload,function( dropzone ){
      $.each(multiupload[dropzone].borrados, function( $i,filename ){
        remove_file( filename , server.tabla, dropzone );
      });
    })

    // No lo necesito mas. Esto permite que se pueda hacer json.stringify
    $.each(multiupload,function( dropzone ){
      delete multiupload[ dropzone ]['elemento'];
    })

    data.push({ name:'multiupload', value: JSON.stringify(multiupload) })

    $.ajax({
      url: server.rutaSaveRegistro,
      type : "POST",
      data: $.param(data),
      headers : { 'X-CSRF-Token': _token },
      dataType: 'json'
    }).done(function(data) {
      if( data.success ){
        window.location = data.redirect;
      }
    });

  });
}

function modificacion_init(){
  if( (typeof server !== "undefined") && (server !== null) && server ){
    if((typeof server.modificacion !== "undefined") && (server.modificacion !== null) && server.modificacion ){
      // por cada campo de tipo dropzone ( imagenes, audios )
      $.each(server.dropzone, function( campo ){
        multiupload[ campo ] = {
          'agregados' : [],
          'borrados' : [],
          'existentes' : [],
          'elemento' : ''
        }
        // devolver data de ese campo (de cada file) de la DB a un objeto
        var array_src = server.registro[campo].split(',');
        array_src.forEach(function( file_name ){
          if( file_name ){
            var file = { name: file_name, size: 0, existente : server.tabla };
            multiupload[campo].existentes.push(file.name);
            console.log('existente',file_name)
          }
        })
      })

    //console.log('multiupload',_.clone(multiupload))
    console.log('multiupload',multiupload)
    }
  }
}

function fn_modificacion(){
  if((typeof server.modificacion !== "undefined") && (server.modificacion !== null) && server.modificacion ){

    if((typeof server.registro !== "undefined") && (server.registro !== null) && server.registro ){

      $.each(server.dropzone, function( campo ){
        var array_src = server.registro[ campo ].split(',');
        array_src = _.compact(array_src);

        if(array_src.length > 0){
          array_src.forEach(function( src ){
            var file = { name: src, size: 0, existente : server.tabla };
            var dropzoneElement = multiupload[ campo ]['elemento'];
            var path = server.dropzone[ campo ].path + src;
            dropzoneElement.emit("addedfile", file);
            dropzoneElement.emit("complete", file);
            $.get( path ).done(function() {
              dropzoneElement.emit("thumbnail", file, path );
            }).fail(function() {
              dropzoneElement.emit("thumbnail", file, '/img/noimg.jpg' );
            })

          });
        }
      });

    }

    // Data
    var name = server.dropzone.value;
    var size = '128';
    if( name ){
      var type = name.split('.');
      if( type.length > 0 ){
        type = type[ type.length - 1 ];
      }
    }

    var mimetype;

    //El html del preview. Default: icono files.
    var previewHTML = '<i class="icon-jfi-file-o jfi-file-type-application jfi-file-ext-' + type + '"></i>';

    // Setear mimetype y html del preview para Imagenes.
    switch( type ){
      case 'pdf':
        mimetype = "application/pdf"
        break;
      case 'doc':
        mimetype = "application/msword"
        break;
      case 'ppt':
        mimetype = "application/mspowerpoint"
        break;
      case 'jpg':
      case 'jpeg':
        mimetype = "image/jpeg"
        previewHTML = '<img src="'+ server.dropzone.path +'" width="100" height="75" />';
        break;
      case 'png':
        mimetype = "image/png"
        previewHTML = '<img src="'+ server.dropzone.path +'" width="100" height="75" />';
        break;
      case 'gif':
        mimetype = "image/gif"
        previewHTML = '<img src="'+ server.dropzone.path +'" width="100" height="75" />';
        break;
      case 'wav':
        mimetype = "audio/wav"
        break;   

    }

    var options = [];

    // Html
    options.templates = {
      itemAppend: '<div class="jFiler-items jFiler-row"><ul class="jFiler-items-list jFiler-items-default"><li class="jFiler-item" data-jfiler-index="0" style=""><div class="jFiler-item-container"><div class="jFiler-item-inner"><div class="jFiler-item-icon pull-left">'+previewHTML+'</div><div class="jFiler-item-info pull-left"><div class="jFiler-item-title">'+name+'</div><div class="jFiler-item-others"><span>type: '+type+'</span><span class="jFiler-item-status"></span></div></div></div></div></li></ul></div>'
    }
    // Con trash: '<div class="jFiler-items jFiler-row"><ul class="jFiler-items-list jFiler-items-default"><li class="jFiler-item" data-jfiler-index="0" style=""><div class="jFiler-item-container"><div class="jFiler-item-inner"><div class="jFiler-item-icon pull-left"><img src="'+ server.dropzone.path +'" width="100" height="75" /></div><div class="jFiler-item-info pull-left"><div class="jFiler-item-title">1_audio.png</div><div class="jFiler-item-others"><span>size: 5.45 KB</span><span>type: png</span><span class="jFiler-item-status"></span></div><div class="jFiler-item-assets"><ul class="list-inline"><li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li></ul></div></div></div></div></li></ul></div>'
    options.files = [
      {
          name: server.dropzone.value,
          size: 0,
          type: mimetype,
          file: server.dropzone.value
      }
    ]

  }
}

function eliminacion_registro(){
  $('.btn_eliminar').on('click',function(){

    var modulo = $(this).data('modulo')
    var gestorId = $(this).data('gestorId')
    var gestorContenido = $(this).data('gestorContenido')
    var id = $(this).data('id')

    $('#modal_btn_eliminar').attr('href', server.api + '/' + modulo + '/' + gestorId + '/' + gestorContenido + '/' + id + '/eliminar' )

  })

  $('.btn_eliminar_subcategoria').on('click',function(){

    var modulo = $(this).data('modulo')
    var id = $(this).data('id')

    $('#modal_btn_eliminar').attr('href', server.api + '/' + modulo + '/subcategoria/' + id + '/eliminar' )

  })
}

function remove_file( filename, existente, dropzone ){

  $.ajax({
    url: server.rutaDelete,
    type : "POST",
    data: { name : filename, existente: existente, dropzone: dropzone },
    headers : { 'X-CSRF-Token': _token },
    dataType: 'json'
  }).done(function(data) {
    if( data.success ){
      var i = _.findIndex( multiupload[ dropzone ]['agregados'], { name: filename });
      delete multiupload[ dropzone ]['agregados'][ i ];
      multiupload[ dropzone ]['agregados'] = _.compact( multiupload[ dropzone ]['agregados'] );
    }
  });
}


function check_images_existence(){
  $('img').each(function( i,img ){

    $(img).error(function(){
      var split_src = img.src.split('.');
      var extension = split_src[ split_src.length -1 ];
      switch(extension){
        case 'pdf':
        case 'doc':
        case 'wav':
        case 'mp3':
          $(this).attr('src', '/img/extension/'+ extension +'.jpg');
          break;
        default:
          console.log('NO ENCONTRADO:',extension)
          $(this).attr('src', '/img/noimg.jpg');
          break;
      }
    });
    /*on_image_broken(img.src, function(){
      img.src = '/img/noimg.jpg';
    }, function(){
    var split_src = img.src.split('.');
    var extension = split_src[ split_src.length ];
    console.log('extension',extension);
    });*/
  });
  /*
  function on_image_broken(imageSrc, bad, ok) {
    console.log('broken?',imageSrc)
      var img = new Image();
      img.onerror = bad;
      img.onload  = ok;
      img.src = imageSrc;
  }*/
}
