<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\Container9tGQ9ay\App_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/Container9tGQ9ay/App_KernelDevDebugContainer.php') {
    touch(__DIR__.'/Container9tGQ9ay.legacy');

    return;
}

if (!\class_exists(App_KernelDevDebugContainer::class, false)) {
    \class_alias(\Container9tGQ9ay\App_KernelDevDebugContainer::class, App_KernelDevDebugContainer::class, false);
}

return new \Container9tGQ9ay\App_KernelDevDebugContainer([
    'container.build_hash' => '9tGQ9ay',
    'container.build_id' => 'b5e60e58',
    'container.build_time' => 1659270360,
], __DIR__.\DIRECTORY_SEPARATOR.'Container9tGQ9ay');
