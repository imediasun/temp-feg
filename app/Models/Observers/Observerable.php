<?php 
namespace App\Models\Observers;

use App\Models\Sximo;

 class Observerable extends Sximo
{

    //1
    protected $_observers = array();
     
    //2
    public function attachObserver($type,$observer)
    {
       $this->_observers[$type][]=$observer;
    }
 
    //3
    public function notifyObserver($type, $data=[]){
        //4
        if(isset($this->_observers[$type])){
            //5
            foreach($this->_observers[$type] as $observer){
                //6
                if(method_exists($observer,'callback')){
                    $observer->callback($this, $type, $data);
                }                  
            }
        }
    }  
}