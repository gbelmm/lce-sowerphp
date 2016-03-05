<ul class="nav nav-pills pull-right">
    <li>
        <a href="<?=$_base?>/lce/lce_cuenta_clasificaciones/listar" title="Volver a las clasificaciones de cuentas contables">
            Volver a clasificaciones
        </a>
    </li>
</ul>
<h1>Importar clasificaciones de cuentas contables</h1>
<?php
$f = new \sowerphp\general\View_Helper_Form();
echo $f->begin(['onsubmit'=>'Form.check() && Form.checkSend(\'¿Está seguro de importar las clasificaciones seleccionadas?\')']);
echo $f->input([
    'type' => 'file',
    'name' => 'archivo',
    'label' => 'Clasificaciones',
    'help' => 'Clasificaciones del plan de cuentas formato CSV. Puede consultar un <a href="'.$_base.'/lce/archivos/lce_cuenta_clasificacion.csv">ejemplo</a> para conocer el formato esperado.',
    'check' => 'notempty',
    'attr' => 'accept="csv"',
]);
echo $f->end('Importar clasificaciones');
