<?php

namespace Nalgoo\Container;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Helper class to allow chaining multiple PSR-11 Containers
 *
 * If item doesn't exist in first container, it will be retrieved from second, and so on...
 *
 */
class ContainerChain implements ContainerInterface
{
	/**
	 * @var ContainerInterface[]
	 */
	private $containers;

	public function __construct(ContainerInterface ...$containers)
	{
		$this->containers = $containers;
	}

	/**
	 * Finds an entry of the container by its identifier and returns it.
	 *
	 * @param string $id Identifier of the entry to look for.
	 *
	 * @return mixed Entry.
	 * @throws ContainerExceptionInterface Error while retrieving the entry.
	 *
	 * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
	 */
	public function get($id)
	{
		foreach ($this->containers as $container) {
			if ($container->has($id)) {
				return $container->get($id);
			}
		}

		throw new NotFoundException();
	}

	/**
	 * Returns true if the container can return an entry for the given identifier.
	 * Returns false otherwise.
	 *
	 * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
	 * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
	 *
	 * @param string $id Identifier of the entry to look for.
	 *
	 * @return bool
	 */
	public function has($id)
	{
		foreach ($this->containers as $container) {
			if ($container->has($id)) {
				return true;
			}
		}

		return false;
	}

}
