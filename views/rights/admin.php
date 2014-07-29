<?php
/**
 * Created by PhpStorm.
 * User: borsosalbert
 * Date: 2014.07.16.
 * Time: 9:43
 */
    use albertborsos\yii2user\models\Users;

?>
<h3>Felhasználók jogosultságai</h3>

<?= Users::getUsersInGridView()?>