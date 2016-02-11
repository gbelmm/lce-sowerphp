<?php if (isset($datos)) : ?>
<a href="javascript:window.print()" title="Imprimir balance general" class="pull-right hidden-print">
    <i class="fa fa-print fa-3x fa-fw"></i>
</a>
<?php endif; ?>
<h1>Contabilidad &raquo; Balance general</h1>

<style>
#balance_general td {
        font-size: 14px;
}
#balance_general th + th {
        text-align: right;
}
#balance_general td + td {
        text-align: right;
        font-family: FreeMono;
}
</style>

<div class="hidden-print">
<p>Aquí podrá generar el balance general.</p>
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
echo $f->end('Generar balance general');
echo '</div>';

if (isset($datos)) {
    extract($datos);
    foreach ($balance as &$cuenta) {
        foreach ($cuenta as &$valor) {
            if (is_numeric($valor)) {
                $valor = num($valor);
            }
        }
    }
    foreach ($sumas_parciales as &$valor) {
        if (is_numeric($valor)) {
            $valor = num($valor);
        }
    }
    foreach ($resultados as &$valor) {
        if (is_numeric($valor)) {
            $valor = num($valor);
        }
    }
    foreach ($suma_total as &$valor) {
        if (is_numeric($valor)) {
            $valor = num($valor);
        }
    }
    echo '<p class="visible-print">Balance general entre el ',\sowerphp\general\Utility_Date::format($_POST['desde']),' y el ',\sowerphp\general\Utility_Date::format($_POST['hasta']),'</p>',"\n";
    array_unshift ($balance, ['Cuenta', 'Débitos', 'Créditos', 'Saldo deudor', 'Saldo acreedor', 'Activo', 'Pasivo', 'Pérdidas', 'Ganancias']);
    array_unshift ($sumas_parciales, '<span style="text-align:right"><strong>Sumas parciales</strong></span>');
    $resultados = ['<span style="text-align:right"><strong>Resultados</strong></span>','','','',''] + $resultados;
    array_unshift ($suma_total, '<span style="text-align:right"><strong>Suma Total</strong></span>');
    $balance[] = $sumas_parciales;
    $balance[] = $resultados;
    $balance[] = $suma_total;
    new \sowerphp\general\View_Helper_Table($balance, 'balance_general', true);
}
