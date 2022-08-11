<?php declare(strict_types=1);

namespace BabDev\PagerfantaBundle\Serializer\Normalizer;

use Pagerfanta\PagerfantaInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class PagerfantaNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    /**
     * @param mixed $object Object to normalize
     *
     * @throws InvalidArgumentException when the object given is not a supported type for the normalizer
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        if (!$object instanceof PagerfantaInterface) {
            throw new InvalidArgumentException(sprintf('The object must be an instance of "%s".', PagerfantaInterface::class));
        }

        return [
            'items' => $this->normalizer->normalize($object->getIterator(), $format, $context),
            'pagination' => [
                'current_page' => $object->getCurrentPage(),
                'has_previous_page' => $object->hasPreviousPage(),
                'has_next_page' => $object->hasNextPage(),
                'per_page' => $object->getMaxPerPage(),
                'total_items' => $object->getNbResults(),
                'total_pages' => $object->getNbPages(),
            ],
        ];
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof PagerfantaInterface;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
