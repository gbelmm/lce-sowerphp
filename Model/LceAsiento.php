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
 * Clase para mapear la tabla lce_asiento de la base de datos
 * Comentario de la tabla: Cabecera de los asientos contables
 * Esta clase permite trabajar sobre un registro de la tabla lce_asiento
 * @author SowerPHP Code Generator
 * @version 2016-03-03 23:08:52
 */
class Model_LceAsiento extends \Model_App
{

    // Datos para la conexión a la base de datos
    protected $_database = 'default'; ///< Base de datos del modelo
    protected $_table = 'lce_asiento'; ///< Tabla del modelo

    // Atributos de la clase (columnas en la base de datos)
    public $contribuyente; ///< RUT del contribuyente sin DV: integer(32) NOT NULL DEFAULT '' PK FK:contribuyente.rut
    public $periodo; ///< Año de la fecha del asiento: smallint(16) NOT NULL DEFAULT '' PK
    public $asiento; ///< Número del asiento dentro del periodo: integer(32) NOT NULL DEFAULT '' PK
    public $fecha; ///< Fecha del hecho económico que se está registrando: date() NOT NULL DEFAULT ''
    public $glosa; ///< Glosa o descripción del hecho económico: text() NOT NULL DEFAULT ''
    public $json; ///< boolean() NOT NULL DEFAULT 'false'
    public $anulado; ///< boolean() NOT NULL DEFAULT 'false'
    public $creado; ///< timestamp without time zone() NOT NULL DEFAULT 'now()'
    public $modificado; ///< timestamp without time zone() NULL DEFAULT ''
    public $usuario; ///< integer(32) NULL DEFAULT '' FK:usuario.id

    // Información de las columnas de la tabla en la base de datos
    public static $columnsInfo = array(
        'contribuyente' => array(
            'name'      => 'Contribuyente',
            'comment'   => 'RUT del contribuyente sin DV',
            'type'      => 'integer',
            'length'    => 32,
            'null'      => false,
            'default'   => '',
            'auto'      => false,
            'pk'        => true,
            'fk'        => array('table' => 'contribuyente', 'column' => 'rut')
        ),
        'periodo' => array(
            'name'      => 'Período',
            'comment'   => 'Año de la fecha del asiento',
            'type'      => 'smallint',
            'length'    => 16,
            'null'      => false,
            'default'   => '',
            'auto'      => false,
            'pk'        => true,
            'fk'        => null
        ),
        'asiento' => array(
            'name'      => 'Asiento',
            'comment'   => 'Número del asiento dentro del periodo',
            'type'      => 'integer',
            'length'    => 32,
            'null'      => false,
            'default'   => '',
            'auto'      => false,
            'pk'        => true,
            'fk'        => null
        ),
        'fecha' => array(
            'name'      => 'Fecha',
            'comment'   => 'Fecha del hecho económico que se está registrando',
            'type'      => 'date',
            'length'    => null,
            'null'      => false,
            'default'   => '',
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),
        'glosa' => array(
            'name'      => 'Glosa',
            'comment'   => 'Glosa o descripción del hecho económico',
            'type'      => 'text',
            'length'    => null,
            'null'      => false,
            'default'   => '',
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),
        'json' => array(
            'name'      => 'Json',
            'comment'   => '',
            'type'      => 'boolean',
            'length'    => null,
            'null'      => false,
            'default'   => 'false',
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),
        'anulado' => array(
            'name'      => 'Anulado',
            'comment'   => '',
            'type'      => 'boolean',
            'length'    => null,
            'null'      => false,
            'default'   => 'false',
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),
        'creado' => array(
            'name'      => 'Creado',
            'comment'   => '',
            'type'      => 'timestamp without time zone',
            'length'    => null,
            'null'      => false,
            'default'   => 'now()',
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),
        'modificado' => array(
            'name'      => 'Modificado',
            'comment'   => '',
            'type'      => 'timestamp without time zone',
            'length'    => null,
            'null'      => true,
            'default'   => '',
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),
        'usuario' => array(
            'name'      => 'Usuario',
            'comment'   => '',
            'type'      => 'integer',
            'length'    => 32,
            'null'      => true,
            'default'   => '',
            'auto'      => false,
            'pk'        => false,
            'fk'        => array('table' => 'usuario', 'column' => 'id')
        ),

    );

