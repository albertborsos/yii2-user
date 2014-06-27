<?php
/**
 * Created by PhpStorm.
 * User: borsosalbert
 * Date: 2014.04.28.
 * Time: 13:46
 */

namespace vendor\albertborsos\user\forms;

use vendor\albertborsos\user\models\UserDetails;
use vendor\albertborsos\user\models\Users;
use Yii;
use yii\base\Exception;
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
            ['email', 'unique', 'targetClass' => 'vendor\albertborsos\user\models\Users', 'message' => 'Ezzel az emailcímmel már regisztráltak!'],
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

    public function register(){
        if ($this->validate()){
            $transaction = Yii::$app->db->beginTransaction();
            try {
                // ha ok, akkor mentjük
                $user = new Users();
                $user->email = $this->email;
                $user->setPassword($this->password);
                $user->status = $user::STATUS_INACTIVE;

                if ($user->save()) {
                    $userdetails = new UserDetails();
                    $userdetails->user_id = $user->id;
                    $userdetails->name_first = $this->firstName;
                    $userdetails->name_last = $this->lastName;
                    $userdetails->status = $user::STATUS_INACTIVE;

                    if ($userdetails->save()) {
                        $transaction->commit();
                        return $user;
                    } else {
                        $this->addError('email', 'Nem sikerült menteni a felhasználóadatokat!');
                        return false;
                    }
                } else {
                    $this->addError('email', 'Nem sikerült menteni a felhasználót');
                    return false;
                }
            } catch (Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
                return false;
            }
        }else{
            return false;
        }
    }
} 