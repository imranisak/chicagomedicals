<?php

class AddClinicCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
        //Login as user
        $I->amOnPage("/pages/users/login.php");
        $I->fillField("email", "TehanaKupresak@jourrapide.com");
        $I->fillField("password", new \Codeception\Step\Argument\PasswordArgument("lozinka"));
        $I->click("submit");
        $I->amOnPage("/index.php");
        $I->see("Welcome");
        //Submit clinic
        $I->amOnPage("/pages/clinics/addClinic.php");
        $I->fillField("clinicName", "DS9");
        $I->fillField("clinicAddress", "Far away");
        $I->fillField("clinicEmail", "info@ds9.com");
        $I->fillField("zip", "000");
        $I->fillField("services", "Anesthesiologist");
        $I->fillField("clinicWebsite", "https://ds9.com");
        $I->attachFile("file[]", "ds9.jfif");
        $I->click("submit");
        $I->seeInDatabase("clinics", ['name' => 'DS9']);
        //Logout as user, login as admin, approve clinic
        $I->amOnPage('/pages/users/post/logout.php');
        $I->amOnPage("/pages/users/login.php");
        $I->fillField("email", "imran1701d@gmail.com");
        $I->fillField("password", new \Codeception\Step\Argument\PasswordArgument("lozinka"));
        $I->click("submit");
        $I->amOnPage("/admin/clinics/clinic?ID=39");
        $I->click("Approve");
        //Logout as admin
        $I->amOnPage('/pages/users/post/logout.php');

    }
}
