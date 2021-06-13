<?php

namespace Chazov\Unimarket\Model\Response;

/**
 * Class EmptyResponse
 * @package Chazov\Unimarket\Model\Response
 */
class EmptyResponse extends AbstractResponse
{
    /**
     * EmptyResponse constructor.
     * @param string $errMsg
     * @param bool $success
     */
    public function __construct(string $errMsg, bool $success)
    {
        $this->errMsg = $errMsg;
        $this->success = $success;
    }
}
