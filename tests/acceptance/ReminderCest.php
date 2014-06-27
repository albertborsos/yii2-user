<?php
use \WebGuy;
use common\Library;

class ReminderCest
{

    public function _before()
    {
        Yii::$app->user->logout();
    }

    public function _after()
    {
        Yii::$app->user->logout();
    }

    // tests
    public function requestReminder(WebGuy $I) {
        $user = Library::$users['existing']['albert'];

        ReminderPage::of($I)->request($user);
        $I->see('Jelszóemlékeztető kiküldve!');
        $I->dontseeInDatabase('tbl_user_users', [
                'email' => $user['email'],
                'password_reset_token' => null,
            ]);
    }

    public function refreshPassword(WebGuy $I){
        $user = Library::$users['existing']['albert'];

        ReminderPage::of($I)->request($user);
        $key = $I->grabFromDatabase('tbl_user_users', 'password_reset_token', [
                'email' => $user['email'],
            ]);
        SetNewPasswordPage::of($I)->refreshPassword($user, $key);
        $I->see($user['email']);
    }


}