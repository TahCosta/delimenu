function pooling() {
    fetch('./pooling')
        .then(resp => resp.json())
        .then(response => {
            console.log(response)
                /* if (response.code == '200') { // novo evento recebido
                    for (i in response.data) {
                        getOrder(response.data[i])
                    }
                } */
                //setTimeout(pooling, 30000);
        })
}

function dataMerchant() {
    fetch('./merchantdata')
        .then(resp => resp.json())
        .then(data => {
            console.log(data)
            const store = data.data[0]
            let validations = '';

            store.validations.forEach(item => {
                validations += item.message.title + ' <br>';
            });
            $(`#${data.id}`).popover('dispose')
            $(`#${data.id}`).popover({
                trigger: 'hover',
                title: store.message.title,
                content: validations,
                html: true
            })
            if (store.available) {
                $(`#${data.id}`).removeClass('btn-secondary')
                $(`#${data.id}`).removeClass('btn-danger')
                $(`#${data.id}`).addClass('btn-success')
            } else {
                $(`#${data.id}`).removeClass('btn-secondary')
                $(`#${data.id}`).addClass('btn-danger')
                $(`#${data.id}`).removeClass('btn-success')
            }
            //   setTimeout(dataMerchant, 60000);
        })
}

function getOrder(order) {
    fetch(`./orderdata?orderid=${order.orderId}&status=${order.fullCode}`)
        .then(resp => resp.json())
        .then(orderData => {
            console.log(orderData);
        })
}

function montaCard(orderNum, itens) {
    let body = '';
    itens.forEach(item => {
        
    });
    let card = `<div class="card card-success collapsed-card" id="${orderNum}">
 <div class="card-header">
     <h3 class="card-title"><i class="fas fa-lg fa-motorcycle mr-2"></i>Pedido Ifood #${orderNum}</h3>
     <div class="card-tools">
         <button type="button" class="btn btn-tool" data-card-widget="collapse">
         <i class="fas fa-lg fa-plus"></i></button>
     </div>
 </div>
 <div class="card-body">
     Novo pedido
 </div>
</div>`;
    return card
}

pooling();
dataMerchant();
$('[data-toggle=popover]')