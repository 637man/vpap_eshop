<?php

namespace App\Model\Facades;

use App\Model\Entities\Size;
use App\Model\Repositories\SizeRepository;

/**
 * Class SizeFacade - fasáda pro využívání velikostí z presenterů
 * @package App\Model\Facades
 */
class SizeFacade{
    private SizeRepository $sizeRepository;

    public function __construct(SizeRepository $sizeRepository){
        $this->sizeRepository=$sizeRepository;
    }

    /**
     * Metoda pro načtení jedné velikosti
     * @param int $id
     * @return Size
     * @throws \Exception
     */
    public function getSize(int $id):Size {
        return $this->sizeRepository->find($id); //buď počítáme s možností vyhození výjimky, nebo ji ošetříme už tady a můžeme vracet např. null
    }

    /**
     * Metoda pro vyhledání velikostí
     * @param array|null $params = null
     * @param int|null $offset = null
     * @param int|null $limit = null
     * @return Size[]
     */
    public function findSizes(array $params=null,int $offset=null,int $limit=null):array {
        return $this->sizeRepository->findAllBy($params,$offset,$limit);
    }

    /**
     * Metoda pro zjištění počtu velikosti
     * @param array|null $params
     * @return int
     */
    public function findSizesCount(array $params=null):int {
        return $this->sizeRepository->findCountBy($params);
    }

    /**
     * Metoda pro uložení velikosti
     * @param Size $size
     * @return bool - true, pokud byly v DB provedeny nějaké změny
     */
    public function saveSize(Size &$size):bool {
        return (bool)$this->sizeRepository->persist($size);
    }

    /**
     * Metoda pro smazání velikosti
     * @param Size $size
     * @return bool
     */
    public function deleteSize(Size $size):bool {
        try{
            return (bool)$this->sizeRepository->delete($size);
        }catch (\Exception $e){
            return false;
        }
    }

}