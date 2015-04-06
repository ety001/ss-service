<?php
/**
 * TOP API: taobao.waimai.active.item.del request
 * 
 * @author auto create
 * @since 1.0, 2015.04.06
 */
class WaimaiActiveItemDelRequest
{
	/** 
	 * 删除限时菜活动
	 **/
	private $delItemActive;
	
	private $apiParas = array();
	
	public function setDelItemActive($delItemActive)
	{
		$this->delItemActive = $delItemActive;
		$this->apiParas["del_item_active"] = $delItemActive;
	}

	public function getDelItemActive()
	{
		return $this->delItemActive;
	}

	public function getApiMethodName()
	{
		return "taobao.waimai.active.item.del";
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
