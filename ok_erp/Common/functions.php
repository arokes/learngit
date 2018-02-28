<?php

function get_client_time(){
		return Date("Y-m-d H:i:s");
	}
function get_month_first_day(){
	return  Date("Y-m")."-01";
	}
function addItem($shipment_id,$bat_no,$prd_no,$prd_name,$stock) {
         
        if(hasItem($shipment_id,$bat_no,$prd_no)) { 
        // 如果该商品已经存在则返回
            return;
        }   
        $item = array();
        $item['shipment_id'] = $shipment_id;
        $item['bat_no']=$bat_no;
        $item['prd_no']=$prd_no;
        $item['prd_name']=$prd_name;
        $item['stock'] = $stock;
         
        //$items[$shipment_id.$bat_no.$prd_no] = $item;
         
        return $_SESSION['cart'][$shipment_id.$bat_no.$prd_no]=$item;
         
         
    }
 
    /*
        判断某商品是否存在
    */
function hasItem($shipment_id,$bat_no,$prd_no) {
        return array_key_exists($shipment_id.$bat_no.$prd_no,$_SESSION['cart']);
    }
       
 
    /*
        删除商品
    */
function delItem($key) {
        unset($_SESSION['cart'][$key]);
    }
 
 
    /*
        查询购物车中商品的种类
    */
function getCnt() {
        return count($_SESSION['cart']);
    }
 
 
/*
    返回购物车中的所有商品
    */
 
function all() {
        return $_SESSION['cart'];
    }
 
    /*
        清空购物车
    */
function clear() {
        unset($_SESSION['cart']);
    }