<?php

class LoginPage
{
    // include url of current page
    static $URL = '/users/login';

    static $emailField = '#loginform-email';
    static $passwordField = '#loginform-password';
    static $submitButton = '#loginform-submit';

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
     * @return LoginPage
     */
    public static function of(WebGuy $I)
    {
        return new static($I);
    }

    public function login($user){
        $I = $this->webGuy;
        $I->amOnPage(self::$URL);

        $I->fillField(self::$emailField, $user['email']);
        $I->fillField(self::$passwordField, $user['password']);
        $I->click(self::$submitButton);
    }
}