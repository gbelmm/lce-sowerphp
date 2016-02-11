<?php if (isset($cuentas)) : ?>
<a href="javascript:window.print()" title="Imprimir libro mayor" class="pull-right hidden-print">
    <i class="fa fa-print fa-3x fa-fw"></i>
</a>
<?php endif; ?>
<h1>Contabilidad &raquo; Libro mayor</h1>

<div class="hidden-print">
<p>Aquí podrá buscar las cuentas y sus saldos.</p>
<?php
$f = new \sowerphp\general\View_Helper_Form();
echo $f->begin(array('onsubmit'=>'Form.check()'));
echo $f->input([
    'type' => 'date',
    'name' => 'desde',
    'label' => 'Desde',
    'value' => isset($_POST['desde']) ? $_POST['desde'] : date('Y').'-01-01',
    'check' => 'notempty date',
    'help' => 'Desde cuando buscar',
    'attr' => 'onchange="document.getElementById(\'hastaField\').value = this.value"',
]);
echo $f->input([
    'type' => 'date',
    'name' => 'hasta',
    'label' => 'Hasta',
    'value' => isset($_POST['hasta']) ? $_POST['hasta'] : date('Y-m-d'),
    'check' => 'notempty date',
    'help' => 'Hasta cuando buscar',
]);
echo $f->end('Generar libro mayor');
echo '</div>';

if (isset($cuentas)) :
?>
<p class="visible-print">Libro mayor entre el <?=\sowerphp\general\Utility_Date::format($_POST['desde'])?> y el <?=\sowerphp\general\Utility_Date::format($_POST['hasta'])?></p>
<div class="row">
<?php foreach ($cuentas as $cuenta) : ?>
    <div class="col-sm-6 col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-file-o fa-fw"></i>
                <?=$cuenta['cuenta']?>
            </div>
            <div class="panel-body">
<?php
foreach ($cuenta['detalle'] as &$d) {
    if ($d['debe'])
        $d['debe'] = num($d['debe']);
    if ($d['haber'])
        $d['haber'] = num($d['haber']);
}
array_unshift($cuenta['detalle'], ['Debe', 'Haber']);
new \sowerphp\general\View_Helper_Table($cuenta['detalle']);
?>
            </div>
            <div class="panel-footer">
<?php
if ($cuenta['saldo_deudor'] != $cuenta['saldo_acreedor']) {
    if ($cuenta['saldo_deudor'])
        echo 'Saldo deudor por $'.num($cuenta['saldo_deudor']).'.-';
    else
        echo 'Saldo acreedor por $'.num($cuenta['saldo_acreedor']).'.-';
    } else {
        echo 'Cuenta saldada';
    }
?>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>

<?php
endif;
