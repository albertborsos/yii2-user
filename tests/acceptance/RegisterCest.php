<?php
use \WebGuy;
use common\Library;

class RegisterCest
{
    public $user;
    public $user_registered;

    public function _before()
    {
        $this->user = Library::$users['register']['ok'];
        $this->user_registered = Library::$users['existing']['albert'];

        Yii::$app->user->logout();
    }

    public function _after()
    {
        Yii::$app->user->logout();
    }

    public function testRegistrationPageIsExists(WebGuy $I)
    {
        $I->amOnPage(Yii::$app->urlManager->createUrl([RegisterPage::$URL]));
        $I->seeElement(RegisterPage::$firstnameField);
        $I->seeElement(RegisterPage::$lastnameField);
        $I->seeElement(RegisterPage::$emailField);
        $I->seeElement(RegisterPage::$passwordField);
        $I->seeElement(RegisterPage::$submitButton);
    }

    public function testRegistrationFormWithErrors(WebGuy $I)
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
                'RegisterForm[lastName]' => $this->user['lastname'],
                'RegisterForm[firstName]' => $this->user['firstname'],
                'RegisterForm[email]' => $this->user['email'],
                'RegisterForm[password]' => 'rossz',
            ]
        );

        $I->dontSee('Vezetéknév nem lehet üres.');
        $I->dontSee('Keresztnév nem lehet üres.');
        $I->dontSee('E-mail cím nem lehet üres.');
        $I->see('Jelszó minimum 8 karakter kell, hogy legyen.');

    }

    public function testRegistrationIsOK(WebGuy $I){
        \RegisterPage::of($I)->register($this->user);

        $I->seeCurrentUrlMatches('~users/login~');
        //$I->see('Sikeres regisztráció!');

        $I->seeInDatabase(
            \vendor\albertborsos\user\models\Users::tableName(),
            [
                'email' => $this->user['email'],
                'status' => 'i',
            ]
        );

        $I->seeInDatabase(
            \vendor\albertborsos\user\models\UserDetails::tableName(),
            [
                'name_first' => $this->user['firstname'],
                'name_last' => $this->user['lastname'],
                'status' => 'i',
            ]
        );
    }
    public function testActivationIsOK(WebGuy $I){
        \ActivatePage::of($I)->activate($this->user);
        $I->see('Sikeres aktiválás!');
        $I->seeInDatabase(
            \vendor\albertborsos\user\models\Users::tableName(),
            [
                'email' => $this->user['email'],
                'status' => 'a',
            ]
        );

        $I->seeInDatabase(
            \vendor\albertborsos\user\models\UserDetails::tableName(),
            [
                'name_first' => $this->user['firstname'],
                'name_last' => $this->user['lastname'],
                'status' => 'a',
            ]
        );
    }

    public function testLoginIsOK(WebGuy $I){
        LoginPage::of($I)->login($this->user_registered);
        $I->see('Sikeres bejelentkezés!');
        $I->see($this->user_registered['email']);
    }

    public function testSessionIsStored(WebGuy $I){
        LoginPage::of($I)->login($this->user_registered);
        $I->amOnPage(Yii::$app->urlManager->createUrl(['/site/error']));
        $I->see($this->user_registered['email']);
    }


}