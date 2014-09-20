<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var albertborsos\yii2user\models\Users $model
 * @var ActiveForm $form
 */
?>
<div class="row">
    <div class="col-md-offset-3 col-md-6">

        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Bejelentkezés</h3></div>
            <div class="panel-body">

                <?php $form = ActiveForm::begin(); ?>

                <?php $form->errorSummary($model); ?>

                <?= $form->field($model, 'email') ?>
                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= Html::submitButton('Bejelentkezés', ['class' => 'btn btn-primary btn-block', 'id' => 'loginform-submit']) ?>
                <?= Html::a('Elfelejtett jelszó', Yii::$app->urlManager->createUrl(['/users/reminder']), ['class' => 'btn btn-warning btn-block']) ?>
                <?php ActiveForm::end(); ?>
            </div>
            <!-- panel-body -->
        </div>
        <!-- panel panel-default -->
        <?= Html::a('Regisztráció', Yii::$app->urlManager->createUrl(['/users/register']), ['class' => 'btn btn-info btn-block', 'style' => 'margin-bottom:10px;']) ?>
    </div>
    <!-- login -->
</div>
