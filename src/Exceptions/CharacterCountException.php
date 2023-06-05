<?php

namespace RaisulHridoy\Laravel2FA\Exceptions;

class CharacterCountException extends \Exception
{
    protected $message = 'Secret key is too short. Must be at least 16 base32 characters!';

}
