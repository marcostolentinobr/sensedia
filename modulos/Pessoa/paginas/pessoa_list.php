<div class="modal fade" id="modal_msg" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <!-- title -->
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- body -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>


<!-- table -->
<table id='<?= $this->modulo ?>Datatable'>
    <thead>
        <?= $this->datatableTh ?>
    </thead>
    <tfoot>
        <?= $this->datatableTh ?>
    </tfoot>
</table>
<!-- fim table -->

<script>
    var datatable = null;
    $(document).ready(function() {

        //datatable
        datatable = $('#<?= $this->modulo ?>Datatable').DataTable({
            order: [
                [<?= $this->datatableSortDefalt ?>, 'desc']
            ],
            columnDefs: [{
                orderable: false,
                targets: [<?= implode(',', $this->datatableNoSort) ?>]
            }],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
            },
            searchDelay: 350,
            processing: true,
            serverSide: true,
            serverMethod: 'post',
            ajax: {
                url: 'api/<?= $this->modulo ?>/datatable'
            }
        });
        //fim datatable

    });

    //excluir
    function excluir(chave) {
        $.ajax({
            type: 'DELETE',
            url: 'api/<?= $this->modulo ?>/excluir/' + chave,
            success: function(data) {
                var data = $.parseJSON(data);

                //modal
                var modal = new bootstrap.Modal(document.getElementById('modal_msg'));
                $('#modal_msg .modal-title').html(data.title);
                $('#modal_msg .modal-body').html(
                    data.msg + '<br>' +
                    '<small style="font-size: 10px">' + data.detail + '</small>'
                );

                //reload datatable
                if (data.status == 1) {
                    datatable.ajax.reload();
                }

                //modal
                modal.show();
            }
        });
    }
    //fim excluir

    //editar
    function editar(chave) {
        $.ajax({
            type: 'GET',
            url: 'api/<?= $this->modulo ?>/editar/' + chave,
            success: function(data) {
                var data = $.parseJSON(data);
                if (data.status == 1) {

                    //dados
                    $.each(data.detail, function(id, valor) {
                        $('#' + id).val(valor);
                    });

                    //modal
                    $('#modal_acao .modal-title').html(data.title);
                    $('#form_<?= $this->modulo ?>').attr('action', '<?= $this->modulo ?>/update');
                    $('#form_<?= $this->modulo ?> .btn-success').html('Alterar');
                    var modal = new bootstrap.Modal(document.getElementById('modal_acao'));
                    modal.show();

                } else {

                    //modal
                    var modal = new bootstrap.Modal(document.getElementById('modal_msg'));
                    $('#modal_msg .modal-title').html(data.title);
                    $('#modal_msg .modal-body').html(
                        data.msg + '<br>' +
                        '<small style="font-size: 10px">' + data.detail + '</small>'
                    );
                    modal.show();
                }

            }
        });
    }
    //fim editar

    //btn reset incluir
    modal_acao.addEventListener('hide.bs.modal', function() {
        $('#modal_acao .modal-title').html('Incluir <?= $this->descricao_singular ?>');
        var form = $('#form_<?= $this->modulo ?>');
        form.attr('action', '<?= $this->modulo ?>/insert');
        form.trigger('reset');
        $('#form_<?= $this->modulo ?> .btn-success').html('Incluir');
    })
</script>