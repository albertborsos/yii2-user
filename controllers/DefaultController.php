<?php

    namespace albertborsos\yii2user\controllers;

    use albertborsos\yii2lib\helpers\S;
    use albertborsos\yii2lib\helpers\Seo;
    use albertborsos\yii2lib\helpers\Values;
    use albertborsos\yii2lib\web\Controller;
    use albertborsos\yii2user\forms\LoginForm;
    use albertborsos\yii2user\forms\RegisterForm;
    use albertborsos\yii2user\forms\ReminderForm;
    use albertborsos\yii2user\forms\SetNewPasswordForm;
    use albertborsos\yii2user\languages\hu\Messages;
    use albertborsos\yii2user\models\Users;
    use albertborsos\yii2user\models\UserDetails;
    use yii\base\Exception;
    use yii\filters\AccessControl;
    use yii\filters\VerbFilter;
    use Yii;

    class DefaultController extends Controller {
        public $defaultAction = 'login';
        public function init()
        {
            $this->setTheme('page');
            parent::init();
            $names = [
                'profile'        => 'Profil',
                'settings'       => 'Beállítások',
                'login'          => 'Bejelentkezés',
                'logout'         => 'Kijelentkezés',
                'register'       => 'Regisztráció',
                'activate'       => 'Fiókaktiválás',
                'reminder'       => 'Jelszóemlékeztető',
                'setnewpassword' => 'Új jelszó beállítása',
            ];
            $this->addActionNames($names);
            $this->layout = '//center';
        }

        /**
         * @inheritdoc
         */
        public function behaviors()
        {
            return [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'actions' => ['settings', 'profile'],
                            'allow'   => true,
                            'matchCallback' => function(){
                                return !Yii::$app->user->isGuest;
                            }
                        ],
                        [
                            'actions' => ['logout'],
                            'allow'   => true,
                            'matchCallback' => function(){
                                return !Yii::$app->user->isGuest;
                            }
                        ],
                        [
                            'actions' => ['register', 'activate', 'login', 'setnewpassword', 'reminder'],
                            'allow' => true,
                            'matchCallback' => function(){
                                    if (!Yii::$app->user->isGuest){
                                        return $this->goHome();
                                    }else{
                                        return true;
                                    }
                                }
                        ],
                    ],
                ],
                'verbs'  => [
                    'class'   => VerbFilter::className(),
                    'actions' => [
                        'logout' => ['post'],
                    ],
                ],
            ];
        }

        /**
         * @inheritdoc
         */
        public function actions()
        {
            return [
                'error' => [
                    'class' => 'yii\web\ErrorAction',
                ],
            ];
        }

        public function actionLogin()
        {
            $model = new LoginForm();
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                Yii::$app->session->setFlash('success', Messages::$login_successful);

                return $this->goBack();
            } else {
                Seo::noIndex();
                return $this->render('login', [
                    'model' => $model,
                ]);
            }
        }

        public function actionLogout()
        {
            Yii::$app->user->logout(Yii::$app->user->destroySession);
            Yii::$app->session->setFlash('error', Messages::$logout_succesful);

            return $this->goHome();
        }

        public function actionRegister()
        {
            $model = new RegisterForm();
            if ($model->load(Yii::$app->request->post()) && $model->register()) {
                Yii::$app->session->setFlash('success',Messages::$registration_succesful);

                return $this->goHome();
            }
            Seo::noIndex();
            return $this->render('register', [
                'model' => $model,
            ]);
        }

        public function actionActivate($email = '', $key = '')
        {
            if ($email === '' || $key === '') {
                Yii::$app->session->setFlash('error', Messages::$activation_error_wrong_link);
                return $this->redirect(['/users/login']);
            } else {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $user = Users::findByEmail($email, 'i');

                    if (!is_null($user)) {
                        if ($user->validateAuthKey($key)) {
                            //ok, lehet aktiválni
                            if ($user->activateRegistration()) {
                                $transaction->commit();
                                Yii::$app->session->setFlash('success', Messages::$activation_successful);

                                return $this->redirect(['/users/login']);
                            } else {
                                throw new Exception(Messages::$activation_error);
                            }
                        } else {
                            // Nincs ilyen felhasználó
                            throw new Exception(Messages::$activation_error_wrong_key);
                        }
                    } else {
                        throw new Exception(Messages::$activation_error_wrong_email);
                    }

                } catch (Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', $e->getMessage());

                    return $this->redirect(['/users/login']);
                }
            }
        }

        public function actionReminder()
        {
            $model = new ReminderForm();

            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate()) {
                    $user = Users::findByEmail($model->email);
                    $user->generatePasswordResetToken();
                    if ($user->save()) {
                        $user->sendReminderMail();
                        Yii::$app->session->setFlash('success', Messages::$reminder_email_sent);

                        return $this->redirect(['/users/login']);
                    } else {
                        Yii::$app->session->setFlash('error', Messages::$reminder_error);
                    }
                }
            }
            Seo::noIndex();
            return $this->render('reminder', [
                'model' => $model,
            ]);
        }

        public function actionSetnewpassword($email, $key)
        {
            $user = Users::findByPasswordResetToken($key);
            if (!is_null($user) && $user->email === $email) {
                // talált usert
                // megváltoztathatja a jelszavát
                $model        = new SetNewPasswordForm();
                $model->email = $email;

                if ($model->load(Yii::$app->request->post())) {
                    if ($model->validate()) {
                        // ha minden ok, akkor
                        $user->setPassword($model->password);
                        $user->removePasswordResetToken();
                        if ($user->save()) {
                            Yii::$app->session->setFlash('success', Messages::$new_password_successfully_changed);
                            Yii::$app->user->login($user);
                            $this->redirect(['/users/profile']);
                        }
                    }
                }
                Seo::noIndex();
                return $this->render('setnewpassword', [
                    'model' => $model,
                ]);
            } else {
                Yii::$app->session->setFlash('error', Messages::$new_password_error_wrong_link);

                return $this->redirect(['/users/reminder']);
            }
        }

        public function actionSettings()
        {
            Seo::noIndex();
            return $this->render('settings');
        }

        public function actionProfile()
        {
            $user = Users::findByEmail(Yii::$app->user->identity->email);
            $ud = $user->getDetails();

            if (Yii::$app->request->isPost){
                $changepwd = Yii::$app->request->post('SetNewPasswordForm');
                if (!is_null($changepwd)){
                    $user->changePassword(Values::arrayGet('email', $changepwd));
                    return $this->redirect(['/users/profile']);
                }

                if ($ud->load(Yii::$app->request->post(), 'UserDetails') && $ud->save()){
                    Yii::$app->session->setFlash('success', Messages::$user_details_update_successful);
                }else{
                    Yii::$app->session->setFlash('error', Messages::$user_details_update_error);
                }
                return $this->redirect(['/users/profile']);
            }

            $form_pwd = new SetNewPasswordForm();
            $form_pwd->email = Yii::$app->user->identity->email;

            Seo::noIndex();
            return $this->render('profile', [
                'model'         => $ud,
                'new_pwd_model' => $form_pwd,
            ]);
        }
    }
