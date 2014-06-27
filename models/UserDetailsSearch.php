<?php

namespace albertborsos\yii2user\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use albertborsos\yii2user\models\UserDetails;

/**
 * UserDetailsSearch represents the model behind the search form about `albertborsos\yii2user\models\UserDetails`.
 */
class UserDetailsSearch extends UserDetails
{
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['name_first', 'name_last', 'sex', 'country', 'county', 'postal_code', 'city', 'email', 'phone_1', 'phone_2', 'website', 'comment_private', 'google_profile', 'facebook_profile', 'status'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = UserDetails::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'name_first', $this->name_first])
            ->andFilterWhere(['like', 'name_last', $this->name_last])
            ->andFilterWhere(['like', 'sex', $this->sex])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'county', $this->county])
            ->andFilterWhere(['like', 'postal_code', $this->postal_code])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone_1', $this->phone_1])
            ->andFilterWhere(['like', 'phone_2', $this->phone_2])
            ->andFilterWhere(['like', 'website', $this->website])
            ->andFilterWhere(['like', 'comment_private', $this->comment_private])
            ->andFilterWhere(['like', 'google_profile', $this->google_profile])
            ->andFilterWhere(['like', 'facebook_profile', $this->facebook_profile])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
