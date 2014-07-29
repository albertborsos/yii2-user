<?php

    namespace albertborsos\yii2user\controllers;

    use albertborsos\yii2lib\web\Controller;
    use albertborsos\yii2user\languages\hu\Messages;
    use HttpException;
    use yii\filters\AccessControl;
    use yii\filters\VerbFilter;
    use Yii;

    class RightsController extends Controller {
        public $name = 'Jogosultságok';

        public function init()
        {
            parent::init();
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
                    'only'  => ['index', 'setright', 'delete'],
                    'rules' => [
                        [
                            'actions' => ['index', 'remove'],
                            'allow'   => true,
                            'roles'   => ['@'],
                        ],
                    ],
                ],
                'verbs'  => [
                    'class'   => VerbFilter::className(),
                    'actions' => [
                        'remove' => ['post'],
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

        public function actionAdmin()
        {
            $this->layout = '//center';

            return $this->render('admin');
        }

        public function actionModify()
        {
            $username = Yii::$app->request->post('pk');
            $role     = Yii::$app->request->post('value');

            $auth = Yii::$app->authManager;
            if ($auth->revokeAll($username)){
                // ha nem volt hozzárendelve jog, akkor elmentem
                $role_model = $auth->getRole($role);
                if ($role_model !== null) {
                    $auth->assign($role_model, $username);
                    return true;
                } else {
                    throw new HttpException(400,Messages::ERROR_RIGHT_NOT_EXISTS);
                }
            }
        }

        public function actionRemove($id)
        {
            $auth = Yii::$app->authManager;
            if ($auth->revokeAll($id)) {
                Yii::$app->session->setFlash('success', '<h4><b>"' . $id . '"</b>' . Messages::$user_remove_successful . '</h4>');
            } else {
                Yii::$app->session->setFlash('error', '<h4><b>"' . $id . '"</b>' . Messages::$user_remove_error . '</h4>');
            }

            return $this->redirect(Yii::$app->getModule('users')->urls['rights']);
        }
    }
