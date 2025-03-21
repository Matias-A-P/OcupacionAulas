// on ready
$(document).ready(function () {
    // filtro areas
    var fArea = $('#area');
    $.post(fArea.attr('data-json'), function (areas) {
        $.each(areas, function (key, ar) {
            fArea.append('<option value="' + ar.id + '">' + ar.area + '</option>');
        });
        fArea.val(fArea.attr('data-area')).change();
    });
    // filtro palabra 
    $("#inputFiltro").on("keyup click", function () {
        var value = $(this).val().toLowerCase();
        $("#tabody").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    $("#inputFiltroHoras").on("keyup click", function () {
        var value = $(this).val().toLowerCase();
        $("#rowOcups").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });    
    // cambio de dia
    $('#dia').on('change', function () {
        $(this).closest('form').submit();
    });
});


var err = false;
// validar Ocupacion antes de guardar
function onSubmit(event) {
    err = false;
    $('#msj').text('');
    $(".spinner").css("display", "block");
    var id = $('#idocup').val();
    var au = $('#ocupacion_id_aula').val();
    var ar = $('#ocupacion_id_area').val();
    var ac = $('#ocupacion_id_catedra').val();
    var di = $('#ocupacion_fecha').val();
    var hi = $('#ocupacion_hora_inicio').val();
    var hf = $('#ocupacion_hora_fin').val();
    var co = $('#ocupacion_comision').val();
    var us = $('#ocupacion_user').val();
    var d1 = new Date(di + ' ' + hi);
    var d2 = new Date(di + ' ' + hf);
    var m1 = d1.getMinutes();
    var m2 = d2.getMinutes();

    if (!(ar > 0) || !(ac > 0)) {
        $('#msj').text('Ingrese Area y Actividad');
        err = true;
    }
    if ((d1.getTime() >= d2.getTime()) || ((m1 != 0 && m1 != 30) || (m2 != 0 && m2 != 30)) || (co < 0)) {
        $('#msj').text('Verifique horario y/o comisión');
        err = true;
    };
    if (!err) {
        $.ajax({
            type: "POST",
            url: "/ocupado",
            data: { 'aula': au, 'dia': di, 'hi': hi, 'hf': hf, 'id': id, 'ac': ac, 'co': co, 'us': us },
            async: false,
            success: function (res) {
                if (res == 'true') {
                    err = true;
                    $('#msj').text('Se superpone el horario');
                };
            }
        });
    };
    if (err) {
        event.preventDefault();
        $(".spinner").css("display", "none");
    }
};

// filtrar Catedras por Area en modal
function onChangeArea() {
    var selArea = $('#ocupacion_id_area');
    var selCatedra = $('#ocupacion_id_catedra');
    $('#ocupacion_id_area').change(function () {
        selCatedra.html('');
        if (selArea.val() > 0) {
            $.ajax({
                url: '/areas/' + selArea.val() + '/catedras',
                type: 'POST',
                dataType: 'JSON'
            }).done(function (catedras) {
                $.each(catedras, function (key, catedra) {
                    selCatedra.append('<option value="' + catedra.id + '">' + catedra.nombre + '</option>');
                });
            });
        }
    });
}

// nueva Ocupacion
function nueva(btn) {
    var dr = btn.getAttribute("data-ref");
    var aula = btn.getAttribute("data-id-aula");
    var dia = btn.getAttribute("data-dia");
    var hora = btn.getAttribute("data-hora");
    var hora_fin = btn.getAttribute("data-hora-fin");
    if (hora_fin==null) {
        hora_fin = hora;
    }
   // var vista = btn.getAttribute("data-vista");
    var area = 0;
    var activ = 0;
    if (btn.hasAttribute("data-area")) {
        area = btn.getAttribute("data-area");
    };
    if (btn.hasAttribute("data-activ")) {
        activ = btn.getAttribute("data-activ");
    };
    err = false;
    $('#ocupacion_id_area').change(function () { });  // 'vista': vista,
    $.post(dr, { 'aula': aula, 'dia': dia, 'hora': hora, 'hora_fin': hora_fin, 'area': area, 'activ': activ }, function (result) {
        $('#newOcup').modal('show');
        $("#new-result").html(result);
        $("#formOcup").on('submit', onSubmit);
        $('#ocupacion_id_area').val(area).change();
        $('#ocupacion_id_catedra').val(activ).change();
    }).always(function () {
        $('#ocupacion_id_area').on('change', onChangeArea());
    });

};

// editar Ocupacion
function editar(btn) {
    var id = btn.getAttribute("data-edit-id");
    //var vista = btn.getAttribute("data-vista");
    err = false;
    $.post("/ocupacion/edit_modal/" + id, function (result) {
        $('#editOcup').modal('show');
        $("#edit-result").html(result);
        $("#formOcup").on('submit', onSubmit);
    }).always(function () {
        $('#ocupacion_id_area').on('change', onChangeArea());
    });
};
