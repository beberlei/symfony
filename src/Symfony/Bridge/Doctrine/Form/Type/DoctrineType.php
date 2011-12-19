<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bridge\Doctrine\Form\Type;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormBuilder;
use Symfony\Bridge\Doctrine\Form\ChoiceList\DoctrineChoiceList;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityLoaderInterface;
use Symfony\Bridge\Doctrine\Form\EventListener\MergeCollectionListener;
use Symfony\Bridge\Doctrine\Form\DataTransformer\EntitiesToArrayTransformer;
use Symfony\Bridge\Doctrine\Form\DataTransformer\EntityToIdTransformer;
use Symfony\Component\Form\AbstractType;

abstract class DoctrineType extends AbstractType
{
    /**
     * @var ManagerRegistry
     */
    protected $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        if ($options['multiple']) {
            $builder
                ->addEventSubscriber(new MergeCollectionListener())
                ->prependClientTransformer(new EntitiesToArrayTransformer($options['choice_list']))
            ;
        } else {
            $builder->prependClientTransformer(new EntityToIdTransformer($options['choice_list']));
        }
    }

    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array(
            'em'                => null,
            'class'             => null,
            'property'          => null,
            'query_builder'     => null,
            'loader'            => null,
            'choices'           => array(),
            'group_by'          => null,
        );

        $options = array_replace($defaultOptions, $options);

        if (!isset($options['choice_list'])) {
            $manager = $this->registry->getManager($options['em']);
            if (isset($options['query_builder'])) {
                $options['loader'] = $this->getLoader($manager, $options);
            }

            $defaultOptions['choice_list'] = $this->getChoiceList(
                $manager,
                $options['class'],
                $options['property'],
                $options['loader'],
                $options['choices'],
                $options['group_by']
            );
        }

        return $defaultOptions;
    }

    /**
     * Return the default loader object.
     *
     * @param ObjectManager $manager
     * @param array $options
     * @return EntityLoaderInterface
     */
    abstract protected function getLoader(ObjectManager $manager, array $options);

    /**
     * @param ObjectManager $manager
     * @param string $class
     * @param string $property
     * @param EntityLoaderInterface $loader
     * @param array $choices
     * @param string $groupBy
     * @return DoctrineChoiceList
     */
    abstract protected function getChoiceList(ObjectManager $manager, $class, $property, EntityLoaderInterface $loader = null, $choices = array(), $groupBy = null);

    public function getParent(array $options)
    {
        return 'choice';
    }
}
