<?php
    /**
     * Created by PhpStorm.
     * User: borsosalbert
     * Date: 2014.07.16.
     * Time: 12:35
     */

    namespace albertborsos\yii2user\components;


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
            }
            if (is_null($id) && $returnArray) {
                return $array;
            } else {
                return isset($array[$id]) ? $array[$id] : $id;
            }
        }
    }