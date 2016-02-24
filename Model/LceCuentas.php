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
 * Clase para mapear la tabla lce_cuenta de la base de datos
 * Comentario de la tabla: Plan de cuentas de la empresa (por ejemplo plan de cuentas MiPyme SII)
 * Esta clase permite trabajar sobre un conjunto de registros de la tabla lce_cuenta
 * @author SowerPHP Code Generator
 * @version 2016-02-08 01:50:20
 */
class Model_LceCuentas extends \Model_Plural_App
{

    // Datos para la conexión a la base de datos
    protected $_database = 'default'; ///< Base de datos del modelo
    protected $_table = 'lce_cuenta'; ///< Tabla del modelo

    protected $contribuyente; ///< Contribuyente con el que se realizarán las consultas

    /**
     * Método que asigna el contribuyente que se utilizará en las consultas
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-02-09
     */
    public function setContribuyente($contribuyente)
    {
        $this->contribuyente = $contribuyente;
        return $this;
    }

    /**
     * Método que entrega el listado de cuentas contables
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-02-23
     */
    public function getList()
    {
        return $this->db->getTable ('
            SELECT codigo AS id, '.$this->db->concat('codigo', ' - ', 'cuenta').' AS glosa
            FROM lce_cuenta
            WHERE contribuyente = :contribuyente AND activa = true
            ORDER BY
                CHAR_LENGTH(clasificacion), clasificacion,
                CHAR_LENGTH(subclasificacion), subclasificacion,
                CHAR_LENGTH(codigo), codigo
        ', [':contribuyente'=>$this->contribuyente]);
    }

    /**
     * Método que entrega el diccionario de cuentas contables
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-02-09
     */
    public function getDiccionario($clasificacion_glosa = true)
    {
        if ($clasificacion_glosa) {
            return $this->db->getTable ('
                SELECT cl.clasificacion, c.codigo, c.cuenta, c.oficial
                FROM lce_cuenta AS c, lce_cuenta_clasificacion AS cl
                WHERE c.clasificacion = cl.codigo AND c.contribuyente = :contribuyente AND c.activa = true
                ORDER BY
                    CHAR_LENGTH(c.clasificacion), c.clasificacion,
                    CHAR_LENGTH(c.subclasificacion), c.subclasificacion,
                    CHAR_LENGTH(c.codigo), c.codigo
            ', [':contribuyente'=>$this->contribuyente]);
        } else {
            return $this->db->getTable ('
                SELECT clasificacion, codigo, cuenta, oficial
                FROM lce_cuenta
                WHERE contribuyente = :contribuyente AND activa = true
                ORDER BY
                    CHAR_LENGTH(clasificacion), clasificacion,
                    CHAR_LENGTH(subclasificacion), subclasificacion,
                    CHAR_LENGTH(codigo), codigo
            ', [':contribuyente'=>$this->contribuyente]);
        }
    }

    /**
     * Método que obtiene la ayuda para la carga o abono de las cuentas
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-02-10
     */
    public function getAyuda()
    {
        return $this->db->getAssociativeArray('
            SELECT codigo, descripcion, cargos, abonos
            FROM lce_cuenta
            WHERE contribuyente = :contribuyente AND activa = true
            ORDER BY
                CHAR_LENGTH(clasificacion), clasificacion,
                CHAR_LENGTH(subclasificacion), subclasificacion,
                CHAR_LENGTH(codigo), codigo
        ', [':contribuyente'=>$this->contribuyente]);
    }

}
