<?php
/**
 * Created by PhpStorm.
 * User: ivanis
 * Date: 14/10/15
 * Time: 01:31
 */

namespace AppBundle\Security\Voter;

use AppBundle\Entity\Team;
use Symfony\Component\Security\Core\Authorization\Voter\AbstractVoter;
use Symfony\Component\Security\Core\User\UserInterface;

class TeamVoter extends AbstractVoter
{

    const CREATE = 'create';
    const EDIT = 'edit';
    const ADD = 'add';
    const QUIT = 'quit';
    const DELETE = 'delete';
    const TRANSFER = 'transfer';
    /**
     * Return an array of supported classes. This will be called by supportsClass.
     *
     * @return array an array of supported classes, i.e. array('Acme\DemoBundle\Model\Product')
     */
    protected function getSupportedClasses()
    {
        return array('AppBundle\Entity\Team');
    }

    /**
     * Return an array of supported attributes. This will be called by supportsAttribute.
     *
     * @return array an array of supported attributes, i.e. array('CREATE', 'READ')
     */
    protected function getSupportedAttributes()
    {
        return array(
            self::CREATE,
            self::EDIT,
            self::ADD,
            self::QUIT,
            self::DELETE,
            self::TRANSFER
        );
    }

    /**
     * Perform a single access check operation on a given attribute, object and (optionally) user
     * It is safe to assume that $attribute and $object's class pass supportsAttribute/supportsClass
     * $user can be one of the following:
     *   a UserInterface object (fully authenticated user)
     *   a string               (anonymously authenticated user).
     *
     * @param string $attribute
     * @param Team $object
     * @param UserInterface|string $user
     *
     * @return bool
     */
    protected function isGranted($attribute, $object, $user = null)
    {
        if (!($user instanceof UserInterface)) {
            return false;
        }

        switch ($attribute) {
            case self::CREATE:
            case self::QUIT:
                return true;
            case self::TRANSFER:
            case self::ADD:
            case self::DELETE:
            case self::EDIT:
                return $object->getCreatedBy() == $user;
            default :
                return false;
        }
    }
}