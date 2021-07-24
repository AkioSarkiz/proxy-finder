# proxy-finder

## Installation

You can install the package via composer:

```bash
composer require akiosarkiz/proxy-finder
```

## Usage

```php
$finder = app(\AkioSarkiz\Contracts\ProxyFinderInterface::class);
$proxyData = $finder->find(); 
```
