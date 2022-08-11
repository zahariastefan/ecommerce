<?php declare(strict_types=1);

namespace BabDev\PagerfantaBundle\RouteGenerator;

use Pagerfanta\Exception\RuntimeException;
use Pagerfanta\RouteGenerator\RouteGeneratorFactoryInterface;
use Pagerfanta\RouteGenerator\RouteGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class RequestAwareRouteGeneratorFactory implements RouteGeneratorFactoryInterface
{
    private UrlGeneratorInterface $router;
    private RequestStack $requestStack;
    private ?PropertyAccessorInterface $propertyAccessor;

    public function __construct(UrlGeneratorInterface $router, RequestStack $requestStack, ?PropertyAccessorInterface $propertyAccessor = null)
    {
        if (null === $propertyAccessor) {
            trigger_deprecation('babdev/pagerfanta-bundle', '3.1', 'Not passing a "%s" to the "%s" constructor is deprecated. As of 4.0, it will be a required argument.', PropertyAccessorInterface::class, self::class);
        }

        $this->router = $router;
        $this->requestStack = $requestStack;
        $this->propertyAccessor = $propertyAccessor;
    }

    public function create(array $options = []): RouteGeneratorInterface
    {
        $options = array_replace(
            [
                'routeName' => null,
                'routeParams' => [],
                'pageParameter' => '[page]',
                'omitFirstPage' => false,
            ],
            $options
        );

        if (null === $options['routeName']) {
            $request = $this->getRequest();

            if (null === $request) {
                throw new RuntimeException('The request aware route generator can not be used when there is not an active request.');
            }

            if (null !== $this->requestStack->getParentRequest()) {
                throw new RuntimeException('The request aware route generator can not guess the route when used in a sub-request, pass the "routeName" option to use this generator.');
            }

            $options['routeName'] = $request->attributes->get('_route');

            // Make sure we read the route parameters from the passed option array
            $defaultRouteParams = array_merge($request->query->all(), $request->attributes->get('_route_params', []));

            $options['routeParams'] = array_merge($defaultRouteParams, $options['routeParams']);
        }

        return new RouterAwareRouteGenerator(
            $this->router,
            null === $this->propertyAccessor ? $options : $this->propertyAccessor,
            null === $this->propertyAccessor ? [] : $options
        );
    }

    private function getRequest(): ?Request
    {
        return $this->requestStack->getCurrentRequest();
    }
}
