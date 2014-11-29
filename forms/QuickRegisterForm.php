<?php
/**
 * Created by PhpStorm.
 * User: borsosalbert
 * Date: 2014.04.28.
 * Time: 13:46
 */

namespace albertborsos\yii2user\forms;

use albertborsos\yii2lib\helpers\S;
use albertborsos\yii2user\models\UserDetails;
use albertborsos\yii2user\models\Users;
use Yii;
use yii\base\Exception;
use yii\base\Model;

class QuickRegisterForm extends Model {

    public $firstName;
    public $lastName;
    public $email;

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
            ['email', 'unique', 'targetClass' => 'albertborsos\yii2user\models\Users', 'message' => 'Ezzel az emailcímmel már regisztráltak!'],
            ['email', 'required'],
            ['email', 'email'],
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
        ];
    }

    public function register(){
        if ($this->validate()){
            $transaction = Yii::$app->db->beginTransaction();
            try {
                // ha ok, akkor mentjük
                $user = new Users();
                $user->email = $this->email;
                $user->status = $user::STATUS_ACTIVE;

                if ($user->save()) {
                    $userdetails = $user->getDetails();
                    $userdetails->user_id = $user->id;
                    $userdetails->name_first = $this->firstName;
                    $userdetails->name_last = $this->lastName;
                    $userdetails->status = $user::STATUS_ACTIVE;

                    if ($userdetails->save()) {
                        $transaction->commit();
                        $user->sendInfoMail();
                        return true;
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