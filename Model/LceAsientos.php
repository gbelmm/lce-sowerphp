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
 * Comentario de la tabla:
 * Esta clase permite trabajar sobre un conjunto de registros de la tabla lce_asiento
 * @author SowerPHP Code Generator
 * @version 2016-03-04 22:54:48
 */
class Model_LceAsientos extends \Model_Plural_App
{

    // Datos para la conexión a la base de datos
    protected $_database = 'default'; ///< Base de datos del modelo
    protected $_table = 'lce_asiento'; ///< Tabla del modelo

    /**
     * Método que entrega el listado de períodos del contribuyente
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2016-03-04
     */
    public function getPeriodos()
    {
        return $this->db->getAssociativeArray('
            SELECT DISTINCT periodo AS id, periodo AS glosa
            FROM lce_asiento
            WHERE contribuyente = :contribuyente
            ORDER BY periodo DESC
        ', [':contribuyente'=>$this->contribuyente]);
    }

    /**
     * Método que entrega los asientos para confeccionar el libro diario
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2016-03-05
     */
    public function getLibroDiario($desde, $hasta)
    {
        $asientos = \sowerphp\core\Utility_Array::fromTableWithHeaderAndBody($this->db->getTable('
            SELECT
                a.asiento AS asiento,
                a.fecha,
                a.glosa,
                a.creado,
                a.modificado,
                u.usuario,
                c.cuenta,
                ad.debe,
                ad.haber
            FROM
                lce_asiento AS a
                LEFT JOIN usuario AS u ON a.usuario = u.id,
                lce_asiento_detalle AS ad,
                lce_cuenta AS c
            WHERE
                ad.periodo = a.periodo
                AND ad.asiento = a.asiento
                AND ad.cuenta = c.codigo
                AND a.contribuyente = :contribuyente
                AND a.fecha BETWEEN :desde AND :hasta
            ORDER BY a.periodo, a.asiento, ad.movimiento
        ', [':contribuyente'=>$this->contribuyente, ':desde'=>$desde, ':hasta'=>$hasta]), 6);
        foreach ($asientos as &$asiento) {
            $asiento['debito'] = 0;
            $asiento['credito'] = 0;
            foreach ($asiento['detalle'] as $d) {
                $asiento['debito'] += (int)$d['debe'];
                $asiento['credito'] += (int)$d['haber'];
            }
        }
        return $asientos;
    }

    /**
     * Método que entrega las cuentas para construir el libro mayor
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2016-03-05
     */
    public function getLibroMayor($desde, $hasta)
    {
        $aux = \sowerphp\core\Utility_Array::fromTableWithHeaderAndBody($this->db->getTable('
            SELECT c.cuenta, ad.debe, ad.haber
            FROM lce_asiento AS a, lce_asiento_detalle AS ad, lce_cuenta AS c
            WHERE
                ad.periodo = a.periodo
                AND ad.asiento = a.asiento
                AND ad.cuenta = c.codigo
                AND a.contribuyente = :contribuyente
                AND a.fecha BETWEEN :desde AND :hasta
            ORDER BY
                CHAR_LENGTH(c.clasificacion), c.clasificacion,
                CHAR_LENGTH(c.codigo), c.codigo,
                a.periodo,
                a.asiento
        ', [':contribuyente'=>$this->contribuyente, ':desde'=>$desde, ':hasta'=>$hasta]), 1);
        $cuentas = [];
        foreach ($aux as &$a) {
            $debe = [];
            $haber = [];
            foreach ($a['detalle'] as &$d) {
                if (!empty($d['debe']))
                    $debe[] = $d['debe'];
                if (!empty($d['haber']))
                    $haber[] = $d['haber'];
            }
            $cuenta = [
                'cuenta' => $a['cuenta'],
                'detalle' => \sowerphp\core\Utility_Array::groupToTable([
                    'debe' => $debe,
                    'haber' => $haber,
                ]),
                'saldo_deudor' => 0,
                'saldo_acreedor' => 0,
            ];
            foreach ($cuenta['detalle'] as &$d) {
                $cuenta['saldo_deudor'] += (int)$d['debe'];
                $cuenta['saldo_acreedor'] += (int)$d['haber'];
            }
            if ($cuenta['saldo_deudor']>$cuenta['saldo_acreedor']) {
                $cuenta['saldo_deudor'] -= $cuenta['saldo_acreedor'];
                $cuenta['saldo_acreedor'] = '';
            } else if ($cuenta['saldo_acreedor']>$cuenta['saldo_deudor']) {
                $cuenta['saldo_acreedor'] -= $cuenta['saldo_deudor'];
                $cuenta['saldo_deudor'] = '';
            } else {
                $cuenta['saldo_deudor'] = $cuenta['saldo_acreedor'] = 0;
            }
            $cuentas[] = $cuenta;
        }
        return $cuentas;
    }

    /**
     * Método que construye el balance general
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2016-03-05
     */
    public function getBalanceGeneral($periodo)
    {
        $balance = $this->db->getTable ('
            SELECT
                b.cuenta,
                b.debitos,
                b.creditos,
                b.saldo_deudor,
                b.saldo_acreedor,
                CASE WHEN SUBSTR(b.clasificacion,1,1) IN (\'1\', \'2\', \'3\') AND b.saldo_deudor>0 THEN
                    saldo_deudor
                ELSE NULL END AS activo,
                CASE WHEN SUBSTR(b.clasificacion,1,1) IN (\'1\', \'2\', \'3\') AND b.saldo_acreedor>0 THEN
                    saldo_acreedor
                ELSE NULL END AS pasivo,
                CASE WHEN SUBSTR(b.clasificacion,1,1) IN (\'4\') AND b.saldo_deudor>0 THEN
                    saldo_deudor
                ELSE NULL END AS perdidas,
                CASE WHEN SUBSTR(b.clasificacion,1,1) IN (\'4\') AND b.saldo_acreedor>0 THEN
                    saldo_acreedor
                ELSE NULL END AS ganancias
            FROM (
                SELECT
                    c.cuenta,
                    c.clasificacion,
                    SUM(ad.debe) AS debitos,
                    SUM(ad.haber) AS creditos,
                    CASE
                        WHEN SUM(ad.debe)>0 AND SUM(ad.haber)>0 THEN
                            CASE WHEN SUM(ad.debe)>SUM(ad.haber) THEN
                                SUM(ad.debe) - SUM(ad.haber)
                            ELSE
                                NULL
                            END
                        WHEN SUM(ad.debe)>0 THEN
                            SUM(ad.debe)
                        ELSE
                            NULL
                    END AS saldo_deudor,
                    CASE
                        WHEN SUM(ad.debe)>0 AND SUM(ad.haber)>0 THEN
                            CASE WHEN SUM(ad.haber)>SUM(ad.debe) THEN
                                SUM(ad.haber) - SUM(ad.debe)
                            ELSE
                                NULL
                            END
                        WHEN SUM(ad.haber)>0 THEN
                            SUM(ad.haber)
                        ELSE
                            NULL
                    END AS saldo_acreedor
                FROM lce_asiento AS a, lce_asiento_detalle AS ad, lce_cuenta AS c
                WHERE
                    ad.periodo = a.periodo
                    AND ad.asiento = a.asiento
                    AND ad.cuenta = c.codigo
                    AND a.contribuyente = :contribuyente
                    AND a.periodo = :periodo
                GROUP BY c.codigo, c.cuenta, c.clasificacion
                ORDER BY CHAR_LENGTH(c.codigo), c.codigo
            ) AS b
        ', [':contribuyente'=>$this->contribuyente, ':periodo'=>$periodo]);
        // determinar sumas parciales
        $sumas_parciales = [
            'debitos'=>0,
            'creditos'=>0,
            'saldo_deudor'=>0,
            'saldo_acreedor'=>0,
            'activo'=>0,
            'pasivo'=>0,
            'perdidas'=>0,
            'ganancias'=>0
        ];
        foreach ($balance as &$cuenta) {
            $sumas_parciales['debitos'] += (int)$cuenta['debitos'];
            $sumas_parciales['creditos'] += (int)$cuenta['creditos'];
            $sumas_parciales['saldo_deudor'] += (int)$cuenta['saldo_deudor'];
            $sumas_parciales['saldo_acreedor'] += (int)$cuenta['saldo_acreedor'];
            $sumas_parciales['activo'] += (int)$cuenta['activo'];
            $sumas_parciales['pasivo'] += (int)$cuenta['pasivo'];
            $sumas_parciales['perdidas'] += (int)$cuenta['perdidas'];
            $sumas_parciales['ganancias'] += (int)$cuenta['ganancias'];
        }
        // determinar resultados
        $resultados = array();
        if ($sumas_parciales['activo']>$sumas_parciales['pasivo']) {
            $resultados['activo'] = '';
            $resultados['pasivo'] = $sumas_parciales['activo'] - $sumas_parciales['pasivo'];
        }
        else if ($sumas_parciales['pasivo']>$sumas_parciales['activo']) {
            $resultados['activo'] = $sumas_parciales['pasivo'] - $sumas_parciales['activo'];
            $resultados['pasivo'] = '';
        }
        else {
            $resultados['activo'] = $resultados['pasivo'] = '';
        }
        if ($sumas_parciales['perdidas']>$sumas_parciales['ganancias']) {
            $resultados['perdidas'] = '';
            $resultados['ganancias'] = $sumas_parciales['perdidas'] - $sumas_parciales['ganancias'];
        } else if ($sumas_parciales['ganancias']>$sumas_parciales['perdidas']) {
            $resultados['perdidas'] = $sumas_parciales['ganancias'] - $sumas_parciales['perdidas'];
            $resultados['ganancias'] = '';
        } else {
            $resultados['perdidas'] = $resultados['ganancias'] = '';
        }
        // determinar suma total
        $suma_total = array();
        foreach ($sumas_parciales as $col => &$valor) {
            if (isset($resultados[$col]))
                $suma_total[$col] = $sumas_parciales[$col] + $resultados[$col];
            else
                $suma_total[$col] = $sumas_parciales[$col];
        }
        return compact('balance', 'sumas_parciales', 'resultados', 'suma_total');
    }

    /**
     * Método que ordena los asientos de un periodo por fecha ascendentemente
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]sasco.cl)
     * @version 2016-03-05
     */
    public function ordenar($periodo)
    {
        $this->db->beginTransaction();
        // obtener el número de asiento mayor
        $offset = $this->db->getValue('
            SELECT MAX(asiento)
            FROM lce_asiento
            WHERE contribuyente = :contribuyente AND periodo = :periodo
        ', [':contribuyente'=>$this->contribuyente, ':periodo'=>$periodo]);
        // mover los asientos desde el mayor en adelante (para hacer espacio al
        // inicio)
        $this->db->query('
            UPDATE lce_asiento
            SET asiento = (asiento + '.$offset.')
            WHERE contribuyente = :contribuyente AND periodo = :periodo
        ', [':contribuyente'=>$this->contribuyente, ':periodo'=>$periodo]);
        // obtener asientos ordenados por fecha y creación
        $asientos = $this->db->getCol('
            SELECT asiento
            FROM lce_asiento
            WHERE contribuyente = :contribuyente AND periodo = :periodo
            ORDER BY fecha, creado
        ', [':contribuyente'=>$this->contribuyente, ':periodo'=>$periodo]);
        // mover los asientos uno a uno a su nueva ubicación
        $numero = 1;
        foreach ($asientos as $asiento) {
            $this->db->query('
                UPDATE lce_asiento
                SET asiento = :nuevo
                WHERE contribuyente = :contribuyente AND periodo = :periodo AND asiento = :original
            ', [':contribuyente'=>$this->contribuyente, ':periodo'=>$periodo, ':original'=>$asiento, ':nuevo'=>$numero++]);
        }
        return $this->db->commit();
    }

}
