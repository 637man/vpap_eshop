<?php

namespace App\Model\Entities;

use LeanMapper\Entity;

/**
 * Class Category
 * @package App\Model\Entities
 * @property int $sizeId
 * @property string $size
 */
class Size extends Entity implements \Nette\Security\Resource{

    /**
     * @inheritDoc
     */
    function getResourceId():string{
        return 'Size';
    }
}