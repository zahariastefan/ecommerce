<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\Container4Sf2u97\App_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/Container4Sf2u97/App_KernelDevDebugContainer.php') {
    touch(__DIR__.'/Container4Sf2u97.legacy');

    return;
}

if (!\class_exists(App_KernelDevDebugContainer::class, false)) {
    \class_alias(\Container4Sf2u97\App_KernelDevDebugContainer::class, App_KernelDevDebugContainer::class, false);
}

return new \Container4Sf2u97\App_KernelDevDebugContainer([
    'container.build_hash' => '4Sf2u97',
    'container.build_id' => '68985d58',
    'container.build_time' => 1659100228,
], __DIR__.\DIRECTORY_SEPARATOR.'Container4Sf2u97');
