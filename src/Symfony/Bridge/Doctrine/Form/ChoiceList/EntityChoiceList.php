<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bridge\Doctrine\Form\ChoiceList;

use Symfony\Component\Form\Exception\FormException;
use Doctrine\Common\Persistence\ObjectManager;

class EntityChoiceList extends DoctrineChoiceList
{
    private $unitOfWork;

    public function __construct(ObjectManager $manager, $class, $property = null, EntityLoaderInterface $entityLoader = null, $choices = array(), $groupBy = null)
    {
        $this->unitOfWork = $manager->getUnitOfWork();

        parent::__construct($manager, $class, $property, $entityLoader, $choices, $groupBy);
    }

    /**
     * Returns the values of the identifier fields of an entity
     *
     * Doctrine must know about this entity, that is, the entity must already
     * be persisted or added to the identity map before. Otherwise an
     * exception is thrown.
     *
     * @param  object $entity  The entity for which to get the identifier
     * @return array
     * @throws FormException   If the entity does not exist in Doctrine's
     *                         identity map
     */
    public function getIdentifierValues($entity)
    {
        if (!$this->unitOfWork->isInIdentityMap($entity)) {
            throw new FormException('Entities passed to the choice field must be managed');
        }

        return $this->unitOfWork->getEntityIdentifier($entity);
    }
}
