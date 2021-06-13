<?php

namespace Chazov\Unimarket\Model\Response;

abstract class AbstractResponse
{
    /** @var bool */
    public $success = false;

    /** @var string */
    public $errMsg = [];
}