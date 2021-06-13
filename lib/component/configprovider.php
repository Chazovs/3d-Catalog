<?php


namespace Chazov\Unimarket\Component;


class ConfigProvider
{

    /**
     * @return string
     */
    public static function getFilePath(): string
    {
        return $_SERVER['DOCUMENT_ROOT'] . Constants::defaultLogFileName;
    }
}