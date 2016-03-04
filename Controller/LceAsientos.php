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
 * Clase para el controlador asociado a la tabla lce_asiento de la base de
 * datos
 * Comentario de la tabla: Cabecera de los asientos contables
 * Esta clase permite controlar las acciones entre el modelo y vista para la
 * tabla lce_asiento
 * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
 * @version 2016-02-09
 */
class Controller_LceAsientos extends \Controller_Maintainer
{

    protected $namespace = __NAMESPACE__; ///< Namespace del controlador y modelos asociados
    protected $columnsView = [
        'listar'=>['periodo', 'asiento', 'fecha', 'glosa', 'anulado']
    ]; ///< Columnas que se deben mostrar en las vistas

    /**
     * Acción para listar las cuentas contables del contribuyente
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-02-09
     */
    public function listar($page = 1, $orderby = null, $order = 'A')
    {
        $Contribuyente = $this->getContribuyente();
        $this->forceSearch(['contribuyente'=>$Contribuyente->rut]);
        parent::listar($page, $orderby, $order);
    }

    /**
     * Acción para crear un asiento contable
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-03-03
     */
    public function crear()
    {
        $Contribuyente = $this->getContribuyente();
        $_POST['contribuyente'] = $Contribuyente->rut;
        $_POST['usuario'] = $this->Auth->User->id;
        $Cuentas = (new \website\Lce\Model_LceCuentas())->setContribuyente($Contribuyente->rut);
        $this->set([
            '_header_extra' => ['js'=>['/lce/js/asiento.js']],
            'cuentas' => $Cuentas->getList(),
            'ayuda' => $Cuentas->getAyuda(),
            'detalle' => [],
        ]);
        if (!isset($_POST['submit'])) {
            parent::crear();
        } else {
            $Asiento = new Model_LceAsiento();
            $Asiento->set($_POST);
            try {
                if ($Asiento->save() and $Asiento->saveDetalle($this->obtenerDetallePost())) {
                    \sowerphp\core\Model_Datasource_Session::message(
                        'Registro creado', 'ok'
                    );
                } else {
                    \sowerphp\core\Model_Datasource_Session::message(
                        'Registro no creado', 'error'
                    );
                }
            } catch (\Exception $e) {
                \sowerphp\core\Model_Datasource_Session::message(
                    'Registro no creado: '.$e->getMessage(), 'error'
                );
            }
            // redireccionar
            $filterListar = !empty($_GET['listar']) ? base64_decode($_GET['listar']) : '';
            $this->redirect(
                $this->module_url.$this->request->params['controller'].'/listar'.$filterListar
            );
        }
    }

    /**
     * Acción para editar un asiento contable
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-03-03
     */
    public function editar($periodo, $asiento = null)
    {
        $Contribuyente = $this->getContribuyente();
        $_POST['contribuyente'] = $Contribuyente->rut;
        $_POST['usuario'] = $this->Auth->User->id;
        $Asiento = new Model_LceAsiento($Contribuyente->rut, $periodo, $asiento);
        $Cuentas = (new \website\Lce\Model_LceCuentas())->setContribuyente($Contribuyente->rut);
        $this->set([
            '_header_extra' => ['js'=>['/lce/js/asiento.js']],
            'cuentas' => $Cuentas->getList(),
            'ayuda' => $Cuentas->getAyuda(),
            'detalle' => $Asiento->getDetalle(false),
        ]);
        if (!isset($_POST['submit'])) {
            parent::editar($Contribuyente->rut, $periodo, $asiento);
        } else {
            $Asiento->set($_POST);
            try {
                if ($Asiento->save() and $Asiento->saveDetalle($this->obtenerDetallePost())) {
                    \sowerphp\core\Model_Datasource_Session::message(
                        'Registro ('.implode(', ', func_get_args()).') editado', 'ok'
                    );
                } else {
                    \sowerphp\core\Model_Datasource_Session::message(
                        'Registro ('.implode(', ', func_get_args()).') no editado', 'error'
                    );
                }
            } catch (\Exception $e) {
                \sowerphp\core\Model_Datasource_Session::message(
                    'Registro ('.implode(', ', func_get_args()).') no editado: '.$e->getMessage(), 'error'
                );
            }
            // redireccionar
            $filterListar = !empty($_GET['listar']) ? base64_decode($_GET['listar']) : '';
            $this->redirect(
                $this->module_url.$this->request->params['controller'].'/listar'.$filterListar
            );
        }
    }

    /**
     * Método que entrega el detalle del asiento enviado por POST
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-02-09
     */
    private function obtenerDetallePost()
    {
        $detalle = [];
        $n_detalle = count($_POST['cuenta']);
        for ($i=0; $i<$n_detalle; $i++) {
            $detalle[] = [
                'cuenta' => $_POST['cuenta'][$i],
                'debe' => $_POST['debe'][$i],
                'haber' => $_POST['haber'][$i],
                'concepto' => $_POST['concepto'][$i],

            ];
        }
        return $detalle;
    }

    /**
     * Acción para eliminar un asient contable
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-03-03
     */
    public function eliminar($periodo, $asiento = null)
    {
        $Contribuyente = $this->getContribuyente();
        parent::eliminar($Contribuyente->rut, $periodo, $asiento);
    }

}
