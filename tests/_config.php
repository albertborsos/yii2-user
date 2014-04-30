<?php
/**
 * application configurations shared by all test types
 */
return [
    'components' => [
        'mail' => [
            'useFileTransport' => true,
        ],
        'urlManager' => [
            'class'=>'yii\web\UrlManager', //Set class
            //'baseUrl' => 'http://localhost/private/yii2-skeleton/frontend/web/',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
    ],
];
