<?php

use Bitrix\Main\Loader;
use Chazov\Unimarket\Component\Container\Container;
use Chazov\Unimarket\Component\Container\LockServiceException;
use Chazov\Unimarket\Component\Container\NotFoundException;

Loader::includeModule('sale');
Loader::includeModule('catalog');

global $uniContainer;

$uniContainer = new Container();

try {
    $uniContainer->configContainer();
} catch (LockServiceException $exception) {
    echo 'Обнаружен зацикливающий вызов';
    return LockServiceException::class;
} catch (NotFoundException $exception) {
    echo $exception->getMessage();
    return NotFoundException::class;
} catch (ReflectionException $exception) {
    echo $exception->getMessage();
    return ReflectionException::class;
}