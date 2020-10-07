<?php
namespace Mantonio84\pymMagicBox;
use \Mantonio84\pymMagicBox\Models\pmbPayment;
use \Mantonio84\pymMagicBox\Models\pmbLog;

class Payment extends BaseOnModel {
	
	public $is_refundable=false;
			
	public function __construct(string $merchant_id, $ref){
		$this->acceptMerchantId($merchant_id);
		if ($ref instanceof pmbPayment){
			$this->managed=$ref;
		}else{
			$this->managed=$this->findPaymentOrFail($ref);
		}
		$this->is_refundable=$this->engine()->isRefundable();		
		pmbLog::write("DEBUG", $this->merchant_id, ["re" => $ref, "pe" => $this->managed->performer, "message" => "Created a 'Payment' class"]);
	}
			
	public function engine(){
		return $this->getEngine($this->managed->performer);
	}
	
	public function method(){
		return $this->managed->performer->method;
	}
        
        public function alias(){
            return $this->managed->alias;
        }
			
	public function confirm(array $other_data=[]){
		return $this->wrapPaymentModel($this->engine()->confirm($this->managed,$other_data));
	}
	
	public function refund(array $other_data=[]){
		return  $this->wrapPaymentModel($this->engine()->refund($this->managed,$other_data));
	}
	
	protected function isReadableAttribute(string $name) : bool{
		return true;
	}
	
	protected function isWriteableAttribute(string $name, $value) : bool{
		return false;
	}
        
        protected function wrapPaymentModel($ret){
            if ($ret instanceof pmbPayment){
                $this->managed=$ret;                
            }
            return $this;
	}
}