<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var albertborsos\yii2user\models\Users $model
 * @var ActiveForm $form
 */
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'lastName') ?>
<?= $form->field($model, 'firstName') ?>
<?= $form->field($model, 'email')->input('email') ?>

<div class="btn-block">
	<?= Html::submitButton('Tovább', ['class' => 'btn btn-primary btn-block', 'id' => 'registerform-submit']) ?>
</div>
<?php ActiveForm::end(); ?>
