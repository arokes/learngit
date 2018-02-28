<?php
class BankModel extends Model{
	protected $_validate = array(
		array('bank_name','require','银行名字必须输入'),
		array('bank_snm','require','银行简称必须输入'),
		array('bank_snm','1,4','输入4个字符以内的简称',0,'length'),
		);
}