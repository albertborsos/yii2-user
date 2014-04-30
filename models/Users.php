<?php

namespace vendor\albertborsos\user\models;

use Yii;
use yii\base\ModelEvent;
use yii\db\BaseActiveRecord;
use yii\helpers\Security;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "tbl_user_users".
 *
 * @property string $id
 * @property string $email
 * @property string $password_hash
 * @property string $auth_key
 * @property string $password_reset_token
 * @property string $username
 * @property string $created_at
 * @property string $activated_at
 * @property string $updated_at
 * @property string $status
 */
class Users extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_user_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'activated_at', 'updated_at'], 'safe'],
            [['email', 'password_hash', 'auth_key', 'password_reset_token'], 'string', 'max' => 255],
            [['username'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'E-mail cím',
            'password_hash' => 'Jelszó',
            'auth_key' => 'Authentikációs kulcs',
            'password_reset_token' => 'Jelszóemlékeztető kulcs',
            'username' => 'Felhasználónév',
            'created_at' => 'Regisztráció ideje',
            'activated_at' => 'Aktiválás ideje',
            'updated_at' => 'Módosítás ideje',
            'status' => 'Státusz',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                //ha uj rekord
                $this->created_at    = date('Y-m-d H:i:s');
                $this->password_hash = Security::generatePasswordHash($this->password_hash);
                $this->auth_key      = Security::generateRandomKey();
            } else {
                // meglévő rekord
                $this->updated_at = date('Y-m-d H:i:s');
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns a Users object
     * or null
     *
     * @param $email
     * @return static
     */
    public static function getUserByEmail($email){
        return self::findOne([
                'email' => $email,
                'status' => 'a',
            ]);
     }

    /**
     * Validates password
     *
     * @param $password
     * @return bool
     */
    public function validatePassword($password){
        return Security::validatePassword($password, $this->password_hash);
    }

    /**
     * Finds an identity by the given ID.
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return Users::findOne($id);
    }

    /**
     * Finds an identity by the given secrete token.
     * @param string $token the secrete token
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token)
    {
        return static::findOne(['auth_key' => $token]);
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|integer an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->email;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return boolean whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
}
