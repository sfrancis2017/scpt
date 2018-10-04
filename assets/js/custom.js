var url = window.location;
$('ul.nav a').filter(function(){
    return this.href == url;
}).parent().addClass('active').parent().parent().addClass('active');

function checkPasswordMatch() {
    var password = $("#passwordField").val();
    var confirmpassword = $("#confirmpasswordField").val();

    if (password !== confirmpassword)
        $("#divCheckPasswordMatch").html("Passwords do not match!");
    else
        $("#divCheckPasswordMatch").html("");
}

$(document).ready(function () {
   $("#passwordField, #confirmpasswordField").keyup(checkPasswordMatch);
});

function checkEmailMatch() {
    var email = $("#emailField").val();
    var confirmemail = $("#confirmemailField").val();

    if (email !== confirmemail)
        $("#divCheckEmailMatch").html("Email address does not match!");
    else
        $("#divCheckEmailMatch").html("");
}

$(document).ready(function () {
   $("#emailField, #confirmemailField").keyup(checkEmailMatch);
});