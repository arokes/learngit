<?php
class billingdataModel extends Model{
	protected $_validate=array(
		array('contract_no','require','合同协议号--必须填写',1),
		array('sale_name','require','业务员名字--必须填写',1),
		array('contract_no','','合同协议号已经存在！',0,'unique',1),
		array('contract_no','require','合同协议号--必须填写',1),
		array('contract_no','require','合同协议号--必须填写',1),
		array('contract_no','require','合同协议号--必须填写',1),
		array('contract_no','require','合同协议号--必须填写',1),
		);

	protected $_auto=array(
		//array('record_dd','get_client_time',1,'function'),
		//array('change_dd','get_client_time',2,'function'),
		);
}