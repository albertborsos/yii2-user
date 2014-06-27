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

class ReminderForm extends Model {

    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'exist',
                'targetClass' => 'vendor\albertborsos\user\models\Users',
                'filter' => ['status' => Users::STATUS_ACTIVE],
                'message' => 'Nincs ilyen e-mailcím a rendszerben, vagy még nem aktiváltad a fiókod!',
            ],
            ['email', 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => 'E-mail cím',
        ];
    }
} 