<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var albertborsos\yii2user\models\Users $model
 * @var ActiveForm $form
 */
?>
<div class="col-md-offset-4 col-md-4">

    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">Jelszóemlékeztető</h3></div>
        <div class="panel-body">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'email') ?>

            <div class="btn-block">
                <?= Html::submitButton('Jelszóemlékeztető küldése!', ['class' => 'btn btn-primary col-md-12', 'id' => 'reminderform-submit']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <!-- panel-body -->
    </div>
    <!-- panel panel-default -->
    <?= Html::a('Belépés', Yii::$app->urlManager->createUrl(['/users/login']), ['class' => 'btn col-md-6']) ?>
    <?= Html::a('Még nem regisztráltál?', Yii::$app->urlManager->createUrl(['/users/register']), ['class' => 'btn col-md-6']) ?>
</div>
<!-- login -->
