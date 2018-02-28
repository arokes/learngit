<?php
load("@.functions");
class tf_exchangeModel extends Model{

	//protected $_validate = array();

	protected $_auto = array(
		array('record_dd','get_client_time','2','function'),
		array('change_dd','get_client_time','1','function'),
		);
}