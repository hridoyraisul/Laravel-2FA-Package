<?php

namespace RaisulHridoy\Laravel2FA\Exceptions;

class GoogleAuthenticateFailedException extends \Exception
{
    protected $message = 'This secret key is not compatible with Google Authenticator.';
}
