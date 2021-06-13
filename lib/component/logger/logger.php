<?php

namespace Chazov\Unimarket\Component\Logger;

/**
 * Class Logger
 * @package Chazov\Unimarket\Component\Logger
 */
class Logger implements LoggerInterface
{
    /**
     * @var string
     */
    private $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath =  $filePath;
    }

    /**
     * Interpolates context values into the message placeholders.
     */
    public function interpolate($message, array $context = []): string
    {
        // build a replacement array with braces around the context keys
        $replace = [];
        foreach ($context as $key => $val) {
            // check that the value can be cast to string
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);

    }

    public function emergency(string $message, array $context = [])
    {
        // TODO: Implement emergency() method.
    }

    public function alert(string $message, array $context = [])
    {
        // TODO: Implement alert() method.
    }

    public function critical(string $message, array $context = [])
    {
        // TODO: Implement critical() method.
    }

    public function error(string $message, array $context = [])
    {
        // TODO: Implement error() method.
    }

    public function warning(string $message, array $context = [])
    {
        // TODO: Implement warning() method.
    }

    public function notice(string $message, array $context = [])
    {
        // TODO: Implement notice() method.
    }

    public function info(string $message, array $context = [])
    {
        // TODO: Implement info() method.
    }

    public function debug(string $message, array $context = [])
    {
        // TODO: Implement debug() method.
    }

    /**
     * @param mixed $level
     * @param string $message
     * @param array $context
     */
    public function log($level, string $message, array $context = []): void
    {
        if (count($context) > 0) {
            $resultMsg = $level. ': [' . date('Y-m-d H:i:s') . '] '  . $this->interpolate($message, $context);
        } else {
            $resultMsg = $level. ': [' . date('Y-m-d H:i:s') . '] '  . $message;
        }

        $this->write($resultMsg);
    }

    /**
     * @param string $resultMsg
     */
    private function write(string $resultMsg): void
    {
        file_put_contents( $this->filePath , $resultMsg , FILE_APPEND);
    }
}