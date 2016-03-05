<h1><?=$accion?> clasificación de cuenta contable</h1>
<?php
$f = new \sowerphp\general\View_Helper_Form();
echo $f->begin(['onsubmit'=>'Form.check()']);
echo $f->input([
    'name' => 'codigo',
    'label' => 'Código',
    'value' => isset($Obj) ? $Obj->codigo : '',
    'check' => 'notempty',
    'help' => 'Código de la clasificación de la cuenta contable, ejemplo: 1',
    'attr' => 'maxlength="10"'.(isset($Obj)?' readonly="readonly"':''),
]);
echo $f->input([
    'name' => 'clasificacion',
    'label' => 'Clasificación',
    'value' => isset($Obj) ? $Obj->clasificacion : '',
    'check' => 'notempty',
    'help' => 'Glosa de la clasificación de la cuenta contable, ejemplo: Activo',
    'attr' => 'maxlength="50"',
]);
echo $f->input([
    'type' => 'select',
    'name' => 'superior',
    'label' => 'Superior',
    'options' => [''=>'No tiene clasificación superior'] + $clasificaciones,
    'value' => isset($Obj) ? $Obj->superior : '',
    'help' => 'Clasificación a la que pertenece esta',
]);
echo $f->input([
    'type' => 'textarea',
    'name' => 'descripcion',
    'label' => 'Descripción',
    'value' => isset($Obj) ? $Obj->descripcion : '',
    'help' => 'Descripción general de la cuenta contable',
]);
echo $f->end('Guardar');
?>
<div style="float:left;color:red">* campo es obligatorio</div>
<div style="float:right;margin-bottom:1em;font-size:0.8em">
    <a href="<?=$_base.$listarUrl?>">Volver al listado de clasificaciones de cuentas contables</a>
</div>
