modules-di
==========

A very simple DI implementations using modules

v2 Upgrade Notes
================

1. `Module` class was renamed to `LegacyModule`

2. The entrypoint is now the `Application` instance

```php
$app = new Application(
    new SomeModule(...)
);

$app->get('router')->handle($url);

```

3. `LegacyModuleWrapper` can help to transition from `LegacyModule`. Use the `LegacyAdapterModule` to inject `ModuleManager`

```php

$module = new LegacyModuleWrapper(
    new LegacyModule(...),
);

$app = new Application(
    new LegacyAdapterModule($manager),
    $module
);

```

