var err = false;

function onSubmit(event) {
    err = false;
    $('#msj').text('');
    $(".spinner").css("display", "block");
    var au = $('#ocupacion_id_aula').val();
    var di = $('#ocupacion_fecha').val();
    var hi = $('#ocupacion_hora_inicio').val();
    var hf = $('#ocupacion_hora_fin').val();
    var co = $('#ocupacion_comision').val();
    var d1 = new Date(di + ' ' + hi);
    var d2 = new Date(di + ' ' + hf);
    var m1 = d1.getMinutes();
    var m2 = d2.getMinutes();
    var id = 0;
    
    if ((d1.getTime() >= d2.getTime()) || ((m1!=0 && m1!=30) || (m2!=0 && m2!=30)) || (co <= 0)) {
        $('#msj').text('Verifique horario y/o comisión');
        err = true;
    };
    if (!err) {
        $.ajax({
            type: "POST",
            url: "/ocupado",
            data: { 'aula': au, 'dia': di, 'hi': hi, 'hf': hf, 'id': id },
            // async: false,
            success: function (res) {
                console.log(res);
                if (res == 'true') {
                    $('#msj').text('Se superpone el horario');
                    err = true;
                };
            } 
        });
    };
    if (err) {
        event.preventDefault();
        $(".spinner").css("display", "none");
    }
};

window.onSubmit = onSubmit;

function nueva(btn) {
    var dr = btn.getAttribute("data-ref");
    err = false;
    $.post(dr, function (result) {
        $('#newOcup').modal('show');
        $("#new-result").html(result);
        $("#formOcup").on('submit', onSubmit);
    });
};

function editar(btn) {
    var id = btn.getAttribute("data-edit-id");
    err = false;
    $.post("/ocupacion/edit_modal/" + id, { 'id': id }, function (result) {
        $('#editOcup').modal('show');
        $("#edit-result").html(result);
        $("#formOcup").on('submit', onSubmit);
    });
};

