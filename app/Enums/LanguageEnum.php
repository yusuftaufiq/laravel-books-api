<?php

namespace App\Enums;

/**
 * Language codes following ISO 639-1.
 *
 * @source https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
 */
enum LanguageEnum: string
{
    case Chinese = 'zh';
    case English = 'en';
    case German = 'de';
    case Hindi = 'hi';
    case Malayalam = 'ml';
    case Tamil = 'ta';
    case Telugu = 'te';
    case Indonesian = 'id';
    case Japanese = 'ja';
    case Korean = 'ko';
    case Malay = 'ms';
    case Portuguese = 'pt';
    case Spanish = 'es';
    case Thai = 'th';

    /**
     * Get query string parameter value.
     *
     * @return int
     */
    final public function value(): int
    {
        return match ($this) {
            self::Chinese => 3,
            self::English => 1,
            self::German => 15,
            self::Hindi => 8,
            self::Malayalam => 7,
            self::Tamil => 5,
            self::Telugu => 6,
            self::Indonesian => 2,
            self::Japanese => 14,
            self::Korean => 16,
            self::Malay => 9,
            self::Portuguese => 10,
            self::Spanish => 17,
            self::Thai => 12,
        };
    }
}
