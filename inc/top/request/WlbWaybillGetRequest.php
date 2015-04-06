<?php
/**
 * TOP API: taobao.wlb.waybill.get request
 * 
 * @author auto create
 * @since 1.0, 2015.04.06
 */
class WlbWaybillGetRequest
{
	/** 
	 * 顺丰(SF)、EMS(标准快递：EMS；经济快件：EYB)、宅急送(ZJS)、圆通(YTO)、中通(ZTO)、百世汇通(HTKY)、优速(UC)、申通(STO)、天天快递 (TTKDEX)、全峰 (QFKD)、快捷(FAST)
	 **/
	private $cpCode;
	
	/** 
	 * 发货 地址
	 **/
	private $shippingAddress;
	
	/** 
	 * 订单数据
	 **/
	private $tradeOrderInfoCols;
	
	private $apiParas = array();
	
	public function setCpCode($cpCode)
	{
		$this->cpCode = $cpCode;
		$this->apiParas["cp_code"] = $cpCode;
	}

	public function getCpCode()
	{
		return $this->cpCode;
	}

	public function setShippingAddress($shippingAddress)
	{
		$this->shippingAddress = $shippingAddress;
		$this->apiParas["shipping_address"] = $shippingAddress;
	}

	public function getShippingAddress()
	{
		return $this->shippingAddress;
	}

	public function setTradeOrderInfoCols($tradeOrderInfoCols)
	{
		$this->tradeOrderInfoCols = $tradeOrderInfoCols;
		$this->apiParas["trade_order_info_cols"] = $tradeOrderInfoCols;
	}

	public function getTradeOrderInfoCols()
	{
		return $this->tradeOrderInfoCols;
	}

	public function getApiMethodName()
	{
		return "taobao.wlb.waybill.get";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->cpCode,"cpCode");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
