<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'form-horizontal'
        ],
        'fieldConfig' => [
            'template' => '{label}<div class="col-sm-8">{input}</div><div class="col-sm-12">{error}</div>',
            'labelOptions' => ['class' => 'col-sm-4 control-label'],
        ]
    ]);?>

<?php $form->errorSummary($model); ?>

<?= Html::activeHiddenInput($model, 'email') ?>
<?= $form->field($model, 'password')->passwordInput() ?>
<?= $form->field($model, 'password_again')->passwordInput() ?>

<div class="form-group">
    <div class="col-sm-8 pull-right">
        <?= Html::submitButton(
            'Beállítom',
            ['class' => 'btn btn-primary col-sm-12 col-md-3', 'id' => 'setnewpasswordform-submit']
        ) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
