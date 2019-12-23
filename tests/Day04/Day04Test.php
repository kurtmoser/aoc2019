<?php

namespace Aoc2019\Day04;

use PHPUnit\Framework\TestCase;

class Day04Test extends TestCase
{
    /** @test */
    public function passwordValidatorMatchesFirstCriteriaPasswords()
    {
        $this->assertTrue(PasswordValidator::matchesFirstCriteria(111111));
        $this->assertFalse(PasswordValidator::matchesFirstCriteria(223450));
        $this->assertFalse(PasswordValidator::matchesFirstCriteria(123789));
    }

    /** @test */
    public function passwordValidatorMatchesSecondCriteriaPasswords()
    {
        $this->assertTrue(PasswordValidator::matchesSecondCriteria(112233));
        $this->assertFalse(PasswordValidator::matchesSecondCriteria(123444));
        $this->assertTrue(PasswordValidator::matchesSecondCriteria(111122));
    }
}
