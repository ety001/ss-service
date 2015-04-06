<?php
/**
 * TOP API: taobao.waimai.shop.auidt request
 * 
 * @author auto create
 * @since 1.0, 2015.04.06
 */
class WaimaiShopAuidtRequest
{
	/** 
	 * 申请营业
	 **/
	private $auditShop;
	
	private $apiParas = array();
	
	public function setAuditShop($auditShop)
	{
		$this->auditShop = $auditShop;
		$this->apiParas["audit_shop"] = $auditShop;
	}

	public function getAuditShop()
	{
		return $this->auditShop;
	}

	public function getApiMethodName()
	{
		return "taobao.waimai.shop.auidt";
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
