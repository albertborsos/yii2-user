<?php
namespace albertborsos\yii2user\forms;

use albertborsos\yii2user\models\Users;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class SetNewPasswordForm extends Model
{
    public $email;
    public $password;
    public $password_again;

    private $_user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['email', 'password', 'password_again'], 'required'],
            // rememberMe must be a boolean value
            // password is validated by validatePassword()
            [['password', 'password_again'], 'validatePassword'],
            [['password', 'password_again'], 'string', 'min' => 8],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => 'E-mail cím',
            'password' => 'Új jelszó',
            'password_again' => 'Új jelszó mégegyszer',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validatePassword()
    {
        if (!$this->hasErrors()) {
            if ($this->password !== $this->password_again){
                $this->addError('password', 'A két jelszó nem egyezik meg!');
                $this->addError('password_again', 'A két jelszó nem egyezik meg!');
            }
        }
    }

    /**
     * Finds user by [[email]]
     *
     * @return Users|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Users::findByEmail($this->email);
        }

        return $this->_user;
    }
}
