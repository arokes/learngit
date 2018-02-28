<?php
load("@.functions");
class AccountModel extends Model{
	protected $_validate=array(
		array('cust_name','require','客户名称--必须填写'),
		array('country','require','国家--必须填写'),
		array('so_no','require','订单号--必须填写'),
		array('expect_sale','require','预计出货时间--必须填写'),
		array('sal_name','require','业务员--必须填写'),
		array('receivable','require','应收账款--必须填写'),
		array('so_no','','订单号已经存在！',0,'unique',1),
		);

	protected $_auto=array(
		array('record_dd','get_client_time',1,'function'),
		array('change_dd','get_client_time',2,'function'),
		);
}