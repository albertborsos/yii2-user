<?php
/**
 * Created by PhpStorm.
 * User: borsosalbert
 * Date: 2014.04.28.
 * Time: 13:46
 */

namespace vendor\albertborsos\user\forms;

use Yii;
use yii\base\Model;

class RegisterForm extends Model {

    public $firstName;
    public $lastName;
    public $email;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['firstName', 'required'],
            ['firstName', 'string'],

            ['lastName', 'required'],
            ['lastName', 'string'],


            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],

            ['password', 'required'],
            ['password', 'string', 'min' => 8],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'lastName' => 'Vezetéknév',
            'firstName' => 'Keresztnév',
            'email' => 'E-mail cím',
            'password' => 'Jelszó',
        ];
    }
} 