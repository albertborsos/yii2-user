<?php

namespace vendor\albertborsos\user\models;

use Yii;
use yii\base\ModelEvent;
use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "tbl_user_userdetails".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name_first
 * @property string $name_last
 * @property string $sex
 * @property string $country
 * @property string $county
 * @property string $postal_code
 * @property string $city
 * @property string $email
 * @property string $phone_1
 * @property string $phone_2
 * @property string $website
 * @property string $comment_private
 * @property string $google_profile
 * @property string $facebook_profile
 * @property string $status
 */
class UserDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_user_userdetails';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['comment_private'], 'string'],
            [['name_first', 'name_last', 'country', 'county', 'city', 'email'], 'string', 'max' => 100],
            [['sex'], 'string', 'max' => 20],
            [['postal_code'], 'string', 'max' => 10],
            [['phone_1', 'phone_2'], 'string', 'max' => 30],
            [['website', 'google_profile', 'facebook_profile'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'name_first' => 'Keresztnév',
            'name_last' => 'Vezetéknév',
            'sex' => 'Nem',
            'country' => 'Ország',
            'county' => 'Megye',
            'postal_code' => 'Irsz',
            'city' => 'Város',
            'email' => 'E-mail',
            'phone_1' => 'Telefonszám/FAX',
            'phone_2' => 'Mobil',
            'website' => 'weboldal',
            'comment_private' => 'Privát megjegyzés',
            'google_profile' => 'Google+ profil',
            'facebook_profile' => 'Facebook profil',
            'status' => 'Státusz',
        ];
    }



}
