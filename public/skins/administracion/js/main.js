document.addEventListener("DOMContentLoaded", function () {
  var forms = document.querySelectorAll("form");

  Array.prototype.slice.call(forms).forEach(function (form) {
    form.addEventListener(
      "submit",
      function (event) {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add("was-validated");
      },
      false,
    );
  });

  document.querySelectorAll("input, select, textarea").forEach(function (el) {
    el.addEventListener("invalid", function () {
      var v = el.validity;
      if (v.valueMissing) {
        el.setCustomValidity("Por favor, complete este campo.");
      } else if (v.rangeOverflow) {
        el.setCustomValidity("El valor debe ser menor o igual a " + el.max + ".");
      } else if (v.rangeUnderflow) {
        el.setCustomValidity("El valor debe ser mayor o igual a " + el.min + ".");
      } else if (v.stepMismatch) {
        el.setCustomValidity("Ingrese un valor válido.");
      } else if (v.tooLong) {
        el.setCustomValidity("El texto es demasiado largo (máximo " + el.maxLength + " caracteres).");
      } else if (v.tooShort) {
        el.setCustomValidity("El texto es demasiado corto (mínimo " + el.minLength + " caracteres).");
      } else if (v.typeMismatch) {
        el.setCustomValidity("Por favor, ingrese un valor en el formato correcto.");
      } else if (v.patternMismatch) {
        el.setCustomValidity("El valor no coincide con el formato requerido.");
      } else if (v.badInput) {
        el.setCustomValidity("Ingrese un valor válido.");
      } else {
        el.setCustomValidity("");
      }
    });
    el.addEventListener("input", function () {
      el.setCustomValidity("");
    });
    el.addEventListener("change", function () {
      el.setCustomValidity("");
    });
  });
});


