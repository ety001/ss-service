<?php
/**
 * TOP API: taobao.waimai.shop.business.add request
 * 
 * @author auto create
 * @since 1.0, 2015.04.06
 */
class WaimaiShopBusinessAddRequest
{
	/** 
	 * 店铺营业资料
	 **/
	private $takeoutShopBusinessInfo;
	
	private $apiParas = array();
	
	public function setTakeoutShopBusinessInfo($takeoutShopBusinessInfo)
	{
		$this->takeoutShopBusinessInfo = $takeoutShopBusinessInfo;
		$this->apiParas["takeout_shop_business_info"] = $takeoutShopBusinessInfo;
	}

	public function getTakeoutShopBusinessInfo()
	{
		return $this->takeoutShopBusinessInfo;
	}

	public function getApiMethodName()
	{
		return "taobao.waimai.shop.business.add";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
