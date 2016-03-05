<ul class="nav nav-pills pull-right">
    <li>
        <a href="<?=$_base?>/lce/lce_asientos/listar<?=$filterListar?>" title="Volver al mantenedor de asientos contables">
            Volver a los asientos
        </a>
    </li>
</ul>
<h1>Ordenar asientos</h1>
<?php
$f = new \sowerphp\general\View_Helper_Form();
echo $f->begin(['onsubmit'=>'Form.check() && Form.checkSend(\'¿Está seguro de ordenar los asientos del período seleccionado?\')']);
echo $f->input([
    'type' => 'select',
    'name' => 'periodo',
    'label' => 'Período',
    'options' => $periodos,
    'check' => 'notempty',
]);
echo $f->end('Ordenar asientos del período');
