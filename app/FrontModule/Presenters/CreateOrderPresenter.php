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
use App\Model\Facades\SizeFacade;
use App\Model\Facades\UsersFacade;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Multiplier;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;

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
    private SizeFacade $sizeFacade;


    /**
     * Akce pro zobrazení jednoho produktu
     * @param int $cartId
     * @throws \Exception
     */
    public function renderCreateOrderForm(int $cartId):void {
        $this->template->cart=$this->cartFacade->getCartById($cartId);
        $sizes = [];
        foreach ($this->template->cart->items as $item) {
            if ($item->sizeId !== null) {
                $sizes[$item->cartItemId] = $this->sizeFacade->getSize($item->sizeId)->getData(['size'])['size'];
            }
        }
        $this->template->sizes = $sizes;
        $form = $this->getComponent('createOrderForm');
        $form->setDefaults(['cartId' => $cartId]);
    }

    protected function createComponentCreateOrderForm():CreateOrderForm {
        $form = $this->createOrderFormFactory->create();

        $form->onSubmit[]=function(CreateOrderForm $form){
            $cart = $this->cartFacade->getCartById($form->values->cartId);
            $order = new Orders();
            $order->status = 1;
            $order->price = $cart->getTotalPrice();
            $order->user = $this->usersFacade->getUser($this->user->getId());
            $order->address = $form->values->address;
            $order->city = $form->values->city;
            $order->psc = (int) $form->values->psc;
            $order->telephone = (int) $form->values->telephone;
            $order->email = $form->values->email;
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

            $this->cartFacade->deleteCartByUser($this->user->getId());

            #region příprava textu mailu
            $mail = new Message();
            $mail->setFrom('pelj03@vse.cz');
            $mail->addTo($form->values->email);
            $mail->subject = 'Vytvoření objednávky';
            $mail->htmlBody = 'Vaše objednávka byla vytvořena. Celková cena je: ' . $order->price . ' Kč';
            #endregion endregion příprava textu mailu

            //odeslání mailu pomocí PHP funkce mail
            $mailer = new SendmailMailer();
            $mailer->send($mail);

            $this->flashMessage('Objednávka vytvořena');
            $this->redirect('Product:list');
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
    public function injectSizeFacade(SizeFacade $sizeFacade):void {
        $this->sizeFacade = $sizeFacade;
    }
}