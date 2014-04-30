<?php

namespace vendor\albertborsos\user\controllers;

use vendor\albertborsos\user\forms\LoginForm;
use vendor\albertborsos\user\forms\RegisterForm;
use vendor\albertborsos\user\forms\ReminderForm;
use vendor\albertborsos\user\models\UserDetails;
use Yii;
use vendor\albertborsos\user\models\Users;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            // ha vendég, akkor login
            $this->redirect(['/users/login']);
        } else {
            return $this->render('index');
        }
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest){
            $this->redirect(['/users/index']);
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Yii::$app->session->setFlash('success', '<h4>Sikeres bejelentkezés!</h4>');
            return $this->redirect(['/users/login']);
        }else{
            return $this->render('login', [
                    'model' => $model,
                ]
            );
        }

    }

    public function actionLogout()
    {

    }

    public function actionRegister()
    {
        $model = new RegisterForm();

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->validate()) {
                    // ha ok, akkor mentjük
                    $user = new Users();
                    $user->email = $model->email;
                    $user->password_hash = $model->password;
                    $user->status = 'i';
                    $user->username = 'albertborsos';

                    if ($user->save()) {
                        $userdetails = new UserDetails();
                        $userdetails->user_id = $user->id;
                        $userdetails->name_first = $model->firstName;
                        $userdetails->name_last = $model->lastName;
                        $userdetails->status = 'i';

                        if ($userdetails->save()) {
                            $transaction->commit();
                            Yii::$app->session->setFlash('success', '<h4>Sikeres regisztráció</h4>');
                            $this->redirect(['/users/login']);
                        } else {
                            throw new Exception('userdetails mentése nem sikerült!');
                        }
                    } else {
                        throw new Exception('user mentése nem sikerült!');
                    }
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }

        return $this->render(
            'register',
            [
                'model' => $model,
            ]
        );
    }

    public function actionActivate($email = '', $key = '')
    {
        if ($email === '' || $key === ''){
            Yii::$app->session->setFlash('error', '<h4>Hibás aktiváló link!</h4><p>Nem megfelelő linket használsz!</p>');
            $this->redirect(['/users/login']);
        }else{
            $transaction = Yii::$app->db->beginTransaction();
            try{
                $user = Users::findOne([
                        'email' => $email,
                        'auth_key' => $key,
                    ]);

                if ($user !== null){
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
                // if valid
            }
        }

        return $this->render(
            'reminder',
            [
                'model' => $model,
            ]
        );
    }

    public function actionSettings()
    {

    }

    public function actionProfile()
    {
        var_dump(Yii::$app->user->isGuest);exit();
    }
}
