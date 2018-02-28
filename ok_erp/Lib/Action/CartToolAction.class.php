<?php
 
 
class CartTool {
    private static $ins = null;
    private $items = array();
 
    final protected function __construct() {
    }
 
    final protected function __clone() {
    }
 
    // 获取实例
    protected static function getIns() {
        if(!(self::$ins instanceof self)) {
            self::$ins = new self();
        }
 
        return self::$ins;
    }
 
 
    // 把购物车的单例对象放到session里
    public static function getCart() {
        if(!isset($_SESSION['cart']) || !($_SESSION['cart'] instanceof self)) {
            $_SESSION['cart'] = self::getIns();
        }
 
        return $_SESSION['cart'];
    }
 
 
    /*
    添加商品

    */
    public function addItem($shipment_id,$bat_no,$prd_no,$prd_name,$stock) {
         
        if($this->hasItem($bat_no,$prd_no)) { // 如果该商品已经存在,则直接加其数量
            //$this->incNum($bat_no,$prd_no,$num);
            return;
        }
         
        $item = array();
        $item['shipment_id'] = $shipment_id;
        $item['bat_no']=$bat_no;
        $item['prd_no']=$prd_no;
        $item['prd_name']=$prd_name;
        $item['stock'] = $stock;
         
        $this->items[$shipmenmt_id.$bat_no.$prd_no] = $item;
         
        return $this->items[$shipmenmt_id.$bat_no.$prd_no];
         
         
    }
 
    /*
        判断某商品是否存在
    */
    public function hasItem($shipment_id,$bat_no,$prd_no) {
        return array_key_exists($shipment_id.$bat_no.$prd_no,$this->items);
    }
       
 
    /*
        删除商品
    */
    public function delItem($bat_no,$prd_no) {
        unset($this->items[$shipment_id.$bat_no.$prd_no]);
    }
 
 
    /*
        查询购物车中商品的种类
    */
    public function getCnt() {
        return count($this->items);
    }
 
 
    /*
        查询购物车中商品的个数
    */
    public function getNum() {
        if($this->getCnt() == 0) {
            return 0;
        }
         
        $sum = 0;
 
        foreach($this->items as $item) {
            $sum += $item['num'];
        }
 
        return $sum;
    }
 
/*
    返回购物车中的所有商品
    */
 
    public function all() {
        return $this->items;
    }
 
    /*
        清空购物车
    */
    public function clear() {
        $this->items = array();
    }
}