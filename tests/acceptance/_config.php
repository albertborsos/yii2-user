<?php

return yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../../../../frontend/config/main.php'),
    require(__DIR__ . '/../../../../../frontend/config/main-local.php'),
    require(__DIR__ . '/../../../../../common/config/main.php'),
    require(__DIR__ . '/../../../../../common/config/main-local.php'),
    require(__DIR__ . '/../_config.php'),
    [
        'components' => [
            'db' => [
                'dsn' => 'mysql:host=localhost;dbname=yii2advanced',
            ],
        ],
    ]
);
