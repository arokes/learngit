<?php
class SuggestModel extends Model {
    // 定义自动验证
    protected $_validate    =   array(
        array('title','require','标题必须'),
        );
    // 定义自动完成
    protected $_auto    =   array(
       //array('record_dd',Date("Y-m-d H:i:s"),
       array('record_dd','get_client_time',1,'callback'),
       array()
        );
    public function get_client_time(){
        return date("Y-m-d H:i:s");
    }
}