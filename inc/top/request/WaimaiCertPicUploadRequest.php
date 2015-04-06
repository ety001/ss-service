<?php
/**
 * TOP API: taobao.waimai.cert.pic.upload request
 * 
 * @author auto create
 * @since 1.0, 2015.04.06
 */
class WaimaiCertPicUploadRequest
{
	/** 
	 * 图片字节流
	 **/
	private $picBytes;
	
	private $apiParas = array();
	
	public function setPicBytes($picBytes)
	{
		$this->picBytes = $picBytes;
		$this->apiParas["pic_bytes"] = $picBytes;
	}

	public function getPicBytes()
	{
		return $this->picBytes;
	}

	public function getApiMethodName()
	{
		return "taobao.waimai.cert.pic.upload";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->picBytes,"picBytes");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
