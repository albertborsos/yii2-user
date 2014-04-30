<?php
use \WebGuy;

class RegisterCest
{
    public $user;

    public function _before()
    {
        $this->user['ok']['firstname'] = 'Albert';
        $this->user['ok']['lastname']  = 'Borsos';
        $this->user['ok']['email']     = 'blog@borsosalbert.hu';
        $this->user['ok']['password']  = 'jelszó10';
    }

    public function _after()
    {
    }

    public function registerPageIsExists(WebGuy $I)
    {
        $I->amOnPage(Yii::$app->urlManager->createUrl(['/users/register']));
        $I->see('Vezetéknév', 'label');
        $I->see('Keresztnév', 'label');
        $I->see('E-mail cím', 'label');
        $I->see('Jelszó', 'label');
        $I->see('Regisztráció', 'button');
    }

    public function testFormWithErrors(WebGuy $I)
    {
        $I->amOnPage(Yii::$app->urlManager->createUrl(['/users/register']));
        $I->submitForm('#w0', []);
        $I->see('Vezetéknév nem lehet üres.');
        $I->see('Keresztnév nem lehet üres.');
        $I->see('E-mail cím nem lehet üres.');
        $I->see('Jelszó nem lehet üres.');

        $I->amOnPage(Yii::$app->urlManager->createUrl(['/users/register']));
        $I->submitForm(
            '#w0',
            [
                'RegisterForm[lastName]' => $this->user['ok']['lastname'],
                'RegisterForm[firstName]' => $this->user['ok']['firstname'],
                'RegisterForm[email]' => $this->user['ok']['email'],
                'RegisterForm[password]' => 'rossz',
            ]
        );

        $I->dontSee('Vezetéknév nem lehet üres.');
        $I->dontSee('Keresztnév nem lehet üres.');
        $I->dontSee('E-mail cím nem lehet üres.');
        $I->see('Jelszó minimum 8 karakter kell, hogy legyen.');

    }

    /**
     * Sikeres regisztráció után kell lennie az adatbázisban:
     *  - egy új user rekordnak INAKTIV statusszal
     *  - egy új userdetails rekordnak
     *
     * @param WebGuy $I
     */
    public function registerANewUser(WebGuy $I)
    {

        $I->amOnPage(Yii::$app->urlManager->createUrl(['/users/register']));
        $I->fillField('registerform-lastname', $this->user['ok']['lastname']);
        $I->fillField('registerform-firstname', $this->user['ok']['firstname']);
        $I->fillField('registerform-email', $this->user['ok']['email']);
        $I->fillField('registerform-password', $this->user['ok']['password']);
        $I->click('button[type=submit]');

        $I->amOnPage('/users/login');
        //$I->see('Sikeres regisztráció', 'h4');

        $I->seeInDatabase(
            \vendor\albertborsos\user\models\Users::tableName(),
            [
                'email' => $this->user['ok']['email'],
                'status' => 'i',
            ]
        );

        $I->seeInDatabase(
            \vendor\albertborsos\user\models\UserDetails::tableName(),
            [
                'name_first' => $this->user['ok']['firstname'],
                'name_last' => $this->user['ok']['lastname'],
                'status' => 'i',
            ]
        );

        $auth_key = $I->grabFromDatabase('tbl_user_users', 'auth_key', array('email' => $this->user['ok']['email']));

        $url = Yii::$app->urlManager->createUrl(
                '/users/activate?email=' . $this->user['ok']['email'] . '&key=' . $auth_key
        );

        $I->amOnPage($url);
        $I->see('Sikeres aktiválás!');

        $I->seeInDatabase(
            \vendor\albertborsos\user\models\Users::tableName(),
            [
                'email' => $this->user['ok']['email'],
                'status' => 'a',
            ]
        );

        $I->seeInDatabase(
            \vendor\albertborsos\user\models\UserDetails::tableName(),
            [
                'name_first' => $this->user['ok']['firstname'],
                'name_last' => $this->user['ok']['lastname'],
                'status' => 'a',
            ]
        );

        $I->amOnPage(Yii::$app->urlManager->createUrl(['/users/login']));
        $I->fillField('LoginForm[email]', $this->user['ok']['email']);
        $I->fillField('LoginForm[password]', $this->user['ok']['password']);
        $I->click('button[type=submit]');

        $I->see('Sikeres bejelentkezés!');


    }

}