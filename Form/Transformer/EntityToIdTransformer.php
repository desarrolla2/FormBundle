<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 26/09/17
 * Time: 12:32
 */

namespace Desarrolla2\FormBundle\Form\Transformer;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class EntityToIdTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    protected $em;
    /**
     * @var string
     */
    protected $class;

    public function __construct(EntityManager $entityManager, $class)
    {
        $this->em = $entityManager;
        $this->class = $class;
    }

    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }
        $entity = $this->em->getRepository($this->class)->find($id);
        if (null === $entity) {
            throw new TransformationFailedException();
        }

        return $entity;
    }

    public function transform($entity)
    {
        if (null === $entity) {
            return;
        }

        return $entity->getId();
    }
}