<?php

namespace App\Model\Facades;

use App\Model\Entities\Orders;
use App\Model\Entities\Size;
use App\Model\Repositories\OrdersRepository;
use App\Model\Repositories\SizeRepository;

/**
 * Class OrdersFacade - fasáda pro objednávky
 * @package App\Model\Facades
 */
class OrdersFacade {
    private OrdersRepository $orderRepository;

    public function __construct(OrdersRepository $orderRepository){
        $this->orderRepository=$orderRepository;
    }

    /**
     * Metoda pro načtení jedné objednavky
     * @param int $id
     * @return Orders
     * @throws \Exception
     */
    public function getOrder(int $id):Orders {
        return $this->orderRepository->find($id); //buď počítáme s možností vyhození výjimky, nebo ji ošetříme už tady a můžeme vracet např. null
    }

    /**
     * Metoda pro vyhledání objednavek
     * @param array|null $params = null
     * @param int|null $offset = null
     * @param int|null $limit = null
     * @return Size[]
     */
    public function findOrders(array $params=null,int $offset=null,int $limit=null):array {
        return $this->orderRepository->findAllBy($params,$offset,$limit);
    }

    /**
     * Metoda pro zjištění počtu objednavek
     * @param array|null $params
     * @return int
     */
    public function findOrdersCount(array $params=null):int {
        return $this->orderRepository->findCountBy($params);
    }

    /**
     * Metoda pro uložení objednavky
     * @param Orders $order
     * @return bool - true, pokud byly v DB provedeny nějaké změny
     */
    public function saveOrder(Orders &$order):bool {
        return (bool)$this->orderRepository->persist($order);
    }

    /**
     * Metoda pro smazání objednavek
     * @param Orders $order
     * @return bool
     */
    public function deleteOrder(Orders $order):bool {
        try{
            return (bool)$this->orderRepository->delete($order);
        }catch (\Exception $e){
            return false;
        }
    }

}