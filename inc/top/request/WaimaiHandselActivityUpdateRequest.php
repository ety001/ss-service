<?php
/**
 * TOP API: taobao.waimai.handsel.activity.update request
 * 
 * @author auto create
 * @since 1.0, 2015.04.06
 */
class WaimaiHandselActivityUpdateRequest
{
	/** 
	 * 活动信息
	 **/
	private $freeItemActiveVO;
	
	private $apiParas = array();
	
	public function setFreeItemActiveVO($freeItemActiveVO)
	{
		$this->freeItemActiveVO = $freeItemActiveVO;
		$this->apiParas["free_item_active_v_o"] = $freeItemActiveVO;
	}

	public function getFreeItemActiveVO()
	{
		return $this->freeItemActiveVO;
	}

	public function getApiMethodName()
	{
		return "taobao.waimai.handsel.activity.update";
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
