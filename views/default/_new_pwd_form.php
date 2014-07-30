<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<?php $form = ActiveForm::begin([
        'options' => [
            //'class' => 'form-horizontal',
        ],
        'fieldConfig' => [
            'template' => '{label}{input}{error}',
            'labelOptions' => ['class' => 'control-label'],
        ]
    ]);?>

<?php $form->errorSummary($model); ?>

<?= Html::activeHiddenInput($model, 'email') ?>
<?= $form->field($model, 'password')->passwordInput() ?>
<?= $form->field($model, 'password_again')->passwordInput() ?>

<div class="form-group">
    <div class="col-md-12 pull-right">
        <?= Html::submitButton(
            'Beállítom',
            ['class' => 'btn btn-primary col-sm-12 col-md-4', 'id' => 'setnewpasswordform-submit']
        ) ?>
    </div>
</div>
<div class="clearfix"></div>
<?php ActiveForm::end(); ?>
