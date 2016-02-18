<?php

/**
 * LibreDTE
 * Copyright (C) SASCO SpA (https://sasco.cl)
 *
 * Este programa es software libre: usted puede redistribuirlo y/o
 * modificarlo bajo los términos de la Licencia Pública General GNU
 * publicada por la Fundación para el Software Libre, ya sea la versión
 * 3 de la Licencia, o (a su elección) cualquier versión posterior de la
 * misma.
 *
 * Este programa se distribuye con la esperanza de que sea útil, pero
 * SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita
 * MERCANTIL o de APTITUD PARA UN PROPÓSITO DETERMINADO.
 * Consulte los detalles de la Licencia Pública General GNU para obtener
 * una información más detallada.
 *
 * Debería haber recibido una copia de la Licencia Pública General GNU
 * junto a este programa.
 * En caso contrario, consulte <http://www.gnu.org/licenses/gpl.html>.
 */

namespace website\Lce;

/**
 * Clase para generar PDF con el libro diario
 * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
 * @version 2016-02-17
 */
class View_Helper_LibroDiario extends \website\View_Helper_PDF
{

    public function agregar($asientos)
    {
        $this->startPageGroup();
        $this->AddPage();
        foreach ($asientos as $a) {
            foreach ($a['detalle'] as &$d) {
                if ($d['debe'])
                    $d['debe'] = num($d['debe']);
                if ($d['haber'])
                    $d['haber'] = num($d['haber']);
            }
            $this->SetFont('helvetica', 'B', 10);
            $this->Texto('#'.$a['asiento'].': '.$a['glosa']);
            $this->Texto(\sowerphp\general\Utility_Date::format($a['fecha']), null, null, 'R');
            $this->Ln();
            $this->SetFont('helvetica', '', 10);
            $titulos = ['Cuenta', 'Debe', 'Haber'];
            $a['detalle'][] = [null, num($a['debito']), num($a['credito'])];
            $this->addTable($titulos, $a['detalle'], ['width'=>[100, 43, 43], 'align'=>['left', 'right', 'right']], true);
            $this->Ln();
        }
    }

}
