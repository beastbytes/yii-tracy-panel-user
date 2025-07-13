The Yii Tracy Panel User package is a panel for [Yii Tracy](https://github.com/beastbytes/yii-tracy)
(integration of the [Tracy debugging tool](https://tracy.nette.org/)into [Yii3](https://www.yiiframework.com/))
that provides information about the current user.

## Requirements
- PHP 8.1 or higher

## Installation
Install the package using [Composer](https://getcomposer.org):

Either:
```shell
composer require-dev beastbytes/yii-tracy-panel-user
```
or add the following to the `require-dev` section of your `composer.json`
```json
"beastbytes/yii-tracy-panel-user": "<version_constraint>"
```

## Information Displayed
### Tab
By default the current user ID is shown. This can be changed by setting `StabValue` in the constructor (see Tab Value).

### Panel
The panel shows:
* the current user ID
* assigned Roles (if RBAC enabled)
* granted Permissions (if RBAC enabled)
* user defined parameters (see Panel Parameters)

The information shown can be customised.
### Customisation
#### Tab Value
By default, the current user ID is shown on the tab. This can be changed by setting `StabValue` in the constructor.

`$tabValue` is an anonymous function that takes the current identity (`IdentityInterface`) as its parameter
and returns a string.

#### User Parameters
Additional information about the current user can be shown on the user panel by setting `$userparameters`
in the constructor.

`$userParameters` is an anonymous function that takes the current identity (`IdentityInterface`) as its parameter
and returns parameters to show as `array{string: string}` 
where the key is the name of the parameter and value its value.

#### id2pk
Depending on how user IDs - Primary Keys - are stored in the database,
if using (RBAC)[https://github.com/yiisoft/rbac] it may be necessary to convert the user ID
in order to get user Roles and Permissions.
An example is when using [UUIDs](https://en.wikipedia.org/wiki/Universally_unique_identifier) as the primary key
and storing it in the database in binary format.

`id2pk` is an anonymous function that takes the string representation of the user ID as its parameter
and returns the Primary Key representation.

## License
The BeastBytes Yii Tracy Panel User package is free software. It is released under the terms of the BSD License.
Please see [`LICENSE`](./LICENSE.md) for more information.
