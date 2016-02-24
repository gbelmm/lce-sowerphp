<a href="<?=$_base?>/lce/lce_cuentas/listar" title="Volver al plan de cuentas contables" class="pull-right"><span class="btn btn-default">Volver al plan de cuentas</span></a>
<h1>Importar plan de cuentas contable desde archivo CSV</h1>
<?php
$f = new \sowerphp\general\View_Helper_Form();
echo $f->begin(['onsubmit'=>'Form.check() && Form.checkSend(\'¿Está seguro de importar el plan de cuentas  seleccionado?\')']);
echo $f->input([
    'type' => 'file',
    'name' => 'archivo',
    'label' => 'Plan de cuentas',
    'help' => 'Plan de cuentas formato CSV. Puede consultar un <a href="'.$_base.'/lce/archivos/plan_mipyme.csv">el plan de cuentas MiPyME</a> para conocer el formato esperado.',
    'check' => 'notempty',
    'attr' => 'accept="csv"',
]);
echo $f->end('Importar plan de cuentas');
