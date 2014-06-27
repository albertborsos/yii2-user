<?php
/**
 * @var yii\web\View $this
 * @var albertborsos\yii2user\models\UserDetails $model
 * @var SetNewPasswordForm $new_pwd_model
 */
?>
<div class="row">
    <div class="col-md-6">
        <div class="well">
            <legend>Alapadatok módosítása</legend>
            <?php
                include(__DIR__ . '/../userdetails/_form.php');
            ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12">
                <div class="well">
                <legend>Jelszómódosítás</legend>
                <?php
                    print Yii::$app->controller->renderPartial('_new_pwd_form', [
                            'model' => $new_pwd_model,
                        ]);
                ?>
                </div>
            </div>
        </div>
    </div>
</div>