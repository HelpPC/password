<?php

use Tester\Assert;

# Načteme knihovny Testeru.
require __DIR__ . '/../vendor/autoload.php';       # při instalaci Composerem


require __DIR__ . '/../src/IPasswords.php';
require __DIR__ . '/../src/DefaultPassword.php';


Tester\Environment::setup();

$password = "\0testPassord";
$hash = \HelpPC\Passwords\DefaultPassword::hash($password);
Assert::false(empty($hash), 'Hash is empty');
Assert::false(\HelpPC\Passwords\DefaultPassword::verify('', $hash), 'Password have i security bug');


$option = ['cost' => 11];
$password = 'testPassword';
$hash = \HelpPC\Passwords\DefaultPassword::hash($password, $option);
Assert::true(!empty($hash), 'Hash is empty');

Assert::exception(function () use ($password) {
    \HelpPC\Passwords\DefaultPassword::hash($password, ['cost' => 3]);
}, \HelpPC\Exceptions\InvalidArgumentException::class);
Assert::exception(function () use ($password) {
    \HelpPC\Passwords\DefaultPassword::hash($password, ['cost' => 40]);
}, \HelpPC\Exceptions\InvalidArgumentException::class);

Assert::true(\HelpPC\Passwords\DefaultPassword::verify($password, $hash), 'Password is invalid');


