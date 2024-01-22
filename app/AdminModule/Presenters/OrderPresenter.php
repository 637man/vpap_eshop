<?php

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\OrderEditForm\OrderEditForm;
use App\AdminModule\Components\OrderEditForm\OrderEditFormFactory;
use App\Model\Facades\OrderItemFacade;
use App\Model\Facades\OrdersFacade;
use App\Model\Facades\ProductsFacade;
use App\Model\Facades\SizeFacade;

class OrderPresenter extends BasePresenter{
    private OrdersFacade $orderFacade;
    private OrderItemFacade $orderItemFacade;
    private OrderEditFormFactory $orderEditFormFactory;
    private SizeFacade $sizeFacade;
    private ProductsFacade $productsFacade;

    public static array $orderStatuses = [1 => 'Nová objednávka', 2 => 'V přípravě', 3 => 'Odesláno'];

    /**
     * Akce pro vykreslení seznamu objednavek
     */
    public function renderDefault():void {
        $this->template->orders=$this->orderFacade->findOrders();
    }

    /**
     * Akce pro úpravu jedné objednavky
     * @param int $id
     * @throws \Nette\Application\AbortException
     */
    public function renderEdit(int $id):void {
        try{
            $order=$this->orderFacade->getOrder($id);
            $orderItems = $this->orderItemFacade->getItemsByOrderId($id);
            $productNames = [];
            $productSizes = [];
            foreach ($this->sizeFacade->findSizes() as $size) {
                $productSizes[$size->sizeId] = $size->size;
            }
            foreach ($orderItems as $item) {
                $productNames[$item->productId] = $this->productsFacade->getProduct($item->productId)->title;
            }
        }catch (\Exception $e){
            $this->flashMessage('Nastal problem', 'error');
            $this->redirect('default');
        }
        $form=$this->getComponent('orderEditForm');
        $defaults = $order->getRowData();
        $defaults['created'] = $order->created->format('d-m-Y H:m:s');
        $defaults['ordersId'] = $order->ordersId;
        $form->setDefaults($defaults);
        $this->template->order=$order;
        $this->template->orderItems = $orderItems;
        $this->template->productSizes = $productSizes;
        $this->template->productNames = $productNames;
    }

    /**
     * Akce pro smazání objednavky
     * @param int $id
     * @throws \Nette\Application\AbortException
     */
    public function actionDelete(int $id):void {
        try{
            $order=$this->orderFacade->getOrder($id);
        }catch (\Exception $e){
            $this->flashMessage('Požadovaná objednavka nebyla nalezena.', 'error');
            $this->redirect('default');
        }

        if ($this->orderFacade->deleteOrder($order)){
            $this->flashMessage('Objednavka byla smazána.', 'info');
        }else{
            $this->flashMessage('Tuto objednavku není možné smazat.', 'error');
        }

        $this->redirect('default');
    }

    public function createComponentOrderEditForm():OrderEditForm {
        $form = $this->orderEditFormFactory->create();
        $form->onCancel[]=function(){
            $this->redirect('default');
        };
        $form->onFinished[]=function($message=null){
            if (!empty($message)){
                $this->flashMessage($message);
            }
            $this->redirect('default');
        };
        $form->onFailed[]=function($message=null){
            if (!empty($message)){
                $this->flashMessage($message,'error');
            }
            $this->redirect('default');
        };
        return $form;
    }

    #region injections
    public function injectOrderFacade(OrdersFacade $orderFacade):void {
        $this->orderFacade=$orderFacade;
    }

    public function injectOrderEditFormFactory(OrderEditFormFactory $orderEditFormFactory):void {
        $this->orderEditFormFactory=$orderEditFormFactory;
    }

    public function injectOrderItemFacade(OrderItemFacade $orderItemFacade):void {
        $this->orderItemFacade = $orderItemFacade;
    }

    public function injectSizeFacade(SizeFacade $sizeFacade):void {
        $this->sizeFacade = $sizeFacade;
    }

    public function injectProductFacade(ProductsFacade $productsFacade):void {
        $this->productsFacade = $productsFacade;
    }
    #endregion injections
}
