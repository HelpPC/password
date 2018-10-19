<?php

use Tester\Assert;

# Načteme knihovny Testeru.
require __DIR__ . '/../vendor/autoload.php';       # při instalaci Composerem


require __DIR__ . '/../src/IPasswords.php';
require __DIR__ . '/../src/DefaultPassword.php';
require __DIR__ . '/../src/BCryptPassword.php';

Tester\Environment::setup();

$password = "\0testPassord";
$hash = \HelpPC\Passwords\BCryptPassword::hash($password);
Assert::false(empty($hash), 'Hash is empty');
Assert::false(\HelpPC\Passwords\BCryptPassword::verify('', $hash), 'Password have i security bug');

$option = ['cost' => 11];
$password = 'testPassword';
$hash = \HelpPC\Passwords\BCryptPassword::hash($password, $option);
Assert::true(!empty($hash), 'Hash is empty');

Assert::exception(function () use ($password) {
    \HelpPC\Passwords\BCryptPassword::hash($password, ['cost' => 3]);
}, \Nette\InvalidArgumentException::class);
Assert::exception(function () use ($password) {
    \HelpPC\Passwords\BCryptPassword::hash($password, ['cost' => 40]);
}, \Nette\InvalidArgumentException::class);

Assert::true(\HelpPC\Passwords\BCryptPassword::verify($password, $hash), 'Password is invalid');


