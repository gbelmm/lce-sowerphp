<ul class="nav nav-pills pull-right">
    <li>
        <a href="<?=$_base?>/lce/lce_cuentas/listar" title="Volver al plan de cuentas contables">
            Volver al plan de cuentas
        </a>
    </li>
</ul>
<h1>Importar plan de cuentas contables</h1>
<?php
$f = new \sowerphp\general\View_Helper_Form();
echo $f->begin(['onsubmit'=>'Form.check() && Form.checkSend(\'¿Está seguro de importar el plan de cuentas seleccionado?\')']);
echo $f->input([
    'type' => 'file',
    'name' => 'archivo',
    'label' => 'Plan de cuentas',
    'help' => 'Plan de cuentas formato CSV. Puede consultar un <a href="'.$_base.'/lce/archivos/lce_cuenta.csv">ejemplo</a> para conocer el formato esperado.',
    'check' => 'notempty',
    'attr' => 'accept="csv"',
]);
echo $f->end('Importar plan de cuentas');
