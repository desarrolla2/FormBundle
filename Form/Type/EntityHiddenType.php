<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 26/09/17
 * Time: 12:33
 */

namespace Desarrolla2\FormBundle\Form\Type;


use Desarrolla2\FormBundle\Form\Transformer\EntityToIdTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EntityHiddenType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    protected $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new EntityToIdTransformer($this->em, $options['entity_class']);
        $builder->addModelTransformer($transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(
            [
                'entity_class' => '',
                'invalid_message' => 'The entity does not exist.',
            ]
        );
    }

    public function getParent()
    {
        return HiddenType::class;
    }
}