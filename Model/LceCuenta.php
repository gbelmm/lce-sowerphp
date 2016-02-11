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
 * Esta clase permite trabajar sobre un registro de la tabla lce_cuenta
 * @author SowerPHP Code Generator
 * @version 2016-02-08 01:50:20
 */
class Model_LceCuenta extends \Model_App
{

    // Datos para la conexión a la base de datos
    protected $_database = 'default'; ///< Base de datos del modelo
    protected $_table = 'lce_cuenta'; ///< Tabla del modelo

    // Atributos de la clase (columnas en la base de datos)
    public $contribuyente; ///< RUT del contribuyente sin DV: integer(32) NOT NULL DEFAULT '' PK FK:contribuyente.rut
    public $codigo; ///< Código de la cuenta (recomendado jerárquico): character varying(20) NOT NULL DEFAULT '' PK
    public $cuenta; ///< Nombre corto de la cuenta: character varying(120) NOT NULL DEFAULT ''
    public $clasificacion; ///< Clasificación de la cuenta (Activo, Pasivo, Patrimonio o Resultado): character varying(3) NOT NULL DEFAULT '' FK:lce_cuenta_clasificacion.codigo
    public $subclasificacion; ///< Clasificación dentro de las de mayor jerarquía, por ejemplo Activo Circulante: character varying(3) NOT NULL DEFAULT '' FK:lce_cuenta_clasificacion.codigo
    public $oficial; ///< Correspondencia de esta cuenta con una cuenta oficial del SII (para confección de diccionario de cuentas): character varying(16) NULL DEFAULT '' FK:lce_cuenta_oficial.codigo
    public $descripcion; ///< Descripción de la cuenta: text() NOT NULL DEFAULT ''
    public $cargos; ///< Cuando se debe hacer un cargo a la cuenta: text() NULL DEFAULT ''
    public $abonos; ///< Cuando se debe hacer un abono a la cuenta: text() NULL DEFAULT ''
    public $saldo_deudor; ///< Que representa el saldo deudor de la cuenta: text() NULL DEFAULT ''
    public $saldo_acreedor; ///< Que representa el saldo acreedor de la cuenta: text() NULL DEFAULT ''
    public $activa; ///< Indica si la cuenta se puede o no usar: boolean() NOT NULL DEFAULT 'true'

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
        'codigo' => array(
            'name'      => 'Código',
            'comment'   => 'Código de la cuenta (recomendado jerárquico)',
            'type'      => 'character varying',
            'length'    => 20,
            'null'      => false,
            'default'   => '',
            'auto'      => false,
            'pk'        => true,
            'fk'        => null
        ),
        'cuenta' => array(
            'name'      => 'Cuenta',
            'comment'   => 'Nombre corto de la cuenta',
            'type'      => 'character varying',
            'length'    => 120,
            'null'      => false,
            'default'   => '',
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),
        'clasificacion' => array(
            'name'      => 'Clasificación',
            'comment'   => 'Clasificación de la cuenta (Activo, Pasivo, Patrimonio o Resultado)',
            'type'      => 'character varying',
            'length'    => 3,
            'null'      => false,
            'default'   => '',
            'auto'      => false,
            'pk'        => false,
            'fk'        => array('table' => 'lce_cuenta_clasificacion', 'column' => 'codigo')
        ),
        'subclasificacion' => array(
            'name'      => 'Subclasificacion',
            'comment'   => 'Clasificación dentro de las de mayor jerarquía, por ejemplo Activo Circulante',
            'type'      => 'character varying',
            'length'    => 3,
            'null'      => false,
            'default'   => '',
            'auto'      => false,
            'pk'        => false,
            'fk'        => array('table' => 'lce_cuenta_clasificacion', 'column' => 'codigo')
        ),
        'oficial' => array(
            'name'      => 'Oficial',
            'comment'   => 'Correspondencia de esta cuenta con una cuenta oficial del SII (para confección de diccionario de cuentas)',
            'type'      => 'character varying',
            'length'    => 16,
            'null'      => true,
            'default'   => '',
            'auto'      => false,
            'pk'        => false,
            'fk'        => array('table' => 'lce_cuenta_oficial', 'column' => 'codigo')
        ),
        'descripcion' => array(
            'name'      => 'Descripción',
            'comment'   => 'Descripción de la cuenta',
            'type'      => 'text',
            'length'    => null,
            'null'      => false,
            'default'   => '',
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),
        'cargos' => array(
            'name'      => 'Cargos',
            'comment'   => 'Cuando se debe hacer un cargo a la cuenta',
            'type'      => 'text',
            'length'    => null,
            'null'      => true,
            'default'   => '',
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),
        'abonos' => array(
            'name'      => 'Abonos',
            'comment'   => 'Cuando se debe hacer un abono a la cuenta',
            'type'      => 'text',
            'length'    => null,
            'null'      => true,
            'default'   => '',
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),
        'saldo_deudor' => array(
            'name'      => 'Saldo Deudor',
            'comment'   => 'Que representa el saldo deudor de la cuenta',
            'type'      => 'text',
            'length'    => null,
            'null'      => true,
            'default'   => '',
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),
        'saldo_acreedor' => array(
            'name'      => 'Saldo Acreedor',
            'comment'   => 'Que representa el saldo acreedor de la cuenta',
            'type'      => 'text',
            'length'    => null,
            'null'      => true,
            'default'   => '',
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),
        'activa' => array(
            'name'      => 'Activa',
            'comment'   => 'Indica si la cuenta se puede o no usar',
            'type'      => 'boolean',
            'length'    => null,
            'null'      => false,
            'default'   => 'true',
            'auto'      => false,
            'pk'        => false,
            'fk'        => null
        ),

    );

    // Comentario de la tabla en la base de datos
    public static $tableComment = 'Plan de cuentas de la empresa (por ejemplo plan de cuentas MiPyme SII)';

    public static $fkNamespace = array(
        'Model_Contribuyente' => 'website\Dte',
        'Model_LceCuentaClasificacion' => 'website\Lce\Admin\Mantenedores',
        'Model_LceCuentaOficial' => 'website\Lce\Admin\Mantenedores'
    ); ///< Namespaces que utiliza esta clase

    /**
     * Método que indica si la cuenta está o no siendo usada en algún asiento
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-02-09
     */
    public function enUso()
    {
        return (bool)$this->db->getValue('
            SELECT COUNT(*)
            FROM lce_asiento_detalle
            WHERE contribuyente = :contribuyente AND cuenta = :cuenta
        ', [':contribuyente'=>$this->contribuyente, ':cuenta'=>$this->codigo]);
    }

}
