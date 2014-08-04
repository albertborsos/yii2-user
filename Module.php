<?php

namespace albertborsos\yii2user;

use yii\base\BootstrapInterface;

class Module extends \yii\base\Module implements BootstrapInterface
{
    public $controllerNamespace = 'albertborsos\yii2user\controllers';
    public $name = 'Felhasználó';

    /**
     * Module specific urlManager
     * @param $app
     */
    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules([
            $this->id . '/?' => $this->id . '/default/login',
            $this->id . '/<id:\d+>' => $this->id . '/default/view',
            $this->id . '/<action:\w+>/?' => $this->id . '/default/<action>',
            $this->id . '/<controller:\w+>/<action:\w+>' => $this->id . '/<controller>/<action>',
        ], false);
    }

    public function init()
    {
        parent::init();
        // custom initialization code goes here
    }
}
