<?php

namespace Playbloom\Satisfy\Serializer;

use JMS\Serializer\Context;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\VisitorInterface;
use PhpCollection\Map;
use Playbloom\Satisfy\Model\Repository;

class RepositoryCollectionHandler
{
    /**
     * @param VisitorInterface $visitor
     * @param Map              $collection
     * @param array            $type
     * @param Context          $context
     * @return string
     */
    public function serializeCollection(VisitorInterface $visitor, Map $collection, array $type, Context $context)
    {
        // We change the base type, and pass through possible parameters.
        $type['name'] = 'array';
        $data = $collection->values();
        if ($visitor instanceof JsonSerializationVisitor) {
            $visitor->setOptions(JSON_PRETTY_PRINT);
        }

        return $visitor->visitArray($data, $type, $context);
    }

    /**
     * @param VisitorInterface $visitor
     * @param mixed            $data
     * @param array            $type
     * @param Context          $context
     * @return Map
     */
    public function deserializeCollection(VisitorInterface $visitor, $data, array $type, Context $context)
    {
        // We change the base type, and pass through possible parameters.
        $type['name'] = 'array';

        /** @var Repository[] $objects */
        $objects = $visitor->visitArray($data, $type, $context);
        $map = new Map();

        foreach ($objects as $object) {
            $map->set($object->getId(), $object);
        }

        return $map;
    }
}
