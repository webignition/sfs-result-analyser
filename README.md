# SFS Result Analyser

Analyses results from [api.stopforumspam.com][sfs-usage], helps you figure out what to do.

## Why?

You can use [webignition/sfs-client](https://github.com/webignition/sfs-client) to query 
[api.stopforumspam.com][sfs-usage] and get back one or more
[webignition\SfsResultInterfaces\ResultInterface](https://github.com/webignition/sfs-result-interfaces)
objects.

You've probably queried [api.stopforumspam.com][sfs-usage] because you need to know if a given 
email address, IP address or username is known to be untrustworthy.

This helps with that.

## Installation

`composer require webignition/sfs-result-analyser`

## Usage

```php
use webignition\SfsResultAnalyser\Analyser;
use webignition\SfsResultInterfaces\ResultInterface;

// We're assuming that $result is a ResultInterface object.

$analyser = new Analyser();

// Does a given result indicate that the entity is not to be trusted?
$analyser->isUntrustworthy($result));
// returns true or false

// Is a given result's entity trustworthy?
// Return a float between 0 (do not trust) and 1 (probably can be trusted)
$trustworthiness = $analyser->calculateTrustworthiness($result);
```

## See Also
Use [webignition/sfs-querier](https://github.com/webignition/sfs-querier) for a package that
contains [webignition/sfs-result-analyser](https://github.com/webignition/sfs-result-analyser),
[webignition/sfs-client](https://github.com/webignition/sfs-client) and provides detailed
usage instructions.

[sfs-usage]: (https://www.stopforumspam.com/usage)