<?php

/**
 * Copyright (c) Vincent Klaiber.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://github.com/vinkla/laravel-hashids
 */

use Crater\Models\Company;
use Crater\Models\Estimate;
use Crater\Models\Invoice;
use Crater\Models\Credit;
use Crater\Models\Payment;
use Crater\Models\Proforma;
use Crater\Models\Purchase;
use Crater\Models\Debit;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the connections below you wish to use as
    | your default connection for all work. Of course, you may use many
    | connections at once using the manager class.
    |
    */

    'default' => 'main',

    /*
    |--------------------------------------------------------------------------
    | Hashids Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the connections setup for your application. Example
    | configuration has been included, but you may add as many connections as
    | you would like.
    |
    */

    'connections' => [
        Invoice::class => [
            'salt' => Invoice::class.config('app.key'),
            'length' => '20',
            'alphabet' => 'XKyIAR7mgt8jD2vbqPrOSVenNGpiYLx4M61T',
        ],
        Credit::class => [
            'salt' => Credit::class.config('app.key'),
            'length' => '20',
            'alphabet' => 'AKyIA87KgDdjDJvbqP5OSVhnAGHiYLx4M61M',
        ],
        Proforma::class => [
            'salt' => Performa::class.config('app.key'),
            'length' => '20',
            'alphabet' => 'AKyIQ89KgDqjDJvbqO5OGVhnAGHiQLx4V11M',
        ],
        Estimate::class => [
            'salt' => Estimate::class.config('app.key'),
            'length' => '20',
            'alphabet' => 'yLJWP79M8rYVqbn1NXjulO6IUDdvekRQGo40',
        ],
        Payment::class => [
            'salt' => Payment::class.config('app.key'),
            'length' => '20',
            'alphabet' => 'asqtW3eDRIxB65GYl7UVLS1dybn9XrKTZ4zO',
        ],
        Company::class => [
            'salt' => Company::class.config('app.key'),
            'length' => '20',
            'alphabet' => 's0DxOFtEYEnuKPmP08Ch6A1iHlLmBTBVWms5',
        ],
        Purchase::class => [
            'salt' => Purchase::class.config('app.key'),
            'length' => '20',
            'alphabet' => 'A5P2OFtEYEnuLKmP08Ch6A1iHlLmBM5VWms5',
        ],
        Debit::class => [
            'salt' => Debit::class.config('app.key'),
            'length' => '20',
            'alphabet' => '7ABVxmD0ZkeWFTDmYy1eAwAeYfBhtNNoRsIl',
        ],
    ],

];