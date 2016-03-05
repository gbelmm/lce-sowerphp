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

// namespace del controlador
namespace website\Lce;

/**
 * Clase para el controlador asociado a la tabla lce_cuenta de la base de
 * datos
 * Comentario de la tabla: Plan de cuentas de la empresa (por ejemplo plan de cuentas MiPyme SII)
 * Esta clase permite controlar las acciones entre el modelo y vista para la
 * tabla lce_cuenta
 * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
 * @version 2016-02-23
 */
class Controller_LceCuentas extends \Controller_Maintainer
{

    protected $namespace = __NAMESPACE__; ///< Namespace del controlador y modelos asociados
    protected $columnsView = [
        'listar'=>['codigo', 'cuenta', 'clasificacion', 'descripcion', 'codigo_otro', 'activa']
    ]; ///< Columnas que se deben mostrar en las vistas

    /**
     * Acción para listar las cuentas contables del contribuyente
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-03-04
     */
    public function listar($page = 1, $orderby = null, $order = 'A')
    {
        $Contribuyente = $this->getContribuyente();
        $this->set([
            'clasificaciones' => (new Model_LceCuentaClasificaciones())->setContribuyente($Contribuyente->rut)->getList(),
        ]);
        $this->forceSearch(['contribuyente'=>$Contribuyente->rut]);
        parent::listar($page, $orderby, $order);
    }

    /**
     * Acción para crear una cuenta contable
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-03-04
     */
    public function crear()
    {
        $Contribuyente = $this->getContribuyente();
        $_POST['contribuyente'] = $Contribuyente->rut;
        $Clasificaciones = new Model_LceCuentaClasificaciones();
        $Clasificaciones->setContribuyente($Contribuyente->rut);
        $this->set([
            'clasificaciones' => $Clasificaciones->getListPrincipales(),
            'subclasificaciones' => $Clasificaciones->getListSubclasificaciones(),
            'oficiales' => (new \website\Lce\Admin\Mantenedores\Model_LceCuentaOficiales())->getList(),
        ]);
        parent::crear();
    }

    /**
     * Acción para editar una cuenta contable
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-03-05
     */
    public function editar($cuenta)
    {
        $Contribuyente = $this->getContribuyente();
        $_POST['contribuyente'] = $Contribuyente->rut;
        $Clasificaciones = new Model_LceCuentaClasificaciones();
        $Clasificaciones->setContribuyente($Contribuyente->rut);
        $this->set([
            'clasificaciones' => $Clasificaciones->getListPrincipales(),
            'subclasificaciones' => $Clasificaciones->getListSubclasificaciones(),
            'oficiales' => (new \website\Lce\Admin\Mantenedores\Model_LceCuentaOficiales())->getList(),
        ]);
        parent::editar($Contribuyente->rut, $cuenta);
    }

    /**
     * Acción para eliminar una cuenta contable
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-02-09
     */
    public function eliminar($cuenta)
    {
        $Contribuyente = $this->getContribuyente();
        $Cuenta = new Model_LceCuenta($Contribuyente->rut, $cuenta);
        if ($Cuenta->enUso()) {
            \sowerphp\core\Model_Datasource_Session::message(
                'No es posible eliminar la cuenta '.$cuenta.' ya que existen asientos contables que la usan', 'error'
            );
            $filterListar = !empty($_GET['listar']) ? base64_decode($_GET['listar']) : '';
            $this->redirect(
                $this->module_url.$this->request->params['controller'].'/listar'.$filterListar
            );
        }
        parent::eliminar($Contribuyente->rut, $cuenta);
    }

    /**
     * Acción que muestra el diccionario de cuentas contables
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-02-09
     */
    public function diccionario()
    {
        $Contribuyente = $this->getContribuyente();
        $this->set([
            'Contribuyente' => $Contribuyente,
            'cuentas' => (new Model_LceCuentas())->setContribuyente($Contribuyente->rut)->getDiccionario(),
        ]);
    }

