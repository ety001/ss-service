<?php
/**
 * TOP API: taobao.waimai.active.item.add request
 * 
 * @author auto create
 * @since 1.0, 2015.04.06
 */
class WaimaiActiveItemAddRequest
{
	/** 
	 * 添加菜品活动
	 **/
	private $addItemResult;
	
	private $apiParas = array();
	
	public function setAddItemResult($addItemResult)
	{
		$this->addItemResult = $addItemResult;
		$this->apiParas["add_item_result"] = $addItemResult;
	}

	public function getAddItemResult()
	{
		return $this->addItemResult;
	}

	public function getApiMethodName()
	{
		return "taobao.waimai.active.item.add";
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
