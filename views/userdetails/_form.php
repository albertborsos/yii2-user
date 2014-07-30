<?php

    use albertborsos\yii2lib\helpers\Values;
    use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var albertborsos\yii2user\models\UserDetails $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="user-details-form">

    <?php $form = ActiveForm::begin([
            'options' => [
                'class' => 'form-horizontal'
            ],
            'fieldConfig' => [
                'template' => '{label}<div class="col-sm-9">{input}</div><div class="col-sm-12">{error}</div>',
                'labelOptions' => ['class' => 'col-sm-3 control-label'],
            ]
        ]); ?>

    <?= $form->field($model, 'name_first')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'name_last')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'sex')->dropDownList(Values::items('sex'), ['prompt' => 'Válassz nemet!']) ?>

    <?= $form->field($model, 'email')->input('email', ['maxlength' => 100]) ?>

    <?= $form->field($model, 'website')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'google_profile')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'facebook_profile')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'country')->dropDownList(Values::items('countries')) ?>

    <?= $form->field($model, 'county')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'postal_code')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'city')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'phone_1')->textInput(['maxlength' => 30]) ?>

    <?= $form->field($model, 'phone_2')->textInput(['maxlength' => 30]) ?>

    <div class="form-group">
        <div class="col-sm-9 pull-right">
            <?= Html::submitButton(
                'Mentés',
                ['class' => 'btn btn-primary col-sm-12 col-md-3']
            ) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
