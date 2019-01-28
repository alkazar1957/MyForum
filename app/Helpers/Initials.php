<?php
namespace App\Helpers;

class Initials
{
    /**
     * Generate initials from a name
     *
     * @param string $name
     * @return string
     */
    public function generate(string $username) : string
    {
        $words = explode(' ', $username);
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1));
        }
        return $this->makeInitialsFromSingleWord($username);
    }

    /**
     * Make initials from a word with no spaces
     *
     * @param string $name
     * @return string
     */
    protected function makeInitialsFromSingleWord(string $username) : string
    {
        preg_match_all('#([A-Z]+)#', $username, $capitals);
        if (count($capitals[1]) >= 2) {
            return substr(implode('', $capitals[1]), 0, 2);
        }
        return strtoupper(substr($username, 0, 2));
    }
}