function initRichTextEditors() {
  /* El super-build expone window.CKEDITOR.ClassicEditor; el build clásico expone window.ClassicEditor */
  var EditorClass = (window.CKEDITOR && window.CKEDITOR.ClassicEditor)
    ? window.CKEDITOR.ClassicEditor
    : (window.ClassicEditor || null);
  if (!EditorClass) {
    return;
  }

  var editors = document.querySelectorAll("textarea.tinyeditor");
  if (!editors.length) {
    return;
  }

  function UploadAdapter(loader, form) {
    this.loader = loader;
    this.form = form;
    this.xhr = null;
  }

  UploadAdapter.prototype.upload = function () {
    var _this = this;
    return this.loader.file.then(function (file) {
      return new Promise(function (resolve, reject) {
        _this._initRequest();
        _this._initListeners(resolve, reject, file);
        _this._sendRequest(file);
      });
    });
  };

  UploadAdapter.prototype.abort = function () {
    if (this.xhr) {
      this.xhr.abort();
    }
  };

  UploadAdapter.prototype._initRequest = function () {
    var xhr = (this.xhr = new XMLHttpRequest());
    xhr.open("POST", "/administracion/main/uploadeditorimage", true);
    xhr.responseType = "json";
  };

  UploadAdapter.prototype._initListeners = function (resolve, reject, file) {
    var xhr = this.xhr;
    var genericError = "No se pudo cargar la imagen: " + file.name;

    xhr.addEventListener("error", function () {
      reject(genericError);
    });

    xhr.addEventListener("abort", function () {
      reject();
    });

    xhr.addEventListener("load", function () {
      var response = xhr.response;
      if (!response || response.error) {
        reject(
          response && response.error && response.error.message
            ? response.error.message
            : genericError,
        );
        return;
      }

      resolve({
        default: response.url,
      });
    });
  };

  UploadAdapter.prototype._sendRequest = function (file) {
    var data = new FormData();
    var csrf = this.form ? this.form.querySelector('input[name="csrf"]') : null;
    var csrfSection = this.form
      ? this.form.querySelector('input[name="csrf_section"]')
      : null;

    data.append("upload", file);
    data.append("csrf", csrf ? csrf.value : "");
    data.append("csrf_section", csrfSection ? csrfSection.value : "");

    this.xhr.send(data);
  };

  function UploadAdapterPlugin(form) {
    return function (editor) {
      editor.plugins.get("FileRepository").createUploadAdapter = function (
        loader,
      ) {
        return new UploadAdapter(loader, form);
      };
    };
  }

  editors.forEach(function (textarea) {
    var form = textarea.closest("form");
    EditorClass.create(textarea, {
      extraPlugins: [UploadAdapterPlugin(form)],
      removePlugins: [
        "CKBox", "CKFinder", "EasyImage",
        "RealTimeCollaborativeComments", "RealTimeCollaborativeTrackChanges",
        "RealTimeCollaborativeRevisionHistory", "RealTimeCollaborativeEditing",
        "PresenceList", "Comments", "TrackChanges", "TrackChangesData",
        "RevisionHistory", "Pagination", "WProofreader", "MathType",
        "SlashCommand", "Template", "DocumentOutline", "FormatPainter",
        "TableOfContents", "AIAssistant",
        "MultiLevelList", "PasteFromOfficeEnhanced", "CaseChange",
        "ExportPdf", "ExportWord", "ImportWord",
      ],
      toolbar: {
        items: [
          "heading",
          "|",
          "bold",
          "italic",
          "link",
          "|",
          "fontColor",
          "fontBackgroundColor",
          "|",
          "bulletedList",
          "numberedList",
          "|",
          "insertTable",
          "blockQuote",
          "mediaEmbed",
          "imageUpload",
          "|",
          "undo",
          "redo",
          "|",
          "sourceEditing",
        ],
      },
      image: {
        toolbar: [
          "imageTextAlternative",
          "imageStyle:inline",
          "imageStyle:block",
          "imageStyle:side",
        ],
      },
      language: "es",
      htmlSupport: {
        allow: [
          { name: "p",          classes: true, attributes: true, styles: true },
          { name: "h1",         classes: true, attributes: true, styles: true },
          { name: "h2",         classes: true, attributes: true, styles: true },
          { name: "h3",         classes: true, attributes: true, styles: true },
          { name: "h4",         classes: true, attributes: true, styles: true },
          { name: "h5",         classes: true, attributes: true, styles: true },
          { name: "h6",         classes: true, attributes: true, styles: true },
          { name: "div",        classes: true, attributes: true, styles: true },
          { name: "span",       classes: true, attributes: true, styles: true },
          { name: "a",          classes: true, attributes: true, styles: true },
          { name: "ul",         classes: true, attributes: true, styles: true },
          { name: "ol",         classes: true, attributes: true, styles: true },
          { name: "li",         classes: true, attributes: true, styles: true },
          { name: "table",      classes: true, attributes: true, styles: true },
          { name: "thead",      classes: true, attributes: true, styles: true },
          { name: "tbody",      classes: true, attributes: true, styles: true },
          { name: "tr",         classes: true, attributes: true, styles: true },
          { name: "td",         classes: true, attributes: true, styles: true },
          { name: "th",         classes: true, attributes: true, styles: true },
          { name: "figure",     classes: true, attributes: true, styles: true },
          { name: "figcaption", classes: true, attributes: true, styles: true },
          { name: "img",        classes: true, attributes: true, styles: true },
          { name: "blockquote", classes: true, attributes: true, styles: true },
          { name: "pre",        classes: true, attributes: true, styles: true },
          { name: "code",       classes: true, attributes: true, styles: true },
          { name: "strong",     classes: true, attributes: true, styles: true },
          { name: "em",         classes: true, attributes: true, styles: true },
          { name: "section",    classes: true, attributes: true, styles: true },
          { name: "article",    classes: true, attributes: true, styles: true },
          { name: "aside",      classes: true, attributes: true, styles: true },
          { name: "header",     classes: true, attributes: true, styles: true },
          { name: "footer",     classes: true, attributes: true, styles: true },
        ],
      },
    }).then(function (editor) {
      editor.model.document.on("change:data", function () {
        textarea.value = editor.getData();
      });
    }).catch(function (error) {
      console.error(error);
    });
  });
}


$(document).ready(function () {
  $(".menu-toggler").on("click", function () {
    $("#panel-botones").toggleClass("open");
    $(".nav-brand").toggle(300);
  });
});

