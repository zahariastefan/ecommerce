<?php declare(strict_types=1);

namespace BabDev\PagerfantaBundle\DependencyInjection;

use BabDev\PagerfantaBundle\EventListener\ConvertNotValidCurrentPageToNotFoundListener;
use BabDev\PagerfantaBundle\EventListener\ConvertNotValidMaxPerPageToNotFoundListener;
use Pagerfanta\Twig\Extension\PagerfantaExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class BabDevPagerfantaExtension extends ConfigurableExtension implements PrependExtensionInterface
{
    public function getAlias(): string
    {
        return 'babdev_pagerfanta';
    }

    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $container->setParameter('babdev_pagerfanta.default_twig_template', $mergedConfig['default_twig_template']);
        $container->setParameter('babdev_pagerfanta.default_view', $mergedConfig['default_view']);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('pagerfanta.xml');

        /** @var array<string, class-string<BundleInterface>> $bundles */
        $bundles = $container->getParameter('kernel.bundles');

        if (isset($bundles['TwigBundle'])) {
            $loader->load('twig.xml');

            if (!class_exists(PagerfantaExtension::class)) {
                $container->removeDefinition('pagerfanta.twig_extension');
                $container->removeDefinition('pagerfanta.twig_runtime');
                $container->removeDefinition('pagerfanta.view.twig');
            }
        }

        if (isset($bundles['JMSSerializerBundle'])) {
            $loader->load('jms_serializer.xml');
        }

        if (interface_exists(NormalizerInterface::class)) {
            $loader->load('serializer.xml');
        }

        if (Configuration::EXCEPTION_STRATEGY_TO_HTTP_NOT_FOUND === $mergedConfig['exceptions_strategy']['out_of_range_page']) {
            $container->register('pagerfanta.event_listener.convert_not_valid_max_per_page_to_not_found', ConvertNotValidCurrentPageToNotFoundListener::class)
                ->addTag(
                    'kernel.event_listener',
                    [
                        'event' => KernelEvents::EXCEPTION,
                        'method' => 'onKernelException',
                        'priority' => 512,
                    ]
                );
        }

        if (Configuration::EXCEPTION_STRATEGY_TO_HTTP_NOT_FOUND === $mergedConfig['exceptions_strategy']['not_valid_current_page']) {
            $container->register('pagerfanta.event_listener.convert_not_valid_current_page_to_not_found', ConvertNotValidMaxPerPageToNotFoundListener::class)
                ->addTag(
                    'kernel.event_listener',
                    [
                        'event' => KernelEvents::EXCEPTION,
                        'method' => 'onKernelException',
                        'priority' => 512,
                    ]
                );
        }
    }

    public function prepend(ContainerBuilder $container): void
    {
        if (!$container->hasExtension('twig')) {
            return;
        }

        if (!class_exists(PagerfantaExtension::class)) {
            return;
        }

        $refl = new \ReflectionClass(PagerfantaExtension::class);

        if (false === $refl->getFileName()) {
            return;
        }

        $path = \dirname($refl->getFileName(), 2).'/templates/';

        $container->prependExtensionConfig('twig', ['paths' => [$path => 'Pagerfanta']]);
    }
}
