<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var vendor\albertborsos\user\models\UserDetailsSearch $searchModel
 */

$this->title = 'User Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-details-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create User Details', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'user_id',
            'name_first',
            'name_last',
            'sex',
            // 'country',
            // 'county',
            // 'postal_code',
            // 'city',
            // 'email:email',
            // 'phone_1',
            // 'phone_2',
            // 'website',
            // 'comment_private:ntext',
            // 'google_profile',
            // 'facebook_profile',
            // 'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
