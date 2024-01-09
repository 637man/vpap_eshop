<?php

namespace App\AdminModule\Presenters;

use App\Model\Facades\OrdersFacade;

class OrderPresenter extends BasePresenter{
    private OrdersFacade $orderFacade;

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
        //TODO $form=$this->getComponent('sizeEditForm');
        //$form->setDefaults($order);
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

        if (!$this->user->isAllowed($order,'delete')){
            $this->flashMessage('Tuto objednavku není možné smazat.', 'error');
            $this->redirect('default');
        }

        if ($this->orderFacade->deleteOrder($order)){
            $this->flashMessage('Objednavka byla smazána.', 'info');
        }else{
            $this->flashMessage('Tuto objednavku není možné smazat.', 'error');
        }

        $this->redirect('default');
    }

    #region injections
    public function injectOrderFacade(OrdersFacade $orderFacade):void {
        $this->orderFacade=$orderFacade;
    }
    #endregion injections


}