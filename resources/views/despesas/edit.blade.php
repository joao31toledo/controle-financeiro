@extends('layouts.app')

@section('title', 'Editar Despesa')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-body-tertiary d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Editar Despesa</h5>
                <span class="badge bg-secondary">ID: {{ $despesa->id }}</span>
            </div>
            <div class="card-body">
                <form action="{{ route('despesas.update', $despesa->id) }}" method="POST">
                    @csrf
                    @method('PUT') <div class="mb-3">
                        <label for="loja" class="form-label">Loja / Descrição</label>
                        <input type="text" class="form-control" id="loja" name="loja" 
                               value="{{ old('loja', $despesa->loja) }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="valor" class="form-label">Valor</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="number" step="0.01" class="form-control" id="valor" name="valor" 
                                       value="{{ old('valor', $despesa->valor) }}" required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="data_compra" class="form-label">Data da Compra</label>
                            <input type="date" class="form-control" id="data_compra" name="data_compra" 
                                   value="{{ old('data_compra', $despesa->data_compra->format('Y-m-d')) }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="cartao" class="form-label">Cartão / Conta</label>
                        <input type="text" class="form-control" id="cartao" name="cartao" 
                               value="{{ old('cartao', $despesa->cartao) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" name="status" id="status">
                            <option value="verificado" {{ old('status', $despesa->status) == 'verificado' ? 'selected' : '' }}>Verificado</option>
                            <option value="pendente" {{ old('status', $despesa->status) == 'pendente' ? 'selected' : '' }}>Pendente</option>
                            <option value="agendado" {{ old('status', $despesa->status) == 'agendado' ? 'selected' : '' }}>Agendado</option>
                        </select>
                    </div>

                    <hr>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('despesas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Atualizar
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection