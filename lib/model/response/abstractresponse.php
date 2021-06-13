<?php

namespace Chazov\Unimarket\Model\Response;

abstract class AbstractResponse
{
    /** @var bool */
    public $success;

    /** @var string */
    public $errMsg;
}