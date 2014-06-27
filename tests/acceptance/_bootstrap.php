<?php

new yii\web\Application(require(__DIR__ . '/_config.php'));

require_once(__DIR__ . '/../common/Library.php');
\Codeception\Util\Autoload::registerSuffix('Page', __DIR__.DIRECTORY_SEPARATOR.'_pages');