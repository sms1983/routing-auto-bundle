<?php

namespace Symfony\Cmf\Bundle\RoutingAutoBundle\AutoRoute\RoutePatcher;

use Symfony\Cmf\Bundle\RoutingAutoBundle\AutoRoute\RoutePatcherInterface;
use Symfony\Cmf\Bundle\RoutingAutoBundle\AutoRoute\BuilderContext;
use Doctrine\ODM\PHPCR\DocumentManager;
use Doctrine\ODM\PHPCR\Document\Generic;
use Symfony\Cmf\Bundle\RoutingAutoBundle\Document\AutoRoute;

/**
 * This class will make the Route classes using
 * Generic documents using.
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
class GenericPatcher implements RoutePatcherInterface
{
    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    public function makeRoutes(RouteStack $routeStack)
    {
        $paths = $routeStack->getFullPaths();

        foreach ($paths as $path) {
            $doc = $this->dm->find(null, $path);

            if (null === $doc) {
                $doc = new Generic;
                $meta = $this->dm->getClassMetadata(get_class($doc));
                $meta->setIdentifierValue($doc, $path);
            }

            $routeStack->addRoute($doc);
        }
    }
}
