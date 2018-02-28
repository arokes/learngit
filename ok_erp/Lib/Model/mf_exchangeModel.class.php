<?php
load("@.functions");
class mf_exchangeModel extends Model{
	
	protected $_validate = array(
		array('pay_dd','get_client_time','日期格式不正确'),
		array('sal_name','require','销售员必须输入'),

		);
	protected $_auto = array(
		array('record_dd','get_client_time',1,'function'),
		array('change_dd','get_client_time',2,'function'),
		);
}