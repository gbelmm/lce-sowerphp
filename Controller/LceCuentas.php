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
 * @version 2016-02-09
 */
class Controller_LceCuentas extends \Controller_Maintainer
{

    protected $namespace = __NAMESPACE__; ///< Namespace del controlador y modelos asociados
    protected $columnsView = [
        'listar'=>['codigo', 'cuenta', 'clasificacion', 'descripcion', 'activa']
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
     * Acción para crear una cuenta contable
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-02-09
     */
    public function crear()
    {
        $Contribuyente = $this->getContribuyente();
        $_POST['contribuyente'] = $Contribuyente->rut;
        $Clasificaciones = new \website\Lce\Admin\Mantenedores\Model_LceCuentaClasificaciones();
        $this->set([
            'clasificaciones' => $Clasificaciones->getList(),
            'subclasificaciones' => $Clasificaciones->getListSub(),
            'oficiales' => (new \website\Lce\Admin\Mantenedores\Model_LceCuentaOficiales())->getList(),
        ]);
        parent::crear();
    }

    /**
     * Acción para editar una cuenta contable
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-02-09
     */
    public function editar($cuenta)
    {
        $Contribuyente = $this->getContribuyente();
        $_POST['contribuyente'] = $Contribuyente->rut;
        $Clasificaciones = new \website\Lce\Admin\Mantenedores\Model_LceCuentaClasificaciones();
        $this->set([
            'clasificaciones' => $Clasificaciones->getList(),
            'subclasificaciones' => $Clasificaciones->getListSub(),
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