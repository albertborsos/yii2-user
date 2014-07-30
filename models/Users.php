<?php
    namespace albertborsos\yii2user\models;

    use albertborsos\yii2lib\helpers\S;
    use albertborsos\yii2lib\helpers\Values;
    use albertborsos\yii2lib\wrappers\Editable;
    use albertborsos\yii2user\components\DataProvider;
    use albertborsos\yii2user\forms\SetNewPasswordForm;
    use albertborsos\yii2user\languages\hu\Messages;
    use yii\base\NotSupportedException;
    use yii\base\Security;
    use yii\data\ArrayDataProvider;
    use yii\db\ActiveRecord;
    use yii\grid\ActionColumn;
    use yii\grid\GridView;
    use yii\web\IdentityInterface;
    use Yii;
    use yii\web\YiiAsset;

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
                    $security = new Security();
                    $this->auth_key   = $security->generateRandomString();
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
            $details = UserDetails::findOne(['user_id' => $this->id, 'status' => UserDetails::STATUS_ACTIVE]);
            if (is_null($details)){
                // if not exists create one
                $details = new UserDetails();
                $details->user_id = $this->id;
                $details->status = UserDetails::STATUS_ACTIVE;
                $details->save();
            }

            return $details;
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
            $expire    = Yii::$app->params['user.passwordResetTokenExpire'];
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
            $security = new Security();
            return $security->validatePassword($password, $this->password_hash);
        }

        /**
         * Generates password hash from password and sets it to the model
         *
         * @param string $password
         */
        public function setPassword($password)
        {
            $security = new Security();
            $this->password_hash = $security->generatePasswordHash($password);
        }

        /**
         * Generates "remember me" authentication key
         */
        public function generateAuthKey()
        {
            $security = new Security();
            $this->auth_key = $security->generateRandomKey();
        }

        /**
         * Generates new password reset token
         */
        public function generatePasswordResetToken()
        {
            $security = new Security();
            $this->password_reset_token = $security->generateRandomKey() . '_' . time();
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

        public static function getUsersInGridView(){
            // lekérdezem a felhasználókat akikhez van jogosultság rendelve
            $auth = Yii::$app->getAuthManager();
            $sql = 'SELECT * FROM ' . Users::tableName().' u'
                .' LEFT JOIN '. $auth->assignmentTable. ' at'
                .' ON at.user_id=u.id'
                .' WHERE u.status=:status_a';

            $cmd = Yii::$app->db->createCommand($sql);
            $cmd->bindValue(':status_a', Users::STATUS_ACTIVE);
            $results = $cmd->queryAll();

            // a felhasználókat beteszem egy dataprovider-be
            $dataProvider = new ArrayDataProvider([
                'allModels'  => $results,
                'sort'       => [
                    'attributes' => ['id'],
                ],
                'key'        => 'id',
                'pagination' => false,
            ]);

            $options_center = ['class' => 'text-center'];

            return GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute'      => 'email',
                        'header'         => Users::attributeLabels()['email'],
                        'contentOptions' => $options_center,
                        'headerOptions'  => $options_center,
                    ],
                    [
                        'attribute'      => 'username',
                        'header'         => Users::attributeLabels()['username'],
                        'contentOptions' => $options_center,
                        'headerOptions'  => $options_center,
                    ],
                    [
                        'attribute'      => 'created_at',
                        'header'         => Users::attributeLabels()['created_at'],
                        'contentOptions' => $options_center,
                        'headerOptions'  => $options_center,
                    ],
                    [
                        'attribute'      => 'activated_at',
                        'header'         => Users::attributeLabels()['activated_at'],
                        'contentOptions' => $options_center,
                        'headerOptions'  => $options_center,
                    ],
                    [
                        'attribute'      => 'updated_at',
                        'header'         => Users::attributeLabels()['updated_at'],
                        'contentOptions' => $options_center,
                        'headerOptions'  => $options_center,
                    ],
                    [
                        'attribute'      => 'status',
                        'header'         => Users::attributeLabels()['status'],
                        'contentOptions' => $options_center,
                        'headerOptions'  => $options_center,
                    ],
                    [
                        'header'         => 'Jogosultság',
                        'attribute'      => 'ITEM_NAME',
                        'format'         => 'raw',
                        'value'          => function ($model, $index, $widget) {
                            return Editable::select(
                                           $model['id'] . '-role',
                                               $model['username'],
                                               $model['item_name'],
                                               DataProvider::items('roles', $model['item_name'], false),
                                               ['/users/rights/modify'],
                                               DataProvider::items('roles'));
                        },
                        'contentOptions' => $options_center,
                        'headerOptions'  => $options_center,
                    ],
                    [
                        'class'    => ActionColumn::className(),
                        'template' => '{delete}',
                    ]
                ],
            ]);
        }

        public function changePassword($email){
            if ($this->email === $email){
                $form = new SetNewPasswordForm();
                if ($form->load(Yii::$app->request->post()) && $form->validate()){
                    $this->setPassword($form->password);
                    if ($this->save()){
                        Yii::$app->session->setFlash('success', Messages::$new_password_successfully_changed);
                    }else{
                        Yii::$app->session->setFlash('error', Messages::$new_password_error_wrong_link);
                    }
                }else{
                    Yii::$app->session->setFlash('error', Messages::$new_password_error_valid);
                }
            }else{
                Yii::$app->session->setFlash('error', Messages::$new_password_error_email);
            }
        }

        public function sendActivationMail(){
            $link['activation'] = Yii::$app->urlManager->getBaseUrl() . '/users/activate?email=' . $this->email . '&key=' . $this->auth_key;
//            $link['activation'] = Yii::$app->urlManager->createUrl(['/users/activate', [
//                'email' => $this->email,
//                'key' => $this->auth_key,
//            ]]);

            $content = Yii::$app->getView()->renderFile('@vendor/albertborsos/yii2-user/views/mail/activation.php', [
                'link' => $link,
                'user' => $this,
            ]);
            return Yii::$app->mailer->compose()
                ->setHtmlBody($content)
                ->setSubject('Sikeres regisztráció!')
                ->setFrom(S::get(Yii::$app->params, 'adminEmail'))
                ->setTo($this->email)
                ->send();
        }
    }
