@extends('layouts.app')

@section('title', 'Nova Despesa')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-body-tertiary">
                <h5 class="mb-0">Registrar Nova Despesa</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('despesas.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="loja" class="form-label">Loja / Descrição</label>
                        <input type="text" 
                               class="form-control @error('loja') is-invalid @enderror" 
                               id="loja" 
                               name="loja" 
                               value="{{ old('loja') }}" 
                               placeholder="Ex: Supermercado, Almoço, Steam..." 
                               required>
                        @error('loja')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="valor" class="form-label">Valor</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="number" 
                                       step="0.01" 
                                       class="form-control @error('valor') is-invalid @enderror" 
                                       id="valor" 
                                       name="valor" 
                                       value="{{ old('valor') }}" 
                                       placeholder="0,00" 
                                       required>
                                @error('valor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="data_compra" class="form-label">Data da Compra</label>
                            <input type="date" 
                                   class="form-control @error('data_compra') is-invalid @enderror" 
                                   id="data_compra" 
                                   name="data_compra" 
                                   value="{{ old('data_compra', date('Y-m-d')) }}" 
                                   required>
                            @error('data_compra')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="cartao" class="form-label">Cartão / Conta</label>
                        <input type="text" 
                               class="form-control @error('cartao') is-invalid @enderror" 
                               id="cartao" 
                               name="cartao" 
                               value="{{ old('cartao') }}" 
                               placeholder="Ex: Nubank, Dinheiro, VR..." 
                               required>
                        @error('cartao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('despesas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Salvar Despesa
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection