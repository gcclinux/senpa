$(document).ready(function(){

    $("#method").mouseover(function(){
        this.type = "text";
    }).mouseout(function(){
        this.type = "password";
    })

    $("#method2").mouseover(function(){
        this.type = "text";
    }).mouseout(function(){
        this.type = "password";
    })    
});
