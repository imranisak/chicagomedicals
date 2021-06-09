<?php

class VerifyUserCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
        $userEmail="john@doe.com";
        $I->seeInDatabase('verifications', ['userEmail'=>$userEmail]);
        $hash=$I->grabFromDatabase('verifications', 'hash', array("userEmail"=>"john@doe.com"));
        $I->amOnPage("/pages/users/verify.php/?hash=$hash&email=$userEmail");
        $I->see("successfully");
        $I->seeInDatabase("users", ['email'=>$userEmail, 'verified'=>1]);
    }
}