$(document).ready(function () {
  $(".deletePolDoc").on("click", function () {
    let id = $(this).attr("data-id");
    $.ajax({
      url: "/administracion/politicas/deletedocument",
      method: "POST",
      data: {
        id: id,
      },
      dataType: "json",
      success: function (data) {
        if (data == "ok") {
          $("#doc_" + id).remove();
        }
      },
    });
  });
  $(function () {
    $("#fecha_tipo_bloqueo").on("change", function () {
      let _val = $(this).val();

      if (_val == "1") {
        $("#fecha_ciudad").show(300);
        $("#fecha_empleado").val("");
        $("#fecha_empleado").hide(300);
      } else if (_val == "2") {
        $("#fecha_empleado").show(300);
        $("#fecha_ciudad").val("");
        $("#fecha_ciudad").hide(300);
      } else if (_val == "3") {
        $("#fecha_empleado").val("");
        $("#fecha_empleado").hide(300);
        $("#fecha_ciudad").val("");
        $("#fecha_ciudad").hide(300);
      }
    });
  });

  $(".file-image").fileinput({
    maxFileSize: 10000,
    previewFileType: "image",
    allowedFileExtensions: ["jpg", "jpeg", "gif", "png", "ico"],
    browseClass: "btn  btn-verde",
    showUpload: false,
    showRemove: false,
    browseIcon: '<i class="fas fa-image"></i> ',
    browseLabel: "Imagen",
    language: "es",
    dropZoneEnabled: false,
  });

  $(".file-document").fileinput({
    maxFileSize: 2048,
    previewFileType: "image",
    browseLabel: "Archivo",
    browseClass: "btn  btn-cafe",
    allowedFileExtensions: ["pdf", "xlsx", "xls", "doc", "docx", "ico"],
    showUpload: false,
    showRemove: false,
    browseIcon: '<i class="fas fa-folder-open"></i> ',
    language: "es",
    dropZoneEnabled: false,
  });

  $(".file-robot").fileinput({
    maxFileSize: 2048,
    previewFileType: "image",
    allowedFileExtensions: ["txt", ".txt"],
    browseClass: "btn btn-success btn-file-robot",
    showUpload: false,
    showRemove: false,
    browseLabel: "Robot",
    browseIcon: '<i class="fas fa-robot"></i> ',
    language: "es",
    dropZoneEnabled: false,
    showPreview: false,
  });

  $(".file-sitemap").fileinput({
    maxFileSize: 2048,
    previewFileType: "image",
    allowedFileExtensions: ["xml", ".xml"],
    browseClass: "btn btn-success btn-file-sitemap",
    showUpload: false,
    showRemove: false,
    browseLabel: "SiteMap",
    browseIcon: '<i class="fas fa-sitemap"></i> ',
    language: "es",
    dropZoneEnabled: false,
    showPreview: false,
  });
  $('[ data-bs-toggle="tooltip"]').tooltip();
  $(".up_table,.down_table").click(function () {
    var row = $(this).parents("tr:first");
    var value = row.find("input").val();
    var content1 = row.find("input").attr("id");
    var content2 = 0;
    if ($(this).is(".up_table")) {
      if (row.prev().find("input").val() > 0) {
        row.find("input").val(row.prev().find("input").val());
        row.prev().find("input").val(value);
        content2 = row.prev().find("input").attr("id");
        row.insertBefore(row.prev());
      }
    } else {
      if (row.next().find("input").val() > 0) {
        row.find("input").val(row.next().find("input").val());
        row.next().find("input").val(value);
        content2 = row.next().find("input").attr("id");
        row.insertAfter(row.next());
      }
    }
    var route = $("#order-route").val();
    var csrf = $("#csrf").val();
    if (route != "") {
      $.post(route, {
        csrf: csrf,
        id1: content1,
        id2: content2,
      });
    }
  });

  if ($('.select2-eventos').length) {
    $('.select2-eventos').select2({
      placeholder: '— Todos los eventos —',
      allowClear: true,
      width: '100%'
    });
  }

  if ($('.select2-tipo-boleta').length) {
    $('.select2-tipo-boleta').select2({
      placeholder: 'Seleccione...',
      allowClear: true,
      width: '100%'
    });
  }

  $(".selectpagination").change(function () {
    var route = $("#page-route").val();
    var pages = $(this).val();
    $.post(
      route,
      {
        pages: pages,
      },
      function () {
        location.reload();
      },
    );
  });

  $(".switch-form").bootstrapToggle({
    onlabel: "Si",
    offlabel: "No",
    onstyle: "success",
    offstyle: "danger",
  });

  function applyContenidoTipoVisibility(tipoValue) {
    var tipo = parseInt(tipoValue, 10);

    if (!isNaN(tipo) && tipo > 0) {
      $(".no-start").show();
    }

    if (tipo === 1) {
      // Banner
      $(".no-seccion").hide();
      $(".no-banner").hide();
      $(".no-contenido").hide();
      $(".si-banner").show();
    } else if (tipo === 2 || tipo === 4) {
      // Contenedor / Banner interno
      $(".no-seccion").hide();
      $(".no-banner").hide();
      $(".no-contenido").hide();
      $(".si-seccion").show();
    } else if (tipo === 3) {
      // Contenido simple
      $(".no-seccion").hide();
      $(".no-banner").hide();
      $(".no-contenido").hide();
      $(".si-contenido").show();
    } else if (tipo === 5) {
      // Contenido de contenedor
      $(".no-acordion").hide();
      $(".no-carrousel").hide();
      $(".no-contenido2").hide();
      $(".si-contenido2").show();
    } else if (tipo === 6) {
      // Carrusel de contenedor
      $(".no-contenido2").hide();
      $(".no-acordion").show();
      $(".no-carrousel").hide();
      $(".si-carrousel").show();
    } else if (tipo === 7) {
      // Acordeon
      $(".no-acordion").hide();
      $(".no-contenido2").hide();
      $(".no-carrousel").hide();
      $(".si-acordion").show();
    }
  }

  $("#contenido_tipo").on("change", function () {
    var value = $(this).val();
    applyContenidoTipoVisibility(value);
    aparecercolumna();
  });

  applyContenidoTipoVisibility($("#contenido_tipo").val());
  aparecercolumna();
  $(".colorpicker")
    .colorpicker({
      onChange: function (e) {
        console.log("entro");
      },
    })
    .on("colorpickerChange colorpickerCreate", function (e) {
      console.log("entro");
      // console.log( e.colorpicker.picker.parents('.input-group'));
      //e.colorpicker.picker.parents('.input-group').find('input').css('background-color', e.value);
    })
    .on("create", function (e) {
      var val = $(this).val();
      $(this).css({
        backgroundColor: $(this).val(),
      });
    })
    .on("change", function (e) {
      var val = $(this).val();
      $(this).css({
        backgroundColor: $(this).val(),
      });
    });

  initRichTextEditors();
});

