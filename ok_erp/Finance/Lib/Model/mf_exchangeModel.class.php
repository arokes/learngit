<?php
load('@.functions');
class Mf_exchangeModel extends Model{
	protected $_validate = array(
		array('foreign_trade','require','销售方式必选选择'),
		array('company_id','require','公司必须选择'),
		array('bank_id','require','银行必须选择'),
		array('bank_cust','require','客户必选填写'),
		array('pay_dd','require','汇款日期必须填写'),
		array('sal_name','require','销售员必须输入'),
		array('amount','require','到账金额必须输入'),
		);

	protected $_auto = array(
		array('record_dd','get_client_time',1,'function'),
		array('change_dd','get_client_time',2,'function'),
		);
}