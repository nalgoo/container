Install
=======

```
composer require nalgoo/return-type-container
```

Usage
=====

Either `use ReturnTypeContainerTrait` in your service class or extend `ReturnTypeContainer` 

__Example:__

```

class ServiceContainer 
{
	use ReturnTypeContainerTrait;
	
	public function getDatabaseConnection(): Connection
	{
		$connection = new Connection();
		
		... 
		
		return $connection;
	}
	
	public function getLogger(): LoggerInterface
	{
		static $logger;
		
		return $logger ?: $logger = new Logger();
	}
	
	// this ReturnType won't be accessible by `get` method 
	private function getDependency(): Dependency
	{
		return new Dependency();
	}
	
}


```

ContainerChain
==============

Helper class to be able to chain multiple PSR-11 containers.

__Example:__

```

$containerChain = new ContainerChain(
	new FirstToSearchContainer(),
	new SecondToSearchContainer()
);

$app = new App($containerChain);

```
