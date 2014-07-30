<?php
/**
 * @var albertborsos\yii2user\models\Users $user
 */
?>

<h4>Kedves <?= $user->getDetails()->name_first; ?>!</h4>
<p>Mivel jelszóemlékeztetőt kértél a weboldalon, ezért az alábbi linken tudsz beállítani magadnak új jelszót:</p>
<?= $link['reminder']; ?>