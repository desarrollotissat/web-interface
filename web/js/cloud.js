//function validarForm(){
//
//    alert(document.getElementById('password').value);
//    var pwd = document.getElementById('password');
//        pwd = pwd.value ;
//    var date = new Date();
//    date.setTime(date.getTime()+(1*24*60*60*1000));
////    document.cookie = "pwd="+pwd+"; expires="+date.toGMTString()+"; path=/";
////        document.cookie = "pwd="+pwd+"; expires="+date.toGMTString()+"";
//
//
//    return true;
//}

$( document ).on( "pageinit", function( event ) {

      $('#login').on('submit', function(){
          var pwd = $('#password-a').val();
//          alert(pwd);
          var date = new Date();
          date.setTime(date.getTime()+(1*24*60*60*1000));
          document.cookie = "pwd="+pwd+"; expires="+date.toGMTString()+"; path=/";
      });

//    $( document ).on( "pageinit", function( event ) {

        $(".ui-collapsible-heading-toggle").click(function (event) {
            alert("aa");
//            {#return false;#}
        });


});