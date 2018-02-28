<?php
load("@.functions");
class shipmentModel extends Model{
	protected $_validate=array(
		array('warhouse_no','require','进仓单号--必须填写'),
		array('company_name','require','公司名称--必须填写'),
		array('bat_no','require','合同编号--必须填写'),
		array('send_time','require','发货时间--必须填写'),
		array('sale_name','require','业务员--必须填写'),
		array('send_add','require','发货地址--必须填写'),
		array('warehouse_file_savename','require','进仓单文件--必须上传'),
		 array('warehouse_no','','进仓单号已经存在！',0,'unique',1),
		);

	protected $_auto=array(
		array('record_dd','get_client_time',1,'function'),
		array('change_dd','get_client_time',2,'function'),
		);
}