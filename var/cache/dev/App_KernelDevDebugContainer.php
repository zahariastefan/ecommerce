<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerFrW1os3\App_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerFrW1os3/App_KernelDevDebugContainer.php') {
    touch(__DIR__.'/ContainerFrW1os3.legacy');

    return;
}

if (!\class_exists(App_KernelDevDebugContainer::class, false)) {
    \class_alias(\ContainerFrW1os3\App_KernelDevDebugContainer::class, App_KernelDevDebugContainer::class, false);
}

return new \ContainerFrW1os3\App_KernelDevDebugContainer([
    'container.build_hash' => 'FrW1os3',
    'container.build_id' => '23c50f83',
    'container.build_time' => 1659026558,
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerFrW1os3');
