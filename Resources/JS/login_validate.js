$(document).ready(function() {
  $('#login-form').submit(function(e){
    e.preventDefault();
    //do some verification
    var data = $("#login-form").serialize();
    $.ajax({
        url: '../BLL/UsuariosBLL.php',
        type: 'POST',
        dataType: 'json',
        data: data
    })
    .done(function(data) {
        //console.log(data);
        if (data.error) {
            $("#userAlert").removeClass("hidden");
            $("#userAlert").html(data.message);
        }else if(data.valid){
            $("#userAlert").addClass("hidden");
            location.href = '../Views/main_page.php';
        }else{
            $("#userAlert").removeClass("hidden");
            $("#userAlert").html("The user or password are not correct. Please try again.");
        }
    }).fail(function(error) {
        console.log(error);
    })
    .always(function() {
        console.log("complete");
    });
  });
});