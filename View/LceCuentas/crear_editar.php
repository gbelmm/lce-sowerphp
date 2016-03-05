<h1><?=$accion?> cuenta contable</h1>
<?php
$f = new \sowerphp\general\View_Helper_Form();
echo $f->begin(['onsubmit'=>'Form.check()']);
echo $f->input([
    'name' => 'codigo',
    'label' => 'Código',
    'value' => isset($Obj) ? $Obj->codigo : '',
    'check' => 'notempty',
    'help' => 'Código de la cuenta contable',
    'attr' => 'maxlength="20"'.(isset($Obj)?' readonly="readonly"':''),
]);
echo $f->input([
    'name' => 'cuenta',
    'label' => 'Cuenta',
    'value' => isset($Obj) ? $Obj->cuenta : '',
    'check' => 'notempty',
    'help' => 'Nombre de la cuenta contable',
    'attr' => 'maxlength="120"',
]);
echo $f->input([
    'type' => 'select',
    'name' => 'principal',
    'label' => 'Principal',
    'options' => $clasificaciones,
    'value' => isset($Obj) ? $Obj->clasificacion[0] : '',
    'check' => 'notempty',
    'help' => 'Clasificación principal de la cuenta',
    'attr' => 'onchange="clasificacion_procesar()"'
]);
echo $f->input([
    'type' => 'select',
    'name' => 'clasificacion',
    'label' => 'Clasificación',
    'options' => [''=>'Seleccione una clasificación'],
    'value' => isset($Obj) ? $Obj->clasificacion : '',
    'check' => 'notempty',
    'help' => 'Clasificación de la cuenta',
]);
echo $f->input([
    'type' => 'select',
    'name' => 'oficial',
    'label' => 'Cuenta SII',
    'options' => [''=>'No tiene cuenta oficial asociada'],
    'value' => isset($Obj) ? $Obj->oficial : '',
    'help' => 'Cuenta oficial del SII que se mapea a esta cuenta',
]);
echo $f->input([
    'type' => 'textarea',
    'name' => 'descripcion',
    'label' => 'Descripción',
    'value' => isset($Obj) ? $Obj->descripcion : '',
    'help' => 'Descripción general de la cuenta contable',
]);
echo $f->input([
    'type' => 'textarea',
    'name' => 'cargos',
    'label' => 'Cargos',
    'value' => isset($Obj) ? $Obj->cargos : '',
    'help' => 'Indicar cuando la cuenta se debe cargar',
]);
echo $f->input([
    'type' => 'textarea',
    'name' => 'abonos',
    'label' => 'Abonos',
    'value' => isset($Obj) ? $Obj->abonos : '',
    'help' => 'Indicar cuando al cuenta se debe abonar',
]);
echo $f->input([
    'type' => 'textarea',
    'name' => 'saldo_deudor',
    'label' => 'Saldo deudor',
    'value' => isset($Obj) ? $Obj->saldo_deudor : '',
    'help' => 'Indicar que significa que la cuenta tenga saldo deudor',
]);
echo $f->input([
    'type' => 'textarea',
    'name' => 'saldo_acreedor',
    'label' => 'Saldo acreedor',
    'value' => isset($Obj) ? $Obj->saldo_acreedor : '',
    'help' => 'Indicar que significa que la cuenta tenga saldo acreedor',
]);
echo $f->input([
    'type' => 'select',
    'name' => 'activa',
    'label' => '¿Activa?',
    'options' => ['No', 'Si'],
    'value' => isset($Obj) ? $Obj->activa : 1,
    'check' => 'notempty',
    'help' => 'Indica si la cuenta está o no disponible para su uso',
]);
echo $f->input([
    'name' => 'codigo_otro',
    'label' => 'Otro código',
    'value' => isset($Obj) ? $Obj->codigo_otro : '',
    'help' => 'Otro código de la cuenta contable (por ejemplo de la empresa contable)',
    'attr' => 'maxlength="16"',
]);
echo $f->end('Guardar');
?>
<div style="float:left;color:red">* campo es obligatorio</div>
<div style="float:right;margin-bottom:1em;font-size:0.8em">
    <a href="<?=$_base.$listarUrl?>">Volver al listado de cuentas contables</a>
</div>
<script>
var clasificaciones = <?=json_encode($subclasificaciones)?>;
var oficiales = <?=json_encode($oficiales)?>;
function clasificacion_procesar() {
    Form.addOptions("clasificacionField", clasificaciones, document.getElementById("principalField").value);
    Form.addOptions("oficialField", oficiales, document.getElementById("principalField").value);
<?php if (isset($Obj)) : ?>
    document.getElementById("clasificacionField").value = '<?=$Obj->clasificacion?>';
    document.getElementById("oficialField").value = '<?=$Obj->oficial?>';
<?php else : ?>
<?php if (isset($_POST['clasificacion'])): ?>
    document.getElementById("clasificacionField").value = '<?=$_POST['clasificacion']?>';
<?php endif; ?>
<?php if (isset($_POST['oficial'])): ?>
    document.getElementById("oficialField").value = '<?=$_POST['oficial']?>';
<?php endif; ?>
<?php endif; ?>
}
$(function() { clasificacion_procesar(); });
</script>
