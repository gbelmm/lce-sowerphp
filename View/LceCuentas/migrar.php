<ul class="nav nav-pills pull-right">
    <li>
        <a href="<?=$_base?>/lce/lce_cuentas/listar" title="Volver al plan de cuentas contables">
            Volver al plan de cuentas
        </a>
    </li>
</ul>
<h1>Migrar códigos de cuentas contables</h1>
<?php
$f = new \sowerphp\general\View_Helper_Form();
echo $f->begin(['onsubmit'=>'Form.check() && Form.checkSend(\'¿Está seguro de querer migrar las cuentas?\')']);
echo $f->input([
    'type' => 'file',
    'name' => 'archivo',
    'label' => 'Códigos',
    'help' => 'Archivo CSV con 2 columnas: el código original y el nuevo',
    'check' => 'notempty',
    'attr' => 'accept="csv"',
]);
echo $f->end('Migrar códigos');
