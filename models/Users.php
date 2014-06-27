<?php
    namespace albertborsos\yii2user\models;

    use yii\base\NotSupportedException;
    use yii\db\ActiveRecord;
    use yii\helpers\Security;
    use yii\web\IdentityInterface;

    /**
     * User model
     *
     * @property integer $id
     * @property string $username
     * @property string $password_hash
     * @property string $password_reset_token
     * @property string $email
     * @property string $auth_key
     * @property integer $role
     * @property integer $status
     * @property integer $created_at
     * @property integer $updated_at
     * @property string $password write-only password
     */
    class Users extends ActiveRecord implements IdentityInterface {
        const STATUS_ACTIVE   = 'a';
        const STATUS_INACTIVE = 'i';
        const STATUS_DELETED  = 'd';

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
                'id'                   => 'ID',
                'email'                => 'E-mail cím',
                'password_hash'        => 'Jelszó',
                'auth_key'             => 'Authentikációs kulcs',
                'password_reset_token' => 'Jelszóemlékeztető kulcs',
                'username'             => 'Felhasználónév',
                'created_at'           => 'Regisztráció ideje',
                'activated_at'         => 'Aktiválás ideje',
                'updated_at'           => 'Módosítás ideje',
                'status'               => 'Státusz',
            ];
        }

        public function beforeSave($insert)
        {
            if (parent::beforeSave($insert)) {
                if ($this->isNewRecord) {
                    //ha uj rekord
                    $this->created_at = date('Y-m-d H:i:s');
                    $this->auth_key   = Security::generateRandomKey();
                } else {
                    // meglévő rekord
                    $this->updated_at = date('Y-m-d H:i:s');
                }

                return true;
            } else {
                return false;
            }
        }

        public function getDetails()
        {
            return UserDetails::findOne(['user_id' => $this->id]);
        }

        public function getFullname()
        {
            $ud = $this->getDetails();
            if ($ud !== null) {
                return $ud->name_last . ' ' . $ud->name_first;
            } else {
                return $this->email;
            }
        }

        /**
         * @inheritdoc
         */
        public static function findIdentity($id)
        {
            return static::findOne($id);
        }


        /**
         * Finds user by username
         *
         * @param  string $username
         * @return Users|null
         */
        public static function findByEmail($email, $status = self::STATUS_ACTIVE)
        {
            return static::findOne([
                'email'  => $email,
                'status' => $status,
            ]);
        }

        /**
         * Finds user by password reset token
         *
         * @param  string $token password reset token
         * @return Users|null
         */
        public static function findByPasswordResetToken($token)
        {
            $expire    = \Yii::$app->params['user.passwordResetTokenExpire'];
            $parts     = explode('_', $token);
            $timestamp = (int)end($parts);
            if ($timestamp + $expire < time()) {
                // token expired
                return null;
            }

            return static::findOne([
                'password_reset_token' => $token,
                'status'               => self::STATUS_ACTIVE,
            ]);
        }

        /**
         * @inheritdoc
         */
        public function getId()
        {
            return $this->getPrimaryKey();
        }

        public function getEmail()
        {
            return $this->email;
        }

        /**
         * @inheritdoc
         */
        public function getAuthKey()
        {
            return $this->auth_key;
        }

        /**
         * @inheritdoc
         */
        public function validateAuthKey($authKey)
        {
            return $this->getAuthKey() === $authKey;
        }

        /**
         * Validates password
         *
         * @param  string $password password to validate
         * @return boolean if password provided is valid for current user
         */
        public function validatePassword($password)
        {
            return Security::validatePassword($password, $this->password_hash);
        }

        /**
         * Generates password hash from password and sets it to the model
         *
         * @param string $password
         */
        public function setPassword($password)
        {
            $this->password_hash = Security::generatePasswordHash($password);
        }

        /**
         * Generates "remember me" authentication key
         */
        public function generateAuthKey()
        {
            $this->auth_key = Security::generateRandomKey();
        }

        /**
         * Generates new password reset token
         */
        public function generatePasswordResetToken()
        {
            $this->password_reset_token = Security::generateRandomKey() . '_' . time();
        }

        /**
         * Removes password reset token
         */
        public function removePasswordResetToken()
        {
            $this->password_reset_token = null;
        }

        public function activateRegistration($email, $key)
        {

        }

        /**
         * Finds an identity by the given secrete token.
         * @param string $token the secrete token
         * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
         * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
         * @return IdentityInterface the identity object that matches the given token.
         * Null should be returned if such an identity cannot be found
         * or the identity is not in an active state (disabled, deleted, etc.)
         */
        public static function findIdentityByAccessToken($token, $type = null)
        {
            // TODO: Implement findIdentityByAccessToken() method.
        }
    }
