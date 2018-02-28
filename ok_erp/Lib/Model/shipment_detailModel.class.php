<?php
load("@.functions");
class shipment_detailModel extends Model{
	protected $_validate=array(
		array('shipment_id','require','没有发货单ID-请联系管理员'),
		array('itm','require','没有项次-请联系管理员'),
		array('bat_no','require','没有订单号-请联系管理员'),
		array('prd_no','require','没有品号-请联系管理员'),
		array('prd_name','require','没有品名-请联系管理员'),
		array('send_qty','require','请填写发货数量'),
		array('packing_qty','require','请填写发货箱数'),
		array('package_volume','require','请填写货品体积'),
		array('package_weight','require','请填写货品重量'),
		)

	protected $_auto=array(
		array('record_dd','get_client_time',1,'function'),
		array('change_dd','get_client_time',2,'function'),
		);
}