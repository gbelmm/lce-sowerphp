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
 * Comentario de la tabla:
 * Esta clase permite trabajar sobre un registro de la tabla lce_cuenta_oficial
 * @author SowerPHP Code Generator
 * @version 2016-03-04 22:28:48
 */
class Model_LceCuentaOficial extends \Model_App
{

    // Datos para la conexión a la base de datos
    protected $_database = 'default'; ///< Base de datos del modelo
    protected $_table = 'lce_cuenta_oficial'; ///< Tabla del modelo

    // Atributos de la clase (columnas en la base de datos)
    public $codigo; ///< character varying(16) NOT NULL DEFAULT '' PK
    public $cuenta; ///< character varying(120) NOT NULL DEFAULT ''
    public $clasificacion; ///< smallint(16) NOT NULL DEFAULT ''

    // Información de las columnas de la tabla en la base de datos
    public static $columnsInfo = array(
        'codigo' => array(
            'name'      => 'Codigo',
            'comment'   => '',
            'type'      => 'character varying',
            'length'    => 16,
            'null'      => false,
            'default'   => '',
            'auto'      => false,
            'pk'        => true,
            'fk'        => null
        ),
        'cuenta' => array(
            'name'      => 'Cuenta',
            'comment'   => '',
            'type'      => 'character varying',
            'length'    => 120,
            'null'      => false,
            'default'   => '',
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),
        'clasificacion' => array(
            'name'      => 'Clasificacion',
            'comment'   => '',
            'type'      => 'smallint',
            'length'    => 16,
            'null'      => false,
            'default'   => '',
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),

    );

    // Comentario de la tabla en la base de datos
    public static $tableComment = '';

    public static $fkNamespace = array(); ///< Namespaces que utiliza esta clase

    public function __construct($codigo = null)
    {
        parent::__construct($codigo);
        $this->lce_cuenta_oficial = &$this->cuenta;
    }

}
