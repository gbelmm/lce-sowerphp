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
 * Comentario de la tabla:
 * Esta clase permite trabajar sobre un conjunto de registros de la tabla lce_cuenta
 * @author SowerPHP Code Generator
 * @version 2016-03-04 22:54:48
 */
class Model_LceCuentas extends \Model_Plural_App
{

    // Datos para la conexión a la base de datos
    protected $_database = 'default'; ///< Base de datos del modelo
    protected $_table = 'lce_cuenta'; ///< Tabla del modelo

    /**
     * Método que entrega el listado de cuentas contables
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-03-05
     */
    public function getList()
    {
        return $this->db->getTable ('
            SELECT codigo AS id, '.$this->db->concat('codigo', ' - ', 'cuenta').' AS glosa
            FROM lce_cuenta
            WHERE contribuyente = :contribuyente AND activa = true
            ORDER BY
                CHAR_LENGTH(clasificacion), clasificacion,
                CHAR_LENGTH(codigo), codigo
        ', [':contribuyente'=>$this->contribuyente]);
    }

    /**
     * Método que entrega el diccionario de cuentas contables
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-03-05
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
                    CHAR_LENGTH(c.codigo), c.codigo
            ', [':contribuyente'=>$this->contribuyente]);
        } else {
            return $this->db->getTable ('
                SELECT clasificacion, codigo, cuenta, oficial
                FROM lce_cuenta
                WHERE contribuyente = :contribuyente AND activa = true
                ORDER BY
                    CHAR_LENGTH(clasificacion), clasificacion,
                    CHAR_LENGTH(codigo), codigo
            ', [':contribuyente'=>$this->contribuyente]);
        }
    }

    /**
     * Método que obtiene la ayuda para la carga o abono de las cuentas
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-03-05
     */
    public function getAyuda()
    {
        return $this->db->getAssociativeArray('
            SELECT codigo, descripcion, cargos, abonos
            FROM lce_cuenta
            WHERE contribuyente = :contribuyente AND activa = true
            ORDER BY
                CHAR_LENGTH(clasificacion), clasificacion,
                CHAR_LENGTH(codigo), codigo
        ', [':contribuyente'=>$this->contribuyente]);
    }

    /**
     * Método que permite cambiar el código a una cuenta contable
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-03-05
     */
    public function migrar($original, $nuevo)
    {
        $this->db->query('
            UPDATE lce_cuenta
            SET codigo = :nuevo
            WHERE contribuyente = :contribuyente AND codigo = :original
        ', [':contribuyente'=>$this->contribuyente, ':original'=>$original, ':nuevo'=>$nuevo]);
    }

}
