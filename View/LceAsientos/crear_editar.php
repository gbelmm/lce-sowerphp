<h1><?=$accion?> asiento contable</h1>
<script type="text/javascript">
var ayuda = <?=json_encode($ayuda)?>;
$().ready(function () {
    asiento_procesar();
    $('select[name="cuenta[]"]').each(function (i, e) {
        cuenta_tooltip(e);
    });
});
</script>
<?php
$f = new \sowerphp\general\View_Helper_Form();
echo $f->begin(['onsubmit'=>'asiento_check()']);
$f->setColsLabel(3);
echo '<div class="row">',"\n";
echo '<div class="col-md-6">',"\n";
echo $f->input([
    'name' => 'codigo',
    'label' => 'Código',
    'value' => isset($Obj) ? $Obj->periodo.'-'.$Obj->asiento : null,
    'check' => 'notempty integer',
    'attr' => 'disabled="disabled"',
]);
echo $f->input([
    'type' => 'date',
    'name' => 'fecha',
    'label' => 'Fecha',
    'value' => isset($Obj) ? $Obj->fecha : date('Y-m-d'),
    'check' => 'notempty date',
]);
echo '</div>',"\n";
echo '<div class="col-md-6">',"\n";
echo $f->input([
    'name' => 'glosa',
    'label' => 'Glosa',
    'placeholder' => 'Glosa u objeto con datos extras',
    'value' => isset($Obj) ? $Obj->glosa : null,
    'check' => 'notempty',
]);
echo $f->input([
    'type' => 'select',
    'name' => 'anulado',
    'label' => 'Anulado',
    'options' => ['No', 'Si'],
    'value' => isset($Obj) ? $Obj->anulado : 0,
]);
echo '</div>',"\n";
echo '</div>',"\n";
echo $f->setStyle(false);
echo $f->input([
    'type' => 'js',
    'id' => 'detalle',
    'label' => 'Detalle',
    'titles' => ['Cuenta', 'Debe', 'Haber', 'Concepto'],
    'inputs' => [
        ['type'=>'select', 'name'=>'cuenta', 'options'=>[''=>'Seleccionar cuenta']+$cuentas, 'check'=>'notempty', 'attr'=>'data-toggle="tooltip" data-placement="bottom" onchange="cuenta_tooltip(this)"'],
        ['name'=>'debe', 'check'=>'integer', 'placeholder'=>'Debe', 'attr'=>'onblur="asiento_procesar()" data-toggle="tooltip" data-placement="bottom"'],
        ['name'=>'haber', 'check'=>'integer', 'placeholder'=>'Haber', 'attr'=>'onblur="asiento_procesar()" data-toggle="tooltip" data-placement="bottom"'],
        ['name'=>'concepto', 'placeholder'=>'Concepto u objeto con datos extras'],
    ],
    'values' => $detalle,
]);
echo $f->setStyle('horizontal');
echo '<div class="row">',"\n";
echo '<div class="col-md-6">',"\n";
echo $f->input(array(
    'name' => 'total_debe',
    'label' => 'Debe',
    'attr' => 'readonly="readonly"',
));
echo '</div>',"\n";
echo '<div class="col-md-6">',"\n";
echo $f->input(array(
    'name' => 'total_haber',
    'label' => 'Haber',
    'attr' => 'readonly="readonly"',
));
echo '</div>',"\n";
echo '</div>',"\n";
echo $f->setStyle(false);
echo '<div class="text-center">',"\n";
echo $f->end('Guardar asiento contable');
echo '</div>',"\n";
?>
<div style="float:left;color:red">* campo es obligatorio</div>
<div style="float:right;margin-bottom:1em;font-size:0.8em">
    <a href="<?=$_base.$listarUrl?>">Volver al listado de asientos contables</a>
</div>
