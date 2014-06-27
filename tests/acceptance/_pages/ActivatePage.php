<?php

class ActivatePage
{
    // include url of current page
    static $URL = '/users/activate';

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
     * @return ActivatePage
     */
    public static function of(WebGuy $I)
    {
        return new static($I);
    }

    public function activate($user){
        $I = $this->webGuy;

        \RegisterPage::of($I)->register($user);

        $auth_key = $I->grabFromDatabase('tbl_user_users', 'auth_key', array('email' => $user['email']));

        $url = '/users/activate?email=' . $user['email'] . '&key=' . $auth_key;

        $I->amOnPage($url);
    }
}