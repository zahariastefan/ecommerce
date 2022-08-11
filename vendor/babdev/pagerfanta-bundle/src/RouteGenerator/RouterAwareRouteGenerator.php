<?php declare(strict_types=1);

namespace BabDev\PagerfantaBundle\RouteGenerator;

use Pagerfanta\Exception\InvalidArgumentException;
use Pagerfanta\RouteGenerator\RouteGeneratorInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyAccess\PropertyPath;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class RouterAwareRouteGenerator implements RouteGeneratorInterface
{
    private UrlGeneratorInterface $router;
    private ?PropertyAccessorInterface $propertyAccessor;
    private array $options;

    /**
     * @param array|PropertyAccessorInterface|null $propertyAccessor A property accessor or the generator options
     *
     * @throws InvalidArgumentException if missing required options
     */
    public function __construct(UrlGeneratorInterface $router, $propertyAccessor, array $options = [])
    {
        if (\is_array($propertyAccessor) || null === $propertyAccessor) {
            $options = $propertyAccessor;
            $propertyAccessor = null;
        } elseif (!($propertyAccessor instanceof PropertyAccessorInterface)) {
            throw new InvalidArgumentException(sprintf('The $propertyAccessor argument of the "%s" constructor must be an array or instance of %s, "%s" given.', self::class, PropertyAccessorInterface::class, get_debug_type($propertyAccessor)));
        }

        // Check missing options
        if (!isset($options['routeName'])) {
            throw new InvalidArgumentException(sprintf('The "%s" class options requires a "routeName" parameter to be set.', self::class));
        }

        if (null === $propertyAccessor) {
            trigger_deprecation('babdev/pagerfanta-bundle', '3.1', 'Not passing a "%s" to the "%s" constructor is deprecated. As of 4.0, it will be a required argument.', PropertyAccessorInterface::class, self::class);
        }

        $this->router = $router;
        $this->options = $options;
        $this->propertyAccessor = $propertyAccessor;
    }

    public function __invoke(int $page): string
    {
        $pageParameter = $this->options['pageParameter'] ?? '[page]';
        $omitFirstPage = $this->options['omitFirstPage'] ?? false;
        $routeParams = $this->options['routeParams'] ?? [];
        $referenceType = $this->options['referenceType'] ?? UrlGeneratorInterface::ABSOLUTE_PATH;

        $pagePropertyPath = new PropertyPath($pageParameter);
        $propertyAccessor = $this->getPropertyAccessor();

        if ($omitFirstPage) {
            $propertyAccessor->setValue($routeParams, $pagePropertyPath, $page > 1 ? $page : null);
        } else {
            $propertyAccessor->setValue($routeParams, $pagePropertyPath, $page);
        }

        return $this->router->generate($this->options['routeName'], $routeParams, $referenceType);
    }

    private function getPropertyAccessor(): PropertyAccessorInterface
    {
        if (null === $this->propertyAccessor) {
            $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
        }

        return $this->propertyAccessor;
    }
}
