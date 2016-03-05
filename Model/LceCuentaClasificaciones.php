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
namespace website\Lce;

/**
 * Clase para mapear la tabla lce_cuenta_clasificacion de la base de datos
 * Comentario de la tabla:
 * Esta clase permite trabajar sobre un conjunto de registros de la tabla lce_cuenta_clasificacion
 * @author SowerPHP Code Generator
 * @version 2016-03-04 22:54:48
 */
class Model_LceCuentaClasificaciones extends \Model_Plural_App
{

    // Datos para la conexión a la base de datos
    protected $_database = 'default'; ///< Base de datos del modelo
    protected $_table = 'lce_cuenta_clasificacion'; ///< Tabla del modelo

    /**
     * Método que entrega todas las clasificaciones ordenadas
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-03-05
     */
    public function getList()
    {
        $clasificaciones = $this->db->getTable('
            SELECT codigo AS id, '.$this->db->concat('codigo', ' - ', 'clasificacion').' AS glosa
            FROM lce_cuenta_clasificacion
            WHERE contribuyente = :contribuyente
            ORDER BY codigo
        ', [':contribuyente'=>$this->contribuyente]);
        foreach ($clasificaciones as &$c) {
            $c['glosa'] = str_repeat('&nbsp;',(strlen($c['id'])-1)*3).$c['glosa'];
        }
        return $clasificaciones;
    }

    /**
     * Método que entrega las clasificaciones principales o superiores
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-03-04
     */
    public function getListPrincipales()
    {
        return $this->db->getTable('
            SELECT codigo AS id, '.$this->db->concat('codigo', ' - ', 'clasificacion').' AS glosa
            FROM lce_cuenta_clasificacion
            WHERE contribuyente = :contribuyente AND superior IS NULL
            ORDER BY codigo
        ', [':contribuyente'=>$this->contribuyente]);
    }

    /**
     * Método que entrega las subclasificaciones
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-02-09
     */
    public function getListSubclasificaciones()
    {
        return $this->db->getAssociativeArray('
            SELECT SUBSTR(codigo, 1, 1) AS super, codigo AS id, '.$this->db->concat('codigo', ' - ', 'clasificacion').' AS glosa
            FROM lce_cuenta_clasificacion
            WHERE contribuyente = :contribuyente AND superior IS NOT NULL
            ORDER BY codigo
        ', [':contribuyente'=>$this->contribuyente]);
    }

}