function aparecercolumna() {
  var id_columna = document.getElementById("contenido_tipo")?.value;
  if (id_columna == "5" || id_columna == "6") {
    $(".no-colum").attr("style", "display:block!important");
  } else {
    $(".no-colum").attr("style", "display:none!important");
  }
}
aparecercolumna();

function eliminarImagen(campo, ruta) {
  var csrf = $("#csrf").val();
  var csrf_section = $("#csrf_section").val();
  var id = $("#id").val();
  if (confirm("¿Esta seguro de borrar esta imagen?") == true) {
    $.post(
      ruta,
      {
        id: id,
        csrf: csrf,
        csrf_section: csrf_section,
        campo: campo,
      },
      function (data) {
        if (parseInt(data.elimino) == 1) {
          $("#imagen_" + campo).hide();
        }
      },
    );
  }
  return false;
}

function eliminararchivo(campo, ruta) {
  var csrf = $("#csrf").val();
  var csrf_section = $("#csrf_section").val();
  var id = $("#id").val();
  if (confirm("¿Esta seguro de borrar este Archivo?") == true) {
    $.post(
      ruta,
      {
        id: id,
        csrf: csrf,
        csrf_section: csrf_section,
        campo: campo,
      },
      function (data) {
        if (parseInt(data.elimino) == 1) {
          $("#archivo_" + campo).hide();
        }
      },
    );
  }
  return false;
}
