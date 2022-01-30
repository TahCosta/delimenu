function carregaItem(item, select) {
    let costitem, dataajax;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    const formData = {
        id: item,
    };

    $.ajax({
        type: "GET",
        url: "{!! route('recipes.show') !!}",
        data: formData,
        dataType: 'json',
        success: function(data) {
            dataajax = data;
            costitem = data.unity_cost;
            $(select).children('[name=inputMeasure]').html(data.measure);
            if ($(select).children('td').children('[name=ammount]').val() > 0) {
                $(select).children('[name=inputCost]').html("R$ " + (parseFloat($(select).children('td').children('[name=ammount]').val()) * parseFloat(data.unity_cost)).toFixed(4));
                if ($('#yield').length > 0) { // página receitas
                    getRecipeCosts();
                }
            } else {
                $(select).children('td').children('[name=ammount]').val('');
                $(select).children('[name=inputCost]').html('R$ 0,0000');
            }
            $(select).children('[name=inputCost]').attr('data-cost', data.unity_cost);
        },
        error: function(data) {
            console.log(data);
        }
    });

}

function addItem() {

    let tr = $('#tbList tbody tr:first').clone();
    $('#tbList tbody').append(tr);
    $('#tbList tbody tr:last').removeClass('d-none');
    onEvents();
}

function removeItem(item) {
    $(item).tooltip('dispose')
    $(item).parent().parent().remove();
}

function getRecipeCosts() {
    let totalCost = 0
    $('#tbList tr td[name=inputCost]').each(function(key, value) {
        if ($(value).html().length > 0) {
            totalCost += parseFloat($(value).html().substring(3));
        }
    });
    $('#pack_cost').val('R$ ' + totalCost.toFixed(4));
    if ($('#measure option:selected').val() !== 0 && $("#yield").val() !== '') { //medida e unidade preenchidos
        $('#unity_cost').val('R$ ' + (parseFloat(totalCost / $("#yield").val()).toFixed(4)));
    } else {
        $('#unity_cost').val('');
    }
}

function getProfitValue() {
    let totalCost = 0
    $('#tbList tr td[name=inputCost]').each(function(key, value) {
        if ($(value).html().length > 0) {
            totalCost += parseFloat($(value).html().substring(3));
        }
    });
    $('#cost').val('R$ ' + totalCost.toFixed(4));
    //Profit
    if ($('#sell').val() !== '') {

        let profit = $('#sell').val() - totalCost * 2;
        const profitPerc = profit / $('#sell').val();
        if (!isNaN(profitPerc)) {
            $('#profitperc').val(parseFloat(profitPerc * 100).toFixed(2) + '%')
        }

    }

    $('#profit').val('R$ ' + (totalCost * 3).toFixed(2))

}

function onEvents() {
    $('#tbList select').on('change', function() {
        const val = $(this).val(); // id do item
        carregaItem(val, $(this).parent().parent());
    })
    $("#tbList input").on('keyup', function() {
        if ($(this).parent().parent().children('td').children('select').val() !== null) {
            let inputCost = $(this).parent().parent().children('[name=inputCost]');
            inputCost.html('R$ ' + (parseFloat(inputCost.attr('data-cost')) * $(this).val()).toFixed(4));
            if ($('#yield').length > 0) { // página receitas
                getRecipeCosts();
            }
            if ($('#profit').length > 0) { // página de produtos
                getProfitValue();
            }

        }

    });
    if ($('#packsize').length > 0) {
        $('#packsize').on('keydown', 'input[text]', function(e) {
            var input = $(this);
            var oldVal = input.val();
            var regex = new RegExp('^\d*(\.\d{0,2})?$', 'g');

            setTimeout(function() {
                var newVal = input.val();
                if (!regex.test(newVal)) {
                    input.val(oldVal);
                }
            }, 1);
        });
    }

    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })

}

function deleteInput(message, form) {
    Swal.fire({
        title: message,
        showCancelButton: true,
        confirmButtonText: 'Excluir',
        cancelButtonText: `Cancelar`,
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
            Swal.fire('Fornecedor Excluido!', '', 'success')

        }
    })
}



$(document).ready(function($) {
    onEvents();
    $('#yield').on('keyup', getRecipeCosts)
    $('#sell').on('keyup', getProfitValue)
    if ($('#profit').length > 0) { // página de produtos
        getProfitValue();
    }
    if ($('#cnpj').length > 0) {
        $("#cnpj").mask("99.999.999/9999-99");
    }
    if ($('#phone').length > 0) {
        $('#phone').mask("(99) 9999-99999");
    }
    if ($('.table.datatable').length > 0) {
        $('.table').DataTable({
            responsive: true,
            columnDefs: [
                { responsivePriority: 1, targets: 0 },
                { responsivePriority: 2, targets: -1 }
            ],
            language: {
                url: '../js/datatables.json'
            }
        });
    }
});