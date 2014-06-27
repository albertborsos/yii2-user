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
        <div class="panel-heading"><h3 class="panel-title">Regisztráció</h3></div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'lastName') ?>
            <?= $form->field($model, 'firstName') ?>
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'password')->passwordInput() ?>

            <div class="btn-block">
                <?= Html::submitButton('Regisztráció', ['class' => 'btn btn-primary col-md-12', 'id' => 'registerform-submit']) ?>
            </div>
            <?php ActiveForm::end(); ?>
            <div class="alert alert-info text-justify">
                <p><b>Mi fog történni?</b> A megadott e-mailcímre kiküldünk egy aktiváló levelet. Ebben a levélben kattins a linkre, után be tudsz lépni az oldalra!</p>
            </div>
        </div>
        <!-- panel-body -->
    </div>
    <!-- panel panel-default -->
    <?= Html::a('Van már fiókod? Jelentkezz be!', Yii::$app->urlManager->createUrl(['/users/login']), ['class' => 'btn col-md-12']) ?>
</div>
<!-- login -->
