<?php
/**
 * TOP API: taobao.wlb.wms.order.cancel.notify request
 * 
 * @author auto create
 * @since 1.0, 2015.04.06
 */
class WlbWmsOrderCancelNotifyRequest
{
	/** 
	 * 订单类型
	 **/
	private $orderCode;
	
	/** 
	 * 订单编号201	一般交易出库单 202	 B2B交易出库单 502	 换货出库 503	补发出库 　	　 901	退供出库 301	调拨出库 603	B2B出库 505	领用出库 903	其他出库 　	　 601	 采购入库 302	调拨入库 602	B2B入库 504	归还入库 902	其他入库 501	销退入库 504	换货入库 　	　 1002	仓内加工单 701	盘点出库（盘亏） 702	盘点入库（盘盈）
	 **/
	private $orderType;
	
	/** 
	 * 仓库编码
	 **/
	private $storeCode;
	
	private $apiParas = array();
	
	public function setOrderCode($orderCode)
	{
		$this->orderCode = $orderCode;
		$this->apiParas["order_code"] = $orderCode;
	}

	public function getOrderCode()
	{
		return $this->orderCode;
	}

	public function setOrderType($orderType)
	{
		$this->orderType = $orderType;
		$this->apiParas["order_type"] = $orderType;
	}

	public function getOrderType()
	{
		return $this->orderType;
	}

	public function setStoreCode($storeCode)
	{
		$this->storeCode = $storeCode;
		$this->apiParas["store_code"] = $storeCode;
	}

	public function getStoreCode()
	{
		return $this->storeCode;
	}

	public function getApiMethodName()
	{
		return "taobao.wlb.wms.order.cancel.notify";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->orderCode,"orderCode");
		RequestCheckUtil::checkNotNull($this->orderType,"orderType");
		RequestCheckUtil::checkNotNull($this->storeCode,"storeCode");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
