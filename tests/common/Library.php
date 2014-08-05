<?php
/**
 * Created by PhpStorm.
 * User: borsosalbert
 * Date: 2014.04.30.
 * Time: 16:01
 */
namespace common;

use \WebGuy;
use Yii;

class Library {

    public static $users = [
        'register' => [
            'ok' => [
                'firstname' => 'Albert',
                'lastname'  => 'Borsos',
                'email'     => 'noreply@borsosalbert.hu',
                'password'  => 'jelszó10',
            ],
        ],
        'existing' => [
            'albert' => [
                'firstname' => 'Albert',
                'lastname'  => 'Borsos',
                'email'     => 'albertborsos@github.com',
                'password'  => 'jelszó10',
            ],
        ],
    ];
} 