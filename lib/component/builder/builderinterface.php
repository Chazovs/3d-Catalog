<?php


namespace Chazov\Unimarket\Component\Builder;

/**
 * Interface BuilderInterface
 * @package Chazov\Unimarket\Component\Builder
 */
interface BuilderInterface
{
    /**
     * @return BuilderInterface
     */
    public function reset(): BuilderInterface;

    /**
     * @return BuilderInterface
     */
    public function build(): BuilderInterface;

    /**
     * @return mixed
     */
    public function getResult();
}