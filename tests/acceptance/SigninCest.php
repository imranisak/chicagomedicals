<?php

class SigninCest

{
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
        $I->amOnPage("/pages/users/register.php");
        $I->fillField('name', 'John');
        $I->fillField('surname', 'Doe');
        $I->fillField('email', 'John@doeeeeeeee.com');
        $I->attachFile('file', 'jd1.jfif');
        $I->fillField("password_1", new \Codeception\Step\Argument\PasswordArgument("lozinka"));
        $I->fillField("password_2", new \Codeception\Step\Argument\PasswordArgument("lozinka"));
        $I->click("submit");
        $I->seeInCurrentUrl("/");
    }
}
