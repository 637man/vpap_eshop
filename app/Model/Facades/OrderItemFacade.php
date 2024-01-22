<?php

namespace App\Model\Facades;

use App\Model\Entities\Cart;
use App\Model\Entities\CartItem;
use App\Model\Entities\OrderItem;
use App\Model\Entities\User;
use App\Model\Repositories\CartItemRepository;
use App\Model\Repositories\CartRepository;
use App\Model\Repositories\OrderItemRepository;
use Dibi\DateTime;
use LeanMapper\Exception\InvalidStateException;

class OrderItemFacade{
  private OrderItemRepository $orderItemRepository;

  /**
   * @param int $id
   * @return OrderItem
   * @throws \Exception
   */
  public function getOrderItemById(int $id):OrderItem {
    return $this->orderItemRepository->find($id);
  }

    /**
     * @param int $id
     * @return array
     */
    public function getItemsByOrderId(int $id):array {
        return $this->orderItemRepository->findAllBy(['order_id' => $id]);
    }

    /**
     * Metoda pro uložení
     * @param OrderItem $orderItem
     */
  public function saveOrderItem(OrderItem $orderItem):void {
    $this->orderItemRepository->persist($orderItem);
  }

    /**
     * Metoda pro smazání
     * @param OrderItem $orderItem
     * @throws InvalidStateException
     */
  public function deleteOrderItem(OrderItem $orderItem):void {
    $this->orderItemRepository->delete($orderItem);
  }

  public function __construct(OrderItemRepository $orderItemRepository){
    $this->orderItemRepository = $orderItemRepository;
  }
}