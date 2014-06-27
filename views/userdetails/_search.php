<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var vendor\albertborsos\user\models\UserDetailsSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="user-details-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'name_first') ?>

    <?= $form->field($model, 'name_last') ?>

    <?= $form->field($model, 'sex') ?>

    <?php // echo $form->field($model, 'country') ?>

    <?php // echo $form->field($model, 'county') ?>

    <?php // echo $form->field($model, 'postal_code') ?>

    <?php // echo $form->field($model, 'city') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'phone_1') ?>

    <?php // echo $form->field($model, 'phone_2') ?>

    <?php // echo $form->field($model, 'website') ?>

    <?php // echo $form->field($model, 'comment_private') ?>

    <?php // echo $form->field($model, 'google_profile') ?>

    <?php // echo $form->field($model, 'facebook_profile') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
