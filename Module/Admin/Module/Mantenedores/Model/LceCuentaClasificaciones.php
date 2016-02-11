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
 * Clase para mapear la tabla lce_cuenta_clasificacion de la base de datos
 * Comentario de la tabla: Clasificación y subclasificación de cuentas contables
 * Esta clase permite trabajar sobre un conjunto de registros de la tabla lce_cuenta_clasificacion
 * @author SowerPHP Code Generator
 * @version 2016-02-08 01:44:34
 */
class Model_LceCuentaClasificaciones extends \Model_Plural_App
{

    // Datos para la conexión a la base de datos
    protected $_database = 'default'; ///< Base de datos del modelo
    protected $_table = 'lce_cuenta_clasificacion'; ///< Tabla del modelo

    /**
     * Método que entrega las clasificaciones principales o superiores
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-02-09
     */
    public function getList()
    {
        return $this->db->getTable('
            SELECT codigo AS id, '.$this->db->concat('codigo', ' - ', 'clasificacion').' AS glosa
            FROM lce_cuenta_clasificacion
            WHERE superior IS NULL
            ORDER BY codigo
        ');
    }

    /**
     * Método que entrega las subclasificaciones
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-02-09
     */
    public function getListSub()
    {
        return $this->db->getAssociativeArray('
            SELECT SUBSTR(codigo, 1, 1) AS super, codigo AS id, '.$this->db->concat('codigo', ' - ', 'clasificacion').' AS glosa
            FROM lce_cuenta_clasificacion
            WHERE superior IS NOT NULL
            ORDER BY codigo
        ');
    }

}
