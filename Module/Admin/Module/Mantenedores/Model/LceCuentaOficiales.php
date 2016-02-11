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

// namespace del modelo
namespace website\Lce\Admin\Mantenedores;

/**
 * Clase para mapear la tabla lce_cuenta_oficial de la base de datos
 * Comentario de la tabla: Plan de cuentas oficial del SII, cuentas de la empresa se deben mapear a estas para construir el diccionario de cuentas
 * Esta clase permite trabajar sobre un conjunto de registros de la tabla lce_cuenta_oficial
 * @author SowerPHP Code Generator
 * @version 2016-02-08 01:44:34
 */
class Model_LceCuentaOficiales extends \Model_Plural_App
{

    // Datos para la conexión a la base de datos
    protected $_database = 'default'; ///< Base de datos del modelo
    protected $_table = 'lce_cuenta_oficial'; ///< Tabla del modelo

    /**
     * Método que entrega las cuentas oficiales del SII
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-02-09
     */
    public function getList()
    {
        $tipos = $this->db->getAssociativeArray('
            SELECT clasificacion, codigo AS id, '.$this->db->concat('codigo', ' - ', 'cuenta').' AS glosa
            FROM lce_cuenta_oficial
            ORDER BY clasificacion, codigo
        ');
        foreach ($tipos as $tipo => &$cuentas) {
            if (!isset($cuentas[0]))
                $cuentas = [$cuentas];
        }
        return $tipos;
    }

}
