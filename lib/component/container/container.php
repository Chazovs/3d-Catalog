<?php

namespace Chazov\Unimarket\Component\Container;

use Chazov\Unimarket\Component\Builder\CatalogResponseBuilder;
use Chazov\Unimarket\Component\ConfigProvider;
use Chazov\Unimarket\Component\Constants;
use Chazov\Unimarket\Component\Repository\CatalogRepository;
use Chazov\Unimarket\Component\Logger\Logger;
use Chazov\Unimarket\Component\Container\NotFoundException;
use Chazov\Unimarket\Service\CatalogService;
use ReflectionClass;
use ReflectionException;

/**
 * Class Container
 * @package Chazov\Unimarket\Component\Container
 */
class Container implements ContainerInterface
{
    /**
     * Содержит готовые к использованию сервисы
     *
     * @var array
     */
    private $serviceStore = [];
    private $servicesConfigs = [];

    public function __construct()
    {
        $this->servicesConfigs = $this->getContainerConfig();
    }

    /**
     * @param string $id
     * @return mixed|null
     * @throws NotFoundException
     */
    public function get(string $id)
    {
        if (!$this->has($id)) {
            throw new NotFoundException('Service not found: ' . $id);
        }

        try {
            if (!isset($this->serviceStore[$id])) {
                $this->serviceStore[$id] = $this->createService($id);
            }
        } catch (LockServiceException | ReflectionException $exception) {
            return null;
        }

        return $this->serviceStore[$id]['service'];
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has(string $id): bool
    {
        return isset($this->servicesConfigs[$id]);
    }

    /**
     * @param string $serviceName
     * @return object
     * @throws LockServiceException
     * @throws NotFoundException
     * @throws ReflectionException
     */
    private function createService(string $serviceName)
    {
        if (isset($this->serviceStore[$serviceName]['lock'])) {
            throw new LockServiceException();
        }

        if (!isset($this->servicesConfigs[$serviceName])) {
            throw new NotFoundException();
        }

        $this->serviceStore[$serviceName]['lock'] = true;

        $reflector = new ReflectionClass($serviceName);

        $arguments = $this->getArguments($this->servicesConfigs[$serviceName]);

        return $reflector->newInstanceArgs($arguments);
    }

    /**
     * @throws ReflectionException
     * @throws LockServiceException
     * @throws NotFoundException
     */
    public function configContainer(): void
    {
        foreach ($this->servicesConfigs as $serviceName => $serviceConfig) {
            $this->serviceStore[$serviceName]['service'] = $this->createService($serviceName);
        }
    }

    /**
     * @return array
     */
    private function getContainerConfig(): array
    {
        return [
            Logger::class                 => [
                [
                    'type'  => Constants::simpleType,
                    'value' => ConfigProvider::getFilePath(),
                ],
            ],
            CatalogRepository::class      => [
                [
                    'type'  => Constants::entity,
                    'value' => Logger::class,
                ],
            ],
            CatalogResponseBuilder::class => [
                [
                    'type'  => Constants::entity,
                    'value' => CatalogRepository::class,
                ],
            ],
            CatalogService::class         => [
                [
                    'type'  => Constants::entity,
                    'value' => CatalogRepository::class,
                ],
                [
                    'type'  => Constants::entity,
                    'value' => CatalogResponseBuilder::class,
                ],
            ],
        ];

    }

    /**
     * @param array $arguments
     * @return array
     * @throws NotFoundException
     */
    private function getArguments(array $arguments): array
    {
        $resultArgs = [];

        foreach ($arguments as $key => $argument) {
            if ($argument['type'] === Constants::simpleType) {
                $resultArgs[] = $argument['value'];
            } else {
                $resultArgs[] = $this->get($argument['value']);
            }
        }

        return $resultArgs;
    }
}
