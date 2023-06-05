<?php

namespace RaisulHridoy\Laravel2FA\Exceptions;

class SecretKeyException extends \Exception
{
    protected $message = 'Invalid characters in the base32 string.';
}
