<?php

namespace AppBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class IdToEntityTransformer implements DataTransformerInterface
{
    private $manager;
    private $className;

    public function __construct(ObjectManager $manager, string $className)
    {
        $this->manager = $manager;
        $this->className = $className;
    }

    /**
     * Transforms an object (the entity pointed to by $className) to an ID (primitive).
     *
     * @param  mixed|null $entity
     * @return int|string
     */
    public function reverseTransform($entity)
    {
        if (null === $entity) {
            return '';
        }

        $meta = $this->manager->getClassMetadata($this->className);
        return $meta->getFieldValue($entity, $meta->getSingleIdentifierFieldName());
    }

    /**
     * Transforms an ID (number) to an object (the entity pointed to by $className).
     *
     * @param  int|string $id
     * @return mixed|null
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function transform($id)
    {
        // no issue number? It's optional, so that's ok
        if (!$id) {
            return null;
        }

        $entity = $this->manager
            ->getRepository($this->className)
            // query for the issue with this id
            ->find($id)
        ;

        if (null === $entity) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An entity with id "%s" does not exist!',
                $id
            ));
        }

        return $entity;
    }
}