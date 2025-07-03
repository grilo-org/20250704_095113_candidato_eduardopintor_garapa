
window._ = require('lodash');
window.Popper = require('popper.js').default;

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

 try {
    window.$ = window.jQuery = require('jquery');

    require( 'bootstrap' );
    require( 'select2' );
    require( 'parsleyjs' );
    require( 'jquery-inputmask' );

    var dt = require( 'datatables.net-bs4' )( window, $ );

} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

 window.axios = require('axios');

 window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

 let token = document.head.querySelector('meta[name="csrf-token"]');

 if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });


$(document).ready( function () {
     if($('#table_list').length) {
        $('#table_list').DataTable({
            'order': [0, 'desc'],
            "language": {
                "sEmptyTable": "Nenhum registro encontrado",
                "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                "sInfoPostFix": "",
                "sInfoThousands": ".",
                "sLengthMenu": "_MENU_ resultados por página",
                "sLoadingRecords": "Carregando...",
                "sProcessing": "Processando...",
                "sZeroRecords": "Nenhum registro encontrado",
                "sSearch": "Pesquisar",
                "oPaginate": {
                    "sNext": "Próximo",
                    "sPrevious": "Anterior",
                    "sFirst": "Primeiro",
                    "sLast": "Último"
                },
                "oAria": {
                    "sSortAscending": ": Ordenar colunas de forma ascendente",
                    "sSortDescending": ": Ordenar colunas de forma descendente"
                }
            }
        });
    }



    if($('#editor').length) {
        $('#editor').parsley();
        Parsley.addMessages('pt-br', {
          defaultMessage: "Este valor parece ser inválido.",
          type: {
            email:        "Este campo deve ser um email válido.",
            url:          "Este campo deve ser um URL válida.",
            number:       "Este campo deve ser um número válido.",
            integer:      "Este campo deve ser um inteiro válido.",
            digits:       "Este campo deve conter apenas dígitos.",
            alphanum:     "Este campo deve ser alfa numérico."
        },
        notblank:       "Este campo não pode ficar vazio.",
        required:       "Este campo é obrigatório.",
        pattern:        "Este campo parece estar inválido.",
        min:            "Este campo deve ser maior ou igual a %s.",
        max:            "Este campo deve ser menor ou igual a %s.",
        range:          "Este campo deve estar entre %s e %s.",
        minlength:      "Este campo é pequeno demais. Ele deveria ter %s caracteres ou mais.",
        maxlength:      "Este campo é grande demais. Ele deveria ter %s caracteres ou menos.",
        length:         "O tamanho deste campo é inválido. Ele deveria ter entre %s e %s caracteres.",
        mincheck:       "Você deve escolher pelo menos %s opções.",
        maxcheck:       "Você deve escolher %s opções ou mais",
        check:          "Você deve escolher entre %s e %s opções.",
        equalto:        "Este valor deveria ser igual."
    });

        Parsley.setLocale('pt-br');
    }
    
    if($('select.select2').length) {
        $('select.select2').select2();
    }

    if($('#phone').length) {
        $("#phone").mask("(99) 99999-999?9")
        .focusout(function (event) {  
            var target, phone, element;  
            target = (event.currentTarget) ? event.currentTarget : event.srcElement;  
            phone = target.value.replace(/\D/g, '');
            element = $(target);  
            element.unmask();  
            if(phone.length > 10) {  
                element.mask("(99) 99999-999?9");
            } else {  
                element.mask("(99) 9999-9999?9");  
            }  
        });
    }

    if($('#zipcode').length) {
        $('#zipcode').mask('99999-999');
    }

    if($('select.select2-foods').length) {
        $('select.select2-foods').select2();
    }
    if($('.btn-destroy').length) {
        $('.table').on('click','.btn-destroy',function(e) {
            return confirm('Deseja realmente excluir este item?');
        });
    }

    // if($('select#state').length) {
        $('select#state').change( function(e){
            var state = $(this).val();
            var select = $('select#city');
            select.html('<option value="">Selecione o estado primeiro</option>');
            select.attr('disabled', 'disabled');

            if(state.length) {

                select.html('<option value="">Aguarde...</option>');
                $.getJSON({url: '/cidades/list/'+ state, success: function(data){
                    var options = '<option value="">Selecione a cidade</option>';
                    for (var i = 0; i < data.length; i++) {
                        options += '<option value="'+ data[i].name +'">'+ data[i].name +'</option>';
                    }
                    select.html(options);
                    select.removeAttr('disabled');
                    select.select2();
                    
                }});

                // var xhttp = new XMLHttpRequest();
                // xhttp.onreadystatechange = function() {
                //     if (this.readyState == 4 && this.status == 200) {
                // };
                // xhttp.open("GET", '/cidades/list/'+ state , true);
                // xhttp.send();
            }
        });
    // }

});
