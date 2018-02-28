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


function ch(href,msg) {
if(confirm('确定要删除么?')) {
location.href = url+'?'+msg
} else {
return false;
}
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

function printdiv(printpage) 
    { 
    var newstr = printpage.innerHTML; 
    var oldstr = document.body.innerHTML; 
    document.body.innerHTML =newstr; 
    window.print(); 
    document.body.innerHTML=oldstr; 
    return false; 
    } 
window.onload=function()
{
var bt=document.getElementById("bt");
var div_print=document.getElementById("div_print");
bt.onclick=function()
{
printdiv(div_print);
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

