<?php

namespace Nalgoo\Container;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

trait ReturnTypeContainerTrait
{
	/**
	 * Array of discovered entries, where key is the name of class and value is the name of getter method
	 *
	 * @var array|null
	 */
	protected $discoveredReturnTypes;

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
		if (!$this->has($id)) {
			throw new NotFoundException();
		}

		return call_user_func([$this, $this->discoveredReturnTypes[$id]]);
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
		$this->discoverReturnTypes();

		return array_key_exists($id, $this->discoveredReturnTypes);
	}

	/**
	 * @throws \ReflectionException
	 * @throws \RuntimeException
	 */
	protected function discoverReturnTypes()
	{
		if (!is_array($this->discoveredReturnTypes)) {

			$reflectionClass = new \ReflectionClass($this);

			foreach ($reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $reflectionMethod) {
				if (!$returnType = $reflectionMethod->getReturnType()) {
					continue;
				}
				
				$returnTypeName = $returnType->getName();
				
				if (array_key_exists($returnTypeName, $this->discoveredReturnTypes)) {
					throw new \RuntimeException(
						sprintf('Entry with return type of "%s" is defined more than once', $returnTypeName)
					);
				}

				$this->discoveredReturnTypes[$returnTypeName] = $reflectionMethod->getName();
			}
		}
	}

}
