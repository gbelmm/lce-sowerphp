<?php if (isset($asientos)) : ?>
<a href="javascript:window.print()" title="Imprimir libro diario" class="pull-right hidden-print">
    <i class="fa fa-print fa-3x fa-fw"></i>
</a>
<?php endif; ?>
<h1>Contabilidad &raquo; Libro diario</h1>

<div class="hidden-print">
<p>Aquí podrá revisar los asientos contables existentes en el libro diario.</p>
<?php
$f = new \sowerphp\general\View_Helper_Form();
echo $f->begin(['onsubmit'=>'Form.check()']);
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
echo $f->input([
    'type' => 'select',
    'name' => 'ver',
    'label' => 'Ver en',
    'options' => ['web'=>'Web', 'pdf'=>'PDF'],
]);
echo $f->end('Generar libro diario');
echo '</div>';

if (isset($asientos)) :
?>
<p class="visible-print">Libro diario entre el <?=\sowerphp\general\Utility_Date::format($_POST['desde'])?> y el <?=\sowerphp\general\Utility_Date::format($_POST['hasta'])?></p>
<div class="row">
<?php foreach ($asientos as $a) : ?>
    <div class="col-sm-6 col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-file-o fa-fw"></i>
                <?=$a['glosa']?>
                <div class="btn-group pull-right hidden-print">
                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu slidedown">
                        <li>
                            <a href="<?=$_base?>/lce/lce_asientos/editar/<?=$a['asiento']?>" target="_blank">
                                <i class="fa fa-edit fa-fw"></i> Editar asiento
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="javascript:window.location.reload()">
                                <i class="fa fa-refresh fa-fw"></i> Actualizar libro
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="panel-body">
<?php
$debe = 0;
foreach ($a['detalle'] as &$d) {
    $debe += $d['debe'];
    if ($d['debe'])
        $d['debe'] = num($d['debe']);
    if ($d['haber'])
        $d['haber'] = num($d['haber']);
}
array_unshift($a['detalle'], ['Cuenta', 'Debe', 'Haber']);
new \sowerphp\general\View_Helper_Table($a['detalle']);
?>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-xs-4 text-left">#<?=$a['asiento']?></div>
                    <div class="col-xs-4 text-center">$<?=num($debe)?>.-</div>
                    <div class="col-xs-4 text-right"><?=\sowerphp\general\Utility_Date::format($a['fecha'])?></div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>

<?php
endif;
