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
 * Clase para generar el Libro Mayor
 * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
 * @version 2016-02-09
 */
class Controller_LibroMayor extends \Controller_App
{

    /**
     * Acción principal que genera el libro mayor
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2016-02-10
     */
    public function index()
    {
        if (isset($_POST['submit'])) {
            $Contribuyente = $this->getContribuyente();
            $cuentas = (new Model_LceAsientos())->setContribuyente($Contribuyente->rut)->getLibroMayor($_POST['desde'], $_POST['hasta']);
            if (!isset($cuentas[0])) {
                \sowerphp\core\Model_Datasource_Session::message(
                    'No se encontraron cuentas para el período seleccionado', 'info'
                );
            } else {
                $this->set([
                    'cuentas' => $cuentas,
                ]);
            }
        }
    }

}
