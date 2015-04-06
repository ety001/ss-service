<?php
/**
 * TOP API: taobao.waimai.shop.add request
 * 
 * @author auto create
 * @since 1.0, 2015.04.06
 */
class WaimaiShopAddRequest
{
	/** 
	 * 淘点点外卖店铺基本信息添加
	 **/
	private $addTakeoutShop;
	
	private $apiParas = array();
	
	public function setAddTakeoutShop($addTakeoutShop)
	{
		$this->addTakeoutShop = $addTakeoutShop;
		$this->apiParas["add_takeout_shop"] = $addTakeoutShop;
	}

	public function getAddTakeoutShop()
	{
		return $this->addTakeoutShop;
	}

	public function getApiMethodName()
	{
		return "taobao.waimai.shop.add";
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
