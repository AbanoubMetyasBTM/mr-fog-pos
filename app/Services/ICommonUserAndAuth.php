<?php

namespace App\Services;

interface ICommonUserAndAuth {

    public function checkEmailIsUnique(string $email, string $userType, ?int $user_id = null);

    public function reGenerateUserVerificationCode(object $user) :string;

    public function generateVerificationCode() :int;

}
