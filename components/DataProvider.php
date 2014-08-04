<?php
    /**
     * Created by PhpStorm.
     * User: borsosalbert
     * Date: 2014.07.16.
     * Time: 12:35
     */

    namespace albertborsos\yii2user\components;


    use albertborsos\yii2user\models\Users;

    class DataProvider {

        public static function items($category, $id = null, $returnArray = true)
        {
            $array = array();
            switch ($category) {
                case 'roles':
                    $array = array(
                        'guest'  => 'Vendég',
                        'reader' => 'Olvasó',
                        'editor' => 'Szerkesztő',
                        'admin'  => 'Adminisztrátor',
                    );
                    break;
                case 'status_user':
                    $array = array(
                        Users::STATUS_ACTIVE   => 'Aktív',
                        Users::STATUS_INACTIVE => 'Inaktív',
                        Users::STATUS_DELETED  => 'Törölt',
                    );
                    break;
            }
            if (is_null($id) && $returnArray) {
                return $array;
            } else {
                return isset($array[$id]) ? $array[$id] : $id;
            }
        }
    }