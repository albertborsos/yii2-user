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
        <div class="panel-heading"><h3 class="panel-title">Új jelszó beállítása</h3></div>
        <div class="panel-body">

            <?php
                include(__DIR__.'/_new_pwd_form.php');
            ?>
        </div>
        <!-- panel-body -->
    </div>
    <!-- panel panel-default -->
</div>
<!-- login -->
