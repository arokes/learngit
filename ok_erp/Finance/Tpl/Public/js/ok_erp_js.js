function changeFrameHeight(){
    var ifm= document.getElementById("myiframe"); 
    ifm.height=document.documentElement.clientHeight;
}
window.onresize=function(){  
     changeFrameHeight();  
} 

function manySend(href){ 
var form = document.form1; 
form.action = href;//传想要跳转的路径 
form.submit(); 
} 


var count = 1;  
function add() {  
  var tbl = document.all.ci;  
  var rows = tbl.rows.length;  
  var tr = tbl.insertRow(rows);  
     
  var amount = tr.insertCell(0);  
  amount.innerHTML = '<input  id="cst" type="text" name="amount_apart[]" />';  
  var bro = tr.insertCell(1);  
  bro.innerHTML = '<input id="cst" type="text" name="brokerage[]" />';  
  var os = tr.insertCell(2);  
  os.innerHTML = '<input id="os_no" type="text" name="so_no[]" />';  
  var so_pi = tr.insertCell(3);  
  so_pi.innerHTML = '<input id="cst" type="text" name="so_pi[]" />';  
  var dec = tr.insertCell(4);  
  dec.innerHTML = '<input type="checkbox" value="是" name="is_declare[]" checked="checked" >报关<input type="checkbox" value="否" name="is_declare[]">不报关';
  var contract = tr.insertCell(5);  
  contract.innerHTML = '<input id="cust" type="text" name="contract_cust[]" />'; 
  var lz_cust = tr.insertCell(6);  
  lz_cust.innerHTML = '<input id="cust" type="text" name="lz_cust[]" />'; 
  var connect = tr.insertCell(7);  
  connect.innerHTML = '<input id="cust" type="text" name="connect_cust[]" />'; 
  var debit = tr.insertCell(8);  
  debit.innerHTML = '<input type="text" name="debit[]" />'; 
  var rem = tr.insertCell(9);  
  rem.innerHTML = '<input type="text" name="rem[]" />'; 
  var del = tr.insertCell(10);  
  del.innerHTML = '<input type="button" onclick="del(this)" value="Delete">';  

  count++;  
}  
  
function del(btn) {  
  var tr = btn.parentElement.parentElement;  
  var tbl = tr.parentElement;  
  tbl.deleteRow(tr.rowIndex);  
}  

;!function(){
laydate({
   elem: '#demo'
})
}();
function onlyNumber(event){
    var keyCode = event.keyCode;
    if(keyCode<48 || keyCode>57){
        event.keyCode = 0;
    }
}


function menuFix() {
    var sfEls = document.getElementById("nav").getElementsByTagName("li");
    for (var i=0; i<sfEls.length; i++) {
        sfEls[i].onmouseover=function() {
        this.className+=(this.className.length>0? " ": "") + "sfhover";
        }
        sfEls[i].onMouseDown=function() {
        this.className+=(this.className.length>0? " ": "") + "sfhover";
        }
        sfEls[i].onMouseUp=function() {
        this.className+=(this.className.length>0? " ": "") + "sfhover";
        }
        sfEls[i].onmouseout=function() {
        this.className=this.className.replace(new RegExp("( ?|^)sfhover\\b"),

"");
        }
    }
}

