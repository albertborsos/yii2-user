<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var vendor\albertborsos\user\models\Users $model
 * @var ActiveForm $form
 */
?>
<div class="col-md-offset-4 col-md-4">

    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">Bejelentkezés</h3></div>
        <div class="panel-body">

            <?php $form = ActiveForm::begin(); ?>

            <?php $form->errorSummary($model); ?>

            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'password')->passwordInput() ?>

            <div class="btn-block">
                <?= Html::submitButton('Bejelentkezés', ['class' => 'btn btn-primary col-md-12']) ?>
                <?= Html::a('Elfelejtett jelszó', Yii::$app->urlManager->createUrl(['/users/reminder']), ['class' => 'btn btn-warning col-md-12']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <!-- panel-body -->
    </div>
    <!-- panel panel-default -->
    <?= Html::a('Regisztráció', Yii::$app->urlManager->createUrl(['/users/register']), ['class' => 'btn btn-info col-md-12']) ?>
</div>
<!-- login -->
