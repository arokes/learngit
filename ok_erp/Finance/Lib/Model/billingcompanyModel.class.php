<?php
load('@.functions');
class billingcompanyModel extends Model{
	protected $_validate = array(
		array('Gfmc','require','购方名称必须输入'),
		array('Gfsh','require','购方税号必须输入'),
		array('Gfyhzh','require','购方银行帐号必须输入'),
		array('Gfdzdh','require','购方地址电话必须输入'),
		array('Fhr','require','复核人必须输入'),
		array('Skr','require','收款人必须输入'),
		array('mekacompany','require','开票公司必须选择'),
		array('Gfmc','','购方名称已经存在！',0,'unique',1),
		);
	protected $_auto = array(
		array('record_dd','get_client_time',1,'function'),
		array('change_dd','get_client_time',2,'function'),
		);
}