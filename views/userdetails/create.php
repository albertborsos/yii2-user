<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var albertborsos\yii2user\models\UserDetails $model
 */

$this->title = 'Create User Details';
$this->params['breadcrumbs'][] = ['label' => 'User Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-details-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
