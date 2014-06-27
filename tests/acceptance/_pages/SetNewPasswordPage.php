<?php

class SetNewPasswordPage
{
    // include url of current page
    static $URL = '/users/setnewpassword';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.*/
     public static $emailField = '#setnewpasswordform-email';
     public static $passwordField = '#setnewpasswordform-password';
     public static $passwordAgainField = '#setnewpasswordform-password_again';
     public static $submitButton = "#setnewpasswordform-submit";

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
     * @return SetNewPasswordPage
     */
    public static function of(WebGuy $I)
    {
        return new static($I);
    }

    public function refreshPassword($user, $key){
        $I = $this->webGuy;
        $I->amOnPage($this->route('?email='.$user['email'].'&key='.$key));

        $I->fillField(self::$passwordField, $user['password']);
        $I->fillField(self::$passwordAgainField, $user['password']);
        $I->click(self::$submitButton);

        $I->see('Sikeresen frissítetted a jelszavad!');
    }
}