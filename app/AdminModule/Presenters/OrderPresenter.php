<?php

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\OrderEditForm\OrderEditForm;
use App\AdminModule\Components\OrderEditForm\OrderEditFormFactory;
use App\AdminModule\Components\SizeEditForm\SizeEditForm;
use App\AdminModule\Components\SizeEditForm\SizeEditFormFactory;
use App\Model\Facades\OrdersFacade;

class OrderPresenter extends BasePresenter{
    private OrdersFacade $orderFacade;
    private OrderEditFormFactory $orderEditFormFactory;

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
        }catch (\Exception $e){
            $this->flashMessage('Požadovaná objednavka nebyla nalezena.', 'error');
            $this->redirect('default');
        }
        $form=$this->getComponent('orderEditForm');
        $form->setDefaults($order);
        $this->template->order=$order;
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

    public function injectSizeEditFormFactory(OrderEditFormFactory $orderEditFormFactory):void {
        $this->orderEditFormFactory=$orderEditFormFactory;
    }
    #endregion injections
}
