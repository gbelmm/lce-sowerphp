<?php

/**
 * LibreDTE
 * Copyright (C) SASCO SpA (https://sasco.cl)
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

// título del módulo
\sowerphp\core\Configure::write('module.title', 'Contabilidad electrónica');

// Menú para el módulo
\sowerphp\core\Configure::write('nav.module', [
    '/lce_asientos/listar/1/creado/D?search=anulado:0' => [
        'name' => 'Asientos contables',
        'desc' => 'Mantenedor de hechos económicos diarios',
        'icon' => 'fa fa-dollar',
    ],
    '/lce_asientos/crear?listar=LzEvY3JlYWRvL0Q/c2VhcmNoPWNvbnRyaWJ1eWVudGU6NzYxOTIwODMsYW51bGFkbzow' => [
        'name' => 'Crear asiento',
        'desc' => 'Registrar un nuevo hecho económico',
        'icon' => 'fa fa-edit',
    ],
    '/libro_diario' => [
        'name' => 'Libro diario',
        'desc' => 'Buscar asientos contables en libro diario',
        'icon' => 'fa fa-book',
    ],
    '/libro_mayor' => [
        'name' => 'Libro mayor',
        'desc' => 'Buscar saldos de cuentas contables en libro mayor',
        'icon' => 'fa fa-book',
    ],
    '/balance_general' => [
        'name' => 'Balance general',
        'desc' => 'Revisar balance general',
        'icon' => 'fa fa-balance-scale',
    ],
    '/lce_cuentas/diccionario' => [
        'name' => 'Diccionario',
        'desc' => 'Diccionario de cuentas contables',
        'icon' => 'fa fa-list-alt',
    ],
    '/lce_cuentas/listar/1/codigo/A?search=activa:1' => [
        'name' => 'Cuentas contables',
        'desc' => 'Mantenedor de cuentas contables',
        'icon' => 'fa fa-list',
    ],
    '/admin' => [
        'name' => 'Administración',
        'desc' => 'Administración del módulo de contabilidad',
        'icon' => 'fa fa-cogs',
    ],
]);
