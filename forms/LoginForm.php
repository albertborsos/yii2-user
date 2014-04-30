<?php
/**
 * Created by PhpStorm.
 * User: borsosalbert
 * Date: 2014.04.28.
 * Time: 13:46
 */

namespace vendor\albertborsos\user\forms;

use vendor\albertborsos\user\models\Users;
use Yii;
use yii\base\Model;
use yii\helpers\Security;

class LoginForm extends Model {

    public $email;
    public $password;

    private $_user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => 'E-mail cím',
            'password' => 'Jelszó',
        ];
    }


    public function login(){

        if ($this->validate()){
            return Yii::$app->user->login($this->getUser(), 3600 * 24 * 30); // 30 days
        }else{
            return false;
        }
    }

    /**
     * @return Users
     */
    public function getUser(){
        if ($this->_user === false){
            $this->_user = Users::getUserByEmail($this->email);
        }

        return $this->_user;
    }

    public function validatePassword(){
        if (!$this->hasErrors()){
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)){
                $this->addError('password', 'Nem megfelelő jelszót adtál meg!');
            }
        }
    }
} 