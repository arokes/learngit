<?php
class CompanyModel extends Model{
	protected $_validate = array(
		array('company_name','require','公司名字必须输入'),
		array('company_snm','require','公司简称必须输入'),
		array('company_snm','1,4','输入4个字符以内的简称',0,'length'),
		);
}