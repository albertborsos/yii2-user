<?php

class ReminderPage
{
    // include url of current page
    static $URL = '/users/reminder';


    /**
     * Declare UI map for this page here. CSS or XPath allowed.*/
    public static $emailField = '#reminderform-email';
    public static $submitButton = "#reminderform-submit";

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
     * @return ReminderPage
     */
    public static function of(WebGuy $I)
    {
        return new static($I);
    }

    public function request($user){
        $I = $this->webGuy;

        $I->amOnPage(self::$URL);
        $I->fillField(self::$emailField, $user['email']);

        $I->click(self::$submitButton);

        return $this;
    }
}