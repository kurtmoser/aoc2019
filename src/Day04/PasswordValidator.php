<?php

namespace Aoc2019\Day04;

class PasswordValidator
{
    public static function matchesFirstCriteria($password)
    {
        $password = (string)$password;

        for ($i = 0; $i < strlen($password) - 1; $i++) {
            if ((int)$password[$i] > (int)$password[$i + 1]) {
                return false;
            }
        }

        if (!preg_match('/(\d)\1/', $password)) {
            return false;
        }

        return true;
    }

    public static function matchesSecondCriteria($password)
    {
        $password = (string)$password;

        for ($i = 0; $i < strlen($password) - 1; $i++) {
            if ((int)$password[$i] > (int)$password[$i + 1]) {
                return false;
            }
        }

        // Remove all substrings of 3 or more same characters in a row
        $password = preg_replace('/(\d)\1{2,}/', '', $password);

        if (!preg_match('/(\d)\1/', $password)) {
            return false;
        }

        return true;
    }
}
