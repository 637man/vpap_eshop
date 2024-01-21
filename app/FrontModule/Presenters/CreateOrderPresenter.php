<?php

namespace App\FrontModule\Presenters;

use App\FrontModule\Components\CartControl\CartControl;
use App\FrontModule\Components\CreateOrderForm\CreateOrderForm;
use App\FrontModule\Components\CreateOrderForm\CreateOrderFormFactory;
use App\FrontModule\Components\ProductCartForm\ProductCartForm;
use App\FrontModule\Components\ProductCartForm\ProductCartFormFactory;
use App\Model\Entities\OrderItem;
use App\Model\Entities\Orders;
use App\Model\Facades\CartFacade;
use App\Model\Facades\OrderItemFacade;
use App\Model\Facades\OrdersFacade;
use App\Model\Facades\ProductsFacade;
use App\Model\Facades\UsersFacade;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Multiplier;

/**
 * Class CreateOrderPresenter
 * @package App\FrontModule\Presenters
 */
class CreateOrderPresenter extends BasePresenter{
    private CreateOrderFormFactory $createOrderFormFactory;
    private OrdersFacade $ordersFacade;
    private OrderItemFacade $orderItemFacade;
    private UsersFacade $usersFacade;
    private CartFacade $cartFacade;


    /**
     * Akce pro zobrazení jednoho produktu
     * @param string $url
     * @throws BadRequestException
     */
    public function renderCreateOrderForm(int $cartId):void {
        $form = $this->getComponent('createOrderForm');
        $form->setDefaults(['cartId' => $cartId]);
    }

    protected function createComponentCreateOrderForm():CreateOrderForm {
        $form = $this->createOrderFormFactory->create();

        $form->onSubmit[]=function(ProductCartForm $form){
            $cart = $this->cartFacade->getCartById($form->values->cartId);
            $order = new Orders();
            $order->status = 1;
            $order->price = 11;
            $order->user = $this->usersFacade->getUser($this->user->getId());
            $this->ordersFacade->saveOrder($order);

            foreach ($cart->items as $item) {
                $orderItem = new OrderItem();
                $orderItem->productId = $item->product->productId;
                $orderItem->sizeId = $item->sizeId;
                $orderItem->price = $item->product->price;
                $orderItem->count = $item->count;
                $orderItem->orderId = $order->ordersId;
                $this->orderItemFacade->saveOrderItem($orderItem);
            }
            $this->redirect('this');
        };
        return $form;
    }

    public function injectCreateOrderFormFactory(CreateOrderFormFactory $createOrderFormFactory):void {
        $this->createOrderFormFactory = $createOrderFormFactory;
    }
    public function injectOrdersFacade(OrdersFacade $ordersFacade):void {
        $this->ordersFacade = $ordersFacade;
    }
    public function injectOrderItemFacade(OrderItemFacade $orderItemFacade):void {
        $this->orderItemFacade = $orderItemFacade;
    }
    public function injectUsersFacade(UsersFacade $usersFacade):void {
        $this->usersFacade = $usersFacade;
    }
    public function injectCartFacade(CartFacade $cartFacade):void {
        $this->cartFacade = $cartFacade;
    }
}