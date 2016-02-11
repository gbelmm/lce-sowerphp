function asiento_procesar() {
    var total_debe = 0;
    var total_haber = 0;
    $('input[name="debe[]"]').each(function (i, e) {
        // resetear estados para los campos de texto
        $($('input[name="debe[]"]').get(i)).css('background', 'none');
        $($('input[name="debe[]"]').get(i)).removeAttr('readonly');
        $($('input[name="haber[]"]').get(i)).css('background', 'none');
        $($('input[name="haber[]"]').get(i)).removeAttr('readonly');
        // sumar debe y haber y bloquer el "otro" campo
        if (!__.empty($('input[name="debe[]"]').get(i).value)) {
            total_debe += parseInt($('input[name="debe[]"]').get(i).value);
            $($('input[name="haber[]"]').get(i)).css('background', '#ebebe4');
            $($('input[name="haber[]"]').get(i)).attr('readonly', 'readonly');
        } else if (!__.empty($('input[name="haber[]"]').get(i).value)) {
            total_haber += parseInt($('input[name="haber[]"]').get(i).value);
            $($('input[name="debe[]"]').get(i)).css('background', '#ebebe4');
            $($('input[name="debe[]"]').get(i)).attr('readonly', 'readonly');
        }
    });
    $('input[name="total_debe"]').val(total_debe);
    $('input[name="total_haber"]').val(total_haber);
}

function asiento_check() {
    var status = true;
    if (!Form.check())
        return false;
    $('input[name="debe[]"]').each(function (i, e) {
        if (__.empty($('input[name="debe[]"]').get(i).value) && __.empty($('input[name="haber[]"]').get(i).value)) {
            alert ('Debe especificar debe o haber en cada movimiento del asiento');
            status = false;
            return status;
        }
    });
    if (!status)
        return false;
    asiento_procesar();
    if (parseInt($('input[name="total_debe"]').val()) != parseInt($('input[name="total_haber"]').val())) {
        alert ('Asiento no está cuadrado');
        return false;
    }
    if (parseInt($('input[name="total_debe"]').val())<1) {
        alert ('Asiento debe tener un valor positivo');
        return false;
    }
    return Form.checkSend ('Confirmar asiento por $'+__.num($('input[name="total_debe"]').val()));
}

function cuenta_tooltip(cuenta) {
    var debe, haber;
    debe = cuenta.parentNode.parentNode.parentNode.childNodes[1].childNodes[0].childNodes[0];
    haber = cuenta.parentNode.parentNode.parentNode.childNodes[2].childNodes[0].childNodes[0];
    $(cuenta).attr('data-original-title', ayuda[cuenta.value]!==undefined ? ayuda[cuenta.value].descripcion : '');
    $(debe).attr('data-original-title', ayuda[cuenta.value]!==undefined ? ayuda[cuenta.value].cargos : '');
    $(haber).attr('data-original-title', ayuda[cuenta.value]!==undefined ? ayuda[cuenta.value].abonos : '');
    $(cuenta).tooltip();
    $(debe).tooltip();
    $(haber).tooltip();
}
