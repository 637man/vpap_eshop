<?php

namespace App\Model\Entities;

use DateTime;
use LeanMapper\Entity;

/**
 * Class Order
 * @package App\Model\Entities
 * @property int $orderId
 * @property int $userId
 * @property float $price
 * @property int $status
 * @property DateTime $created
 */
class Orders extends Entity implements \Nette\Security\Resource{

    /**
     * @inheritDoc
     */
    function getResourceId():string{
        return 'Orders';
    }
}