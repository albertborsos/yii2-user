<?php

namespace vendor\albertborsos\user\controllers;

use vendor\albertborsos\lib\Controller;
use vendor\albertborsos\user\forms\LoginForm;
use vendor\albertborsos\user\forms\RegisterForm;
use vendor\albertborsos\user\forms\ReminderForm;
use vendor\albertborsos\user\forms\SetNewPasswordForm;
use vendor\albertborsos\user\models\Users;
use vendor\albertborsos\user\models\UserDetails;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use Yii;
use yii\helpers\Security;
use yii\web\User;

class DefaultController extends Controller
{
    public function init()
    {
        parent::init();
        $names = [
            'profile' => 'Profil',
            'settings' => 'Beállítások',
            'login' => 'Bejelentkezés',
            'logout' => 'Kijelentkezés',
            'register' => 'Regisztráció',
            'activate' => 'Fiókaktiválás',
            'reminder' => 'Jelszóemlékeztető',
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
                'only' => ['logout', 'register'],
                'rules' => [
                    [
                        'actions' => ['register'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
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

    public function actionIndex()
    {
        $this->layout = '//center';

        return $this->render('index', [
                'params' => $this->breadcrumbs,
            ]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Yii::$app->session->setFlash('success', '<h4>Sikeres bejelentkezés!</h4>');
            return $this->goBack();
        } else {
            return $this->render('login', [
                    'model' => $model,
                ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionRegister()
    {
        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->register()) {
                Yii::$app->session->setFlash('<h4>Sikeres regisztráció!</h4>');
                $this->redirect(['/users/login']);
            }
        }

        return $this->render('register', [
                'model' => $model,
            ]);
    }

    public function actionActivate($email = '', $key = '')
    {
        if ($email === '' || $key === ''){
            Yii::$app->session->setFlash('error', '<h4>Hibás aktiváló link!</h4><p>Nem megfelelő linket használsz!</p>');
            $this->redirect(['/users/login']);
        }else{
            $transaction = Yii::$app->db->beginTransaction();
            try{
                $user = Users::findByEmail($email, 'i');

                if (!is_null($user)){
                    if ($user->validateAuthKey($key)){
                        //ok, lehet aktiválni
                        $user->activated_at = date('Y-m-d H:i:s');
                        $user->status = 'a';
                        if ($user->save()){
                            $userdetails = UserDetails::findOne([
                                    'user_id' => $user->id
                                ]);
                            $userdetails->status = 'a';
                            if ($userdetails->save()){
                                $transaction->commit();
                                Yii::$app->session->setFlash('success', '<h4>Sikeres aktiválás!</h4><p>Most már be tudsz lépni az oldalra!</p>');
                                $this->redirect(['/users/login']);
                            }else{
                                throw new Exception('<h4>Nem sikerült bekativálni a fiókod</h4>');
                            }
                        }else{
                            throw new Exception('<h4>Nem sikerült bekativálni a fiókod</h4>');
                        }
                    }else{
                        // Nincs ilyen felhasználó
                        throw new Exception('<h4>Nem megfelelő aktiválókulcs!</h4><p>Vagy már beaktiváltad a fiókod! Próbálj meg belépni!</p>');
                    }
                }else{
                    throw new Exception('<h4>Nincs ilyen emailcím a rendszerben!</h4><p>Előbb be kell regisztrálnod, hogy aktiválni tudd a fiókod!</p>');
                }

            }catch (Exception $e){
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
                $this->redirect(['/users/login']);
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
                if ($user->save()){
                    Yii::$app->session->setFlash('success', '<h4>Jelszóemlékeztető kiküldve!</h4><p>A pontos tennivalókért olvasd el a levelet, amit küldtünk!</p>');
                    $this->redirect(['/users/login']);
                }else{
                    Yii::$app->session->setFlash('error', '<h4>Jelszóemlékeztetőt nem sikerült kiküldeni!</h4>');
                }
            }
        }

        return $this->render(
            'reminder',
            [
                'model' => $model,
            ]
        );
    }

    public function actionSetnewpassword($email, $key){
        $user = Users::findByPasswordResetToken($key);
        if (!is_null($user) && $user->email === $email){
            // talált usert
            // megváltoztathatja a jelszavát
            $model = new SetNewPasswordForm();
            $model->email = $email;

            if ($model->load(Yii::$app->request->post())){
                if ($model->validate()){
                    // ha minden ok, akkor
                    $user->setPassword($model->password);
                    $user->removePasswordResetToken();
                    if ($user->save()){
                        Yii::$app->session->setFlash('success', '<h4>Sikeresen frissítetted a jelszavad!</h4>');
                        Yii::$app->user->login($user);
                        $this->redirect(['/users/profile']);
                    }
                }
            }

            return $this->render('setnewpassword', [
                    'model' => $model,
                ]);
        }else{
            Yii::$app->session->setFlash('error', '<h4>Nem megfelelő a link...</h4><p>... vagy már lejárt a jelszóemlékeztetőd. Próbálj meg kérni egy újat!</p>');
            $this->redirect(['/users/reminder']);
        }
    }

    public function actionSettings()
    {
        return $this->render('index');
    }

    public function actionProfile()
    {
        $user = Users::findByEmail(Yii::$app->user->identity->email);
        $ud = $user->getDetails();
        if (is_null($ud)){
            $ud = new UserDetails();
            $ud->user_id = Yii::$app->user->identity->getId();
        }

        return $this->render('profile', [
                'model' => $ud,
                'new_pwd_model' => new SetNewPasswordForm(),
            ]);
    }
}
