<h1>Diccionario de cuentas contables</h1>
<?php
array_unshift($cuentas, ['Clasificación', 'Código', 'Glosa', 'Código SII']);
new \sowerphp\general\View_Helper_Table($cuentas, 'diccionario_cuentas_'.$Contribuyente->rut, true);
?>
<a class="btn btn-primary btn-lg btn-block" href="xml" role="button">
    <span class="fa fa-file-code-o" style="font-size:24px"></span>
    Descargar diccionario en XML
</a>
