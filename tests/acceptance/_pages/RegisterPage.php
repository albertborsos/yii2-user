<?php

class RegisterPage
{
    // include url of current page
    static $URL = '/users/register';

    static $firstnameField = '#registerform-firstname';
    static $lastnameField = '#registerform-lastname';
    static $emailField = '#registerform-email';
    static $passwordField = '#registerform-password';
    static $submitButton = '#registerform-submit';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: EditPage::route('/123-post');
     */
    public static function route($param)
    {
        return static::$URL.$param;
    }

    /**
     * @var WebGuy;
     */
    protected $webGuy;

    public function __construct(WebGuy $I)
    {
        $this->webGuy = $I;
    }

    /**
     * @return RegisterPage
     */
    public static function of(WebGuy $I)
    {
        return new static($I);
    }

    public function register($user){
        $I = $this->webGuy;

        $I->amOnPage(self::$URL);
        $I->fillField(self::$firstnameField, $user['firstname']);
        $I->fillField(self::$lastnameField, $user['lastname']);
        $I->fillField(self::$emailField, $user['email']);
        $I->fillField(self::$passwordField, $user['password']);

        $I->click(self::$submitButton);

        return $this;
    }
}