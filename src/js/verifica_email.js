$(document).ready(function() {
    $('#email').on('input', function() {
        var email = $(this).val();
        var nome = $('input[name="nome"]').val().toLowerCase();
        var cognome = $('input[name="cognome"]').val().toLowerCase();
        console.log(nome);
        console.log(cognome);
        
        var regex = new RegExp('^' + nome + '\\.' + cognome + '@(studenti\\.ittsrimini\\.edu\\.it|ittsrimini\\.edu\\.it)$');

        if (!regex.test(email)) {
            $(this).css('border-color', 'red');
            $('#invio').prop('disabled', true);
        } else {
            $(this).css('border-color', '');
            $('#invio').prop('disabled', false);
        }
    });
});