    /**
     * Acción que permite migrar los códigos de cuenta del plan a otro código
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-03-05
     */
    public function migrar()
    {
        if (isset($_POST['submit'])) {
            // verificar que se haya podido subir el archivo con los códigos
            if (!isset($_FILES['archivo']) or $_FILES['archivo']['error']) {
                \sowerphp\core\Model_Datasource_Session::message(
                    'Ocurrió un error al subir el archivo con los códigos', 'error'
                );
                return;
            }
            // migrar cada código
            $Contribuyente = $this->getContribuyente();
            $cuentas = \sowerphp\general\Utility_Spreadsheet::read($_FILES['archivo']);
            array_shift($cuentas);
            $LceCuentas = new Model_LceCuentas();
            $LceCuentas->setContribuyente($Contribuyente->rut);
            $error = [];
            foreach ($cuentas as $c) {
                try {
                    $LceCuentas->migrar($c[0], $c[1]);
                } catch (\sowerphp\core\Exception_Model_Datasource_Database $e) {
                    $error[] = $e->getMessage();
                }
            }
            // mostrar errores o redireccionar
            if (!empty($error)) {
                \sowerphp\core\Model_Datasource_Session::message(
                    'No se pudieron guardar todas las cuentas:<br/>'.implode('<br/>', $error),
                    'warning'
                );
            } else {
                \sowerphp\core\Model_Datasource_Session::message(
                    'Se migraron los códigos de las cuentas', 'ok'
                );
                $this->redirect('/lce/lce_cuentas/listar');
            }
        }
    }

    /**
     * Acción que permite importar el plan de cuentas contables desde un archivo
     * CSV
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-02-23
     */
    public function importar()
    {
        if (isset($_POST['submit'])) {
            // verificar que se haya podido subir el archivo con el libro
            if (!isset($_FILES['archivo']) or $_FILES['archivo']['error']) {
                \sowerphp\core\Model_Datasource_Session::message(
                    'Ocurrió un error al subir el plan de cuentas', 'error'
                );
                return;
            }
            // agregar cada cuenta al plan
            $Contribuyente = $this->getContribuyente();
            $cuentas = \sowerphp\general\Utility_Spreadsheet::read($_FILES['archivo']);
            array_shift($cuentas);
            $resumen = ['nuevas'=>[], 'editadas'=>[], 'error'=>[]];
            $cols = ['codigo', 'cuenta', 'clasificacion', 'oficial', 'descripcion', 'cargos', 'abonos', 'saldo_deudor', 'saldo_acreedor', 'activa', 'codigo_otro'];
            $n_cols = count($cols);
            foreach ($cuentas as $c) {
                // crear objeto
                $LceCuenta = new Model_LceCuenta();
                $LceCuenta->contribuyente = $Contribuyente->rut;
                for ($i=0; $i<$n_cols; $i++) {
                    $LceCuenta->{$cols[$i]} = $c[$i];
                }
                // corregir cuenta oficial (agregar puntos si se pasó)
                if ($LceCuenta->oficial and !strpos($LceCuenta->oficial, '.')) {
                    $LceCuenta->oficial = substr($LceCuenta->oficial, 0, -3).'.'.substr($LceCuenta->oficial, -3);
                }
                // guardar
                try {
                    $existia = $LceCuenta->exists();
                    if ($LceCuenta->save()) {
                        if ($existia)
                            $resumen['editadas'][] = $LceCuenta->codigo;
                        else
                            $resumen['nuevas'][] = $LceCuenta->codigo;
                    } else {
                        $resumen['error'][] = $LceCuenta->codigo;
                    }
                } catch (\sowerphp\core\Exception_Model_Datasource_Database $e) {
                    $resumen['error'][] = $LceCuenta->codigo;
                }
            }
            // mostrar errores o redireccionar
            if (!empty($resumen['error'])) {
                \sowerphp\core\Model_Datasource_Session::message(
                    'No se pudieron guardar todas las cuentas:<br/>- nuevas: '.implode(', ', $resumen['nuevas']).
                        '<br/>- editadas: '.implode(', ', $resumen['editadas']).
                        '<br/>- con error: '.implode(', ', $resumen['error']),
                    ((empty($resumen['nuevas']) and empty($resumen['editadas'])) ? 'error' : 'warning')
                );
            } else {
                \sowerphp\core\Model_Datasource_Session::message(
                    'Se importó el plan de cuentas contable', 'ok'
                );
                $this->redirect('/lce/lce_cuentas/listar');
            }
        }
    }

    /**
     * Acción que permite descargar el XML del diccionario de cuentas contables
     * @todo Programar método al tener soporte en libredte-lib
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-02-09
     */
    public function xml()
    {
        $Contribuyente = $this->getContribuyente();
        $cuentas = (new Model_LceCuentas())->setContribuyente($Contribuyente->rut)->getDiccionario(false);
        // TODO
    }

}
