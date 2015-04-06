<?php
/**
 * TOP API: taobao.waimai.handsel.activity.add request
 * 
 * @author auto create
 * @since 1.0, 2015.04.06
 */
class WaimaiHandselActivityAddRequest
{
	/** 
	 * 活动信息
	 **/
	private $freeItemActivityVO;
	
	private $apiParas = array();
	
	public function setFreeItemActivityVO($freeItemActivityVO)
	{
		$this->freeItemActivityVO = $freeItemActivityVO;
		$this->apiParas["free_item_activity_v_o"] = $freeItemActivityVO;
	}

	public function getFreeItemActivityVO()
	{
		return $this->freeItemActivityVO;
	}

	public function getApiMethodName()
	{
		return "taobao.waimai.handsel.activity.add";
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
