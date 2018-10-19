<?php
/**
 * Created by PhpStorm.
 * User: tomas
 * Date: 12.01.2018
 * Time: 23:33
 */

namespace HelpPC\Passwords;


interface IPasswords
{
    public static function hash(string $password, array $options = []);

    /**
     * Verifies that a password matches a hash.
     * @return bool
     */
    public static function verify(string $password, string $hash): bool;

    /**
     * Checks if the given hash matches the options.
     * @param  string
     * @param  array with cost (4-31)
     * @return bool
     */
    public static function needsRehash(string $hash, array $options = []): bool;

    public static function getInfo(string $hash): array;
}