    // Comentario de la tabla en la base de datos
    public static $tableComment = 'Cabecera de los asientos contables';

    public static $fkNamespace = array(
        'Model_Contribuyente' => 'website\Dte',
        'Model_Usuario' => '\sowerphp\app\Sistema\Usuarios'
    ); ///< Namespaces que utiliza esta clase

    /**
     * Método que guarda la cabecera del asiento
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-03-03
     */
    public function save()
    {
        // si no hay contribuyente o glosa error
        if (!$this->contribuyente or !$this->glosa) {
            return false;
        }
        // ajustar campos automáticos
        if (!$this->fecha) {
            $this->fecha = date('Y-m-d');
        }
        $this->periodo = (int)substr($this->fecha, 0, 4);
        if (!$this->creado) {
            $this->creado = date('Y-m-d H:i:s');
        }
        if ($this->asiento) {
            $this->modificado = date('Y-m-d H:i:s');
        }
        // probar si puede ser objeto o arreglo, si lo es se codifica
        if (is_object($this->glosa) or is_array($this->glosa)) {
            $this->glosa = json_encode($this->glosa);
            $this->json = 1;
        } else {
            $this->json = (int)(in_array($this->glosa[0], ['{', '[']) and json_decode($this->glosa)!==null);
        }
        // guardar asiento
        $this->db->beginTransaction(true);
        if (!$this->asiento) {
            $this->asiento = $this->db->getValue('
                SELECT MAX(asiento)
                FROM lce_asiento
                WHERE contribuyente = :contribuyente AND periodo = :periodo
            ', [':contribuyente'=>$this->contribuyente, ':periodo'=>$this->periodo]) + 1;
        }
        $status = parent::save();
        $this->db->commit();
        return $status;
    }

    /**
     * Método para obtener el detalle del asiento
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-03-03
     */
    public function getDetalle($json_decode = true)
    {
        $detalle = $this->db->getTable('
            SELECT cuenta, debe, haber, concepto'.($json_decode?', json':'').'
            FROM lce_asiento_detalle
            WHERE contribuyente = :contribuyente AND periodo = :periodo AND asiento = :asiento
        ', [':contribuyente'=>$this->contribuyente, ':periodo'=>$this->periodo, ':asiento'=>$this->asiento]);
        if ($json_decode) {
            foreach ($detalle as &$d) {
                if ($d['json'])
                    $d['concepto'] = json_decode($d['concepto']);
                unset($d['json']);
            }
        }
        return $detalle;
    }

    /**
     * Método para guardar el detalle del asiento
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-03-04
     */
    public function saveDetalle($detalle)
    {
        $this->db->beginTransaction();
        $this->db->query('
            DELETE FROM lce_asiento_detalle
            WHERE contribuyente = :contribuyente AND periodo = :periodo AND asiento = :asiento
        ', [':contribuyente'=>$this->contribuyente, ':periodo'=>$this->periodo, ':asiento'=>$this->asiento]);
        $movimiento = 1;
        foreach ($detalle as &$d) {
            // determinar concepto y si se debe o no serializar
            if (!empty($d['concepto'])) {
                if (is_object($d['concepto']) or is_array($d['concepto'])) {
                    $d['concepto'] = json_encode($d['concepto']);
                    $d['json'] = 1;
                } else {
                    $d['json'] = (int)(in_array($d['concepto'][0], ['{', '[']) and json_decode($d['concepto'])!==null);
                }
            } else {
                $d['concepto'] = null;
                $d['json'] = 0;
            }
            // guardar movimiento del detalle
            $this->db->query('
                INSERT INTO lce_asiento_detalle
                VALUES (:contribuyente, :periodo, :asiento, :movimiento, :cuenta, :debe, :haber, :concepto, :json)
            ', [
                ':contribuyente' => $this->contribuyente,
                ':periodo' => $this->periodo,
                ':asiento' => $this->asiento,
                ':movimiento' => $movimiento++,
                ':cuenta' => $d['cuenta'],
                ':debe' => !empty($d['debe']) ? $d['debe'] : null,
                ':haber' => !empty($d['haber']) ? $d['haber'] : null,
                ':concepto' => $d['concepto'],
                ':json' => $d['json'],
            ]);
        }
        return $this->db->commit();
    }

}
