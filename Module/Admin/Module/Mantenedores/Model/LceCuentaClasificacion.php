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
 * Esta clase permite trabajar sobre un registro de la tabla lce_cuenta_clasificacion
 * @author SowerPHP Code Generator
 * @version 2016-02-08 01:44:34
 */
class Model_LceCuentaClasificacion extends \Model_App
{

    // Datos para la conexión a la base de datos
    protected $_database = 'default'; ///< Base de datos del modelo
    protected $_table = 'lce_cuenta_clasificacion'; ///< Tabla del modelo

    // Atributos de la clase (columnas en la base de datos)
    public $codigo; ///< Código de la clasificación: character varying(3) NOT NULL DEFAULT '' PK
    public $clasificacion; ///< Nombre de la clasificación: character varying(50) NOT NULL DEFAULT ''
    public $superior; ///< Clasificación superior en caso de tener una: character varying(3) NULL DEFAULT '' FK:lce_cuenta_clasificacion.codigo
    public $descripcion; ///< Descripción de la clasificación (tipo de cuentas que contiene): text() NULL DEFAULT ''

    // Información de las columnas de la tabla en la base de datos
    public static $columnsInfo = array(
        'codigo' => array(
            'name'      => 'Codigo',
            'comment'   => 'Código de la clasificación',
            'type'      => 'character varying',
            'length'    => 3,
            'null'      => false,
            'default'   => '',
            'auto'      => false,
            'pk'        => true,
            'fk'        => null
        ),
        'clasificacion' => array(
            'name'      => 'Clasificacion',
            'comment'   => 'Nombre de la clasificación',
            'type'      => 'character varying',
            'length'    => 50,
            'null'      => false,
            'default'   => '',
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),
        'superior' => array(
            'name'      => 'Superior',
            'comment'   => 'Clasificación superior en caso de tener una',
            'type'      => 'character varying',
            'length'    => 3,
            'null'      => true,
            'default'   => '',
            'auto'      => false,
            'pk'        => false,
            'fk'        => array('table' => 'lce_cuenta_clasificacion', 'column' => 'codigo')
        ),
        'descripcion' => array(
            'name'      => 'Descripcion',
            'comment'   => 'Descripción de la clasificación (tipo de cuentas que contiene)',
            'type'      => 'text',
            'length'    => null,
            'null'      => true,
            'default'   => '',
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),

    );

    // Comentario de la tabla en la base de datos
    public static $tableComment = 'Clasificación y subclasificación de cuentas contables';

    public static $fkNamespace = array(
        'Model_LceCuentaClasificacion' => 'website\Lce\Admin\Mantenedores'
    ); ///< Namespaces que utiliza esta clase

    public function __construct($codigo = null)
    {
        parent::__construct($codigo);
        $this->lce_cuenta_clasificacion = &$this->clasificacion;
    }

}
