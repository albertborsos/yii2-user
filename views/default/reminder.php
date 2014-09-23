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
            <div class="panel-heading"><h3 class="panel-title">Jelszóemlékeztető</h3></div>
            <div class="panel-body">

                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'email')->input('email') ?>

                <div class="btn-block">
                    <?= Html::submitButton('Jelszóemlékeztető küldése!', [
                        'class' => 'btn btn-primary btn-block',
                        'id'    => 'reminderform-submit'
                    ]) ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
            <!-- panel-body -->
        </div>
        <!-- panel panel-default -->
    </div>
</div>
<!-- login -->
