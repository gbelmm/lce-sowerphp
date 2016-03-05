<?php

/**
 * SowerPHP: Minimalist Framework for PHP
 * Copyright (C) SowerPHP (http://sowerphp.org)
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
 * Clase para el controlador asociado a la tabla lce_cuenta_clasificacion de la base de
 * datos
 * Comentario de la tabla:
 * Esta clase permite controlar las acciones entre el modelo y vista para la
 * tabla lce_cuenta_clasificacion
 * @author SowerPHP Code Generator
 * @version 2016-03-04 22:54:48
 */
class Controller_LceCuentaClasificaciones extends \Controller_Maintainer
{

    protected $namespace = __NAMESPACE__; ///< Namespace del controlador y modelos asociados
    protected $columnsView = [
        'listar'=>['codigo', 'clasificacion', 'superior', 'descripcion']
    ]; ///< Columnas que se deben mostrar en las vistas

    /**
     * Acción para listar las clasificaciones de cuentas contables del contribuyente
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
     * Acción para crear una clasificación de cuenta contable
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-03-04
     */
    public function crear()
    {
        $Contribuyente = $this->getContribuyente();
        $_POST['contribuyente'] = $Contribuyente->rut;
        $this->set([
            'clasificaciones' => (new Model_LceCuentaClasificaciones())->setContribuyente($Contribuyente->rut)->getList(),
        ]);
        parent::crear();
    }

    /**
     * Acción para editar una clasificación de cuenta contable
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-03-04
     */
    public function editar($clasificacion)
    {
        $Contribuyente = $this->getContribuyente();
        $_POST['contribuyente'] = $Contribuyente->rut;
        $this->set([
            'clasificaciones' => (new Model_LceCuentaClasificaciones())->setContribuyente($Contribuyente->rut)->getList(),
        ]);
        parent::editar($Contribuyente->rut, $clasificacion);
    }

    /**
     * Acción para eliminar una clasificación de cuenta contable
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-03-04
     */
    public function eliminar($clasificacion)
    {
        $Contribuyente = $this->getContribuyente();
        $Clasificacion = new Model_LceCuentaClasificacion($Contribuyente->rut, $clasificacion);
        if ($Clasificacion->enUso()) {
            \sowerphp\core\Model_Datasource_Session::message(
                'No es posible eliminar la clasificación '.$clasificacion.' ya que existen cuentas contables que la usan', 'error'
            );
            $filterListar = !empty($_GET['listar']) ? base64_decode($_GET['listar']) : '';
            $this->redirect(
                $this->module_url.$this->request->params['controller'].'/listar'.$filterListar
            );
        }
        parent::eliminar($Contribuyente->rut, $clasificacion);
    }

    /**
     * Acción que permite importar las clasificaciones del plan de cuentas
     * contables desde un archivoCSV
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-03-04
     */
    public function importar()
    {
        if (isset($_POST['submit'])) {
            // verificar que se haya podido subir el archivo con el libro
            if (!isset($_FILES['archivo']) or $_FILES['archivo']['error']) {
                \sowerphp\core\Model_Datasource_Session::message(
                    'Ocurrió un error al subir el archivo con las clasificaciones', 'error'
                );
                return;
            }
            // agregar cada clasificacion
            $Contribuyente = $this->getContribuyente();
            $clasificaciones = \sowerphp\general\Utility_Spreadsheet::read($_FILES['archivo']);
            array_shift($clasificaciones);
            $resumen = ['nuevas'=>[], 'editadas'=>[], 'error'=>[]];
            $cols = ['codigo', 'clasificacion', 'superior', 'descripcion'];
            $n_cols = count($cols);
            foreach ($clasificaciones as $c) {
                // crear objeto
                $LceCuentaClasificacion = new Model_LceCuentaClasificacion();
                $LceCuentaClasificacion->contribuyente = $Contribuyente->rut;
                for ($i=0; $i<$n_cols; $i++) {
                    $LceCuentaClasificacion->{$cols[$i]} = $c[$i];
                }
                // guardar
                try {
                    $existia = $LceCuentaClasificacion->exists();
                    if ($LceCuentaClasificacion->save()) {
                        if ($existia)
                            $resumen['editadas'][] = $LceCuentaClasificacion->codigo;
                        else
                            $resumen['nuevas'][] = $LceCuentaClasificacion->codigo;
                    } else {
                        $resumen['error'][] = $LceCuentaClasificacion->codigo;
                    }
                } catch (\sowerphp\core\Exception_Model_Datasource_Database $e) {
                    $resumen['error'][] = $LceCuentaClasificacion->codigo;
                }
            }
            // mostrar errores o redireccionar
            if (!empty($resumen['error'])) {
                \sowerphp\core\Model_Datasource_Session::message(
                    'No se pudieron guardar todas las clasificaciones:<br/>- nuevas: '.implode(', ', $resumen['nuevas']).
                        '<br/>- editadas: '.implode(', ', $resumen['editadas']).
                        '<br/>- con error: '.implode(', ', $resumen['error']),
                    ((empty($resumen['nuevas']) and empty($resumen['editadas'])) ? 'error' : 'warning')
                );
            } else {
                \sowerphp\core\Model_Datasource_Session::message(
                    'Se importaron las clasificaciones de cuentas contables', 'ok'
                );
                $this->redirect('/lce/lce_cuenta_clasificaciones/listar');
            }
        }
    }

}
