// Konfirmasi password sama
$('#signup #username, #signup #password, #signup #password_confirmation').on('keyup', function(){
    if($('#signup #password').val() != '' && $('#signup #password').val() == $('#signup #password_confirmation').val() && $('#signup #username').val() != ''){
        $('#message').html('');
        $('#buttonsubmitsignup').css('background-color','green').css('color','white').prop('disabled',false);
    }
    else if($('#signup #password').val() == '' && $('#signup #password_confirmation').val() == ''){
        $('#buttonsubmitsignup').css('background-color','grey').prop('disabled',true);
    }
    else{
        $('#message').html('password tidak sama').css('color','red');
        $('#buttonsubmitsignup').css('background-color','grey').prop('disabled',true);
    }
})

$('#closeFormLogin').on('click', function () {
    $('#buttonLogin').trigger("click");
})
$('#closeHasilPrediksi').on('click', function () {
    $('#buttonBuatPolaPrediksi').trigger("click");
    // alert('bisa');
})

//logout
function signout() {
    var confirmation = confirm("Yakin Keluar?");

    if (confirmation == true) {
        window.location.href = "/signout";
    }
}