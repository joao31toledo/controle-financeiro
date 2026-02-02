@extends('layouts.app')

@section('title', 'Minhas Despesas')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">💸 Controle de Despesas</h1>
    <span class="badge bg-primary fs-6 px-3 py-2 rounded-pill">
        Total: R$ {{ number_format($despesas->sum('valor'), 2, ',', '.') }}
    </span>
</div>



<div class="card shadow-sm border-0">
    <div class="card-body">
        <table id="tabelaDespesas" class="table table-hover w-100">
            <thead class="table-light">
                <tr>
                    <th>Data</th>
                    <th>Loja / Descrição</th>
                    <th>Cartão</th>
                    <th>Valor</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($despesas as $despesa)
                    <tr>
                        <td data-order="{{ $despesa->data_compra->format('YmdHis') }}">
                            {{ $despesa->data_compra->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') }}
                        </td>
                        <td class="fw-bold text-secondary">
                            {{ $despesa->loja ?? 'Não identificado' }}
                        </td>
                        <td>
                            {{ $despesa->cartao ?? '-' }}
                        </td>
                        <td>
                            <span class="badge bg-success-subtle text-success-emphasis rounded-pill">
                                R$ {{ number_format($despesa->valor, 2, ',', '.') }}
                            </span>
                        </td>
                        <td>
                            <span class="badge border border-secondary text-secondary rounded-pill">
                                {{ ucfirst($despesa->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#tabelaDespesas').DataTable({
                responsive: true,
                "order": [[ 0, "desc" ]], // Ordena pela primeira coluna (Data) decrescente
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
        });
    </script>
@endpush