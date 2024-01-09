<?php

namespace App\Model\Facades;

use App\Model\Entities\Size;
use App\Model\Entities\SizesToProducts;
use App\Model\Repositories\SizeRepository;
use App\Model\Repositories\SizesToProductsRepository;
use Exception;

/**
 * Class SizesToFacadeFacade
 * @package App\Model\Facades
 */
class SizesToProductsFacade{
    private SizesToProductsRepository $sizesToProductsRepository;

    public function __construct(SizesToProductsRepository $sizesToProductsRepository){
        $this->sizesToProductsRepository=$sizesToProductsRepository;
    }

    /**
     * Metoda pro vyhledání velikostí
     * @param array|null $params = null
     * @param int|null $offset = null
     * @param int|null $limit = null
     * @return SizesToProducts[]
     */
    public function findSizes(array $params=null,int $offset=null,int $limit=null):array {
        return $this->sizesToProductsRepository->findAllBy($params,$offset,$limit);
    }

    /**
     * Metoda pro uložení velikosti
     * @param SizesToProducts $size
     * @return bool - true, pokud byly v DB provedeny nějaké změny
     */
    public function saveSizesToProduct(SizesToProducts &$size):bool {
        return (bool)$this->sizesToProductsRepository->persist($size);
    }

    /**
     * Metoda pro smazání velikosti
     * @param Size $size
     * @return bool
     */
    public function deleteSizesToProduct(Size $size):bool {
        try{
            return (bool)$this->sizesToProductsRepository->delete($size);
        }catch (Exception $e){
            return false;
        }
    }

}