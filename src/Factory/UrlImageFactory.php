<?php

namespace App\Factory;

use App\Entity\UrlImage;
use App\Repository\UrlImageRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<UrlImage>
 *
 * @method static UrlImage|Proxy createOne(array $attributes = [])
 * @method static UrlImage[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static UrlImage|Proxy find(object|array|mixed $criteria)
 * @method static UrlImage|Proxy findOrCreate(array $attributes)
 * @method static UrlImage|Proxy first(string $sortedField = 'id')
 * @method static UrlImage|Proxy last(string $sortedField = 'id')
 * @method static UrlImage|Proxy random(array $attributes = [])
 * @method static UrlImage|Proxy randomOrCreate(array $attributes = [])
 * @method static UrlImage[]|Proxy[] all()
 * @method static UrlImage[]|Proxy[] findBy(array $attributes)
 * @method static UrlImage[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static UrlImage[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static UrlImageRepository|RepositoryProxy repository()
 * @method UrlImage|Proxy create(array|callable $attributes = [])
 */
final class UrlImageFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'url' => self::faker()->text(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(UrlImage $urlImage): void {})
        ;
    }

    protected static function getClass(): string
    {
        return UrlImage::class;
    }
}
