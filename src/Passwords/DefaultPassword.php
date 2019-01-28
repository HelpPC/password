<?php
/**
 * Created by PhpStorm.
 * User: tomas
 * Date: 12.01.2018
 * Time: 23:34
 */

namespace HelpPC\Passwords;


use Nette\InvalidArgumentException;
use Nette\InvalidStateException;

class DefaultPassword implements IPasswords
{
    const HASH_KEY = 'algjhsdiouahwergoiuawhgiouaehnrgzdfgb23523';

    public static function appendDefaultOptions(array $options): array
    {
        if (!isset($options['cost'])) {
            $options['cost'] = 10;
        }
        return $options;
    }

    public static function fixPassword(string $password): string
    {
        return base64_encode(hash_hmac("sha512", $password, self::HASH_KEY, true));
    }

    public static function hash(string $password, array $options = [])
    {
        $options = self::appendDefaultOptions($options);
        if (isset($options['cost']) && ($options['cost'] < 4 || $options['cost'] > 31)) {
            throw new InvalidArgumentException("Cost must be in range 4-31, $options[cost] given.");
        }

        $hash = password_hash(self::fixPassword($password), PASSWORD_DEFAULT, $options);
        if ($hash === false || strlen($hash) < 60) {
            throw new InvalidStateException('Hash computed by password_hash is invalid.');
        }
        return $hash;
    }


    /**
     * Verifies that a password matches a hash.
     * @return bool
     */
    public static function verify(string $password, string $hash): bool
    {
        return password_verify(self::fixPassword($password), $hash);
    }


    /**
     * Checks if the given hash matches the options.
     * @param  string
     * @param  array with cost (4-31)
     * @return bool
     */
    public static function needsRehash(string $hash, array $options = []): bool
    {
        $options = self::appendDefaultOptions($options);
        return password_needs_rehash($hash, PASSWORD_DEFAULT, $options);
    }

    public static function getInfo(string $hash): array
    {
        return password_get_info($hash);
    }
}
