<?php
namespace App\Helpers;

class ScoresHelper
{
    /**
     * @param string $card One or 2 char playing card code
     * @return int Card value
     */
    public static function card2val(string $card): int
    {
        $card = strtolower($card);
        switch ($card) {
            case 'a':
                return 14;
            case 'k':
                return 13;
            case 'q':
                return 12;
            case 'j':
                return 11;
        }

        return (int)$card;
    }

    /**
     * @param int $val Int value of playing card
     * @return string Card value
     */
    public static function val2card(int $val): string
    {
        switch ($val) {
            case 14:
                return 'Ace';
            case 13:
                return 'King';
            case 12:
                return 'Queen';
            case 11:
                return 'Jack';
        }

        return (string)$val;
    }
}
