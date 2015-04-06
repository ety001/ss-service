<?php
/**
 * TOP API: taobao.waimai.active.item.update request
 * 
 * @author auto create
 * @since 1.0, 2015.04.06
 */
class WaimaiActiveItemUpdateRequest
{
	/** 
	 * 修改限时菜活动
	 **/
	private $updateItemActive;
	
	private $apiParas = array();
	
	public function setUpdateItemActive($updateItemActive)
	{
		$this->updateItemActive = $updateItemActive;
		$this->apiParas["update_item_active"] = $updateItemActive;
	}

	public function getUpdateItemActive()
	{
		return $this->updateItemActive;
	}

	public function getApiMethodName()
	{
		return "taobao.waimai.active.item.update";
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
