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
 * Clase para generar el Balance General
 * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
 * @version 2016-02-09
 */
class Controller_BalanceGeneral extends \Controller_App
{

    /**
     * Acción principal para generar el balance general
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2016-02-10
     */
    public function index()
    {
        if (isset($_POST['submit'])) {
            $Contribuyente = $this->getContribuyente();
            $datos = (new Model_LceAsientos())->setContribuyente($Contribuyente->rut)->getBalanceGeneral($_POST['desde'], $_POST['hasta']);
            $this->set(array(
                'datos' => $datos,
            ));
        }
    }

}
