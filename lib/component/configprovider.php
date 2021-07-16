<?php


namespace Chazov\Unimarket\Component;


use COption;

/**
 * Class ConfigProvider
 * @package Chazov\Unimarket\Component
 */
class ConfigProvider
{
    /**
     * @return string
     */
    public static function getFilePath(): string
    {
        return $_SERVER['DOCUMENT_ROOT'] . Constants::defaultLogFileName;
    }

    /**
     * @param int $iblockId
     * @return int|null
     */
    public static function getUniFieldIdForIblock(int $iblockId): ?int
    {
        $props = json_decode(
            COption::GetOptionString(Constants::MODULE_ID, Constants::UNIMARKET_MODEL),
            true
        );

        return $props[Constants::MODULE_PREFIX . $iblockId] ?? null;
    }

    /**
     * @param array $propsIds
     * @return bool
     */
    public static function setUniFields(array $propsIds): bool
    {
        return COption::SetOptionString(
            Constants::MODULE_ID,
            Constants::UNIMARKET_MODEL,
            json_encode($propsIds)
        );
    }
}