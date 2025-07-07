<div>
    @section('cabecalho')
        Alunos por turma
    @endsection

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fas fa-users me-2"></i>
                            Relatório de Alunos por Turma
                        </h4>
                        <p class="card-text">Selecione a igreja, turma e data para gerar o relatório</p>
                    </div>
                    <div class="card-body">
                        <form wire:submit.prevent='gerarRelatorio'>
                            <div class="row justify-content-center align-items-end g-3">
                                <div class="col-lg-4 col-md-4 col-12 mb-3">
                                    <label for="igreja_id" class="form-label">
                                        <i class="fas fa-church me-1"></i>
                                        Igreja
                                    </label>
                                    <select class="form-select" id="igreja_id" wire:model='igreja_id' wire:change='recuperaTurmas'>
                                        <option value="">Selecione a igreja</option>
                                        @foreach ($igrejas as $igreja)
                                            <option value="{{ $igreja->id }}">{{ $igreja->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('igreja_id')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-4 col-md-4 col-12 mb-3">
                                    <label for="turma_id" class="form-label">
                                        <i class="fas fa-graduation-cap me-1"></i>
                                        Turma
                                    </label>
                                    <select class="form-select" id='turma_id' wire:model='turma_id'>
                                        <option value="">Selecione a turma</option>
                                        @foreach ($turmas as $turma)
                                            <option value="{{ $turma->id }}">{{ $turma->nome_turma }}</option>
                                        @endforeach
                                    </select>
                                    @error('turma_id')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-4 col-md-4 col-12 mb-3">
                                    <label for="data" class="form-label">
                                        <i class="fas fa-calendar me-1"></i>
                                        Data
                                    </label>
                                    <input type="date" class="form-control" id="data" wire:model="data">
                                    @error('data')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i>
                                        Gerar Relatório
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @if($mostrarRelatorio && !empty($dadosRelatorio))
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-1">
                                            <i class="fas fa-chart-bar me-2"></i>
                                            Relatório de Alunos por Turma
                                        </h5>
                                        <p class="mb-0">Turma: {{ $dadosRelatorio['turma']->nome_turma }} | Data: {{ $dadosRelatorio['dataFormatada'] }}</p>
                                    </div>
                                    <div>
                                        <button wire:click="gerarPdf" class="btn btn-light btn-sm">
                                            <i class="fas fa-file-pdf me-1"></i>
                                            Gerar PDF
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Estatísticas -->
                                <div class="row mb-4">
                                    <div class="col-lg-3 col-md-6 col-12 mb-3">
                                        <div class="card border-info">
                                            <div class="card-body text-center">
                                                <i class="fas fa-users fa-2x text-info mb-2"></i>
                                                <h5 class="card-title">{{ $dadosRelatorio['totalAlunos'] }}</h5>
                                                <p class="card-text">Total de Alunos</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-6 col-12 mb-3">
                                        <div class="card border-success">
                                            <div class="card-body text-center">
                                                <i class="fas fa-user-check fa-2x text-success mb-2"></i>
                                                <h5 class="card-title">{{ $dadosRelatorio['totalPresentes'] }}</h5>
                                                <p class="card-text">Alunos Presentes</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-6 col-12 mb-3">
                                        <div class="card border-warning">
                                            <div class="card-body text-center">
                                                <i class="fas fa-user-plus fa-2x text-warning mb-2"></i>
                                                <h5 class="card-title">{{ $dadosRelatorio['totalVisitantes'] }}</h5>
                                                <p class="card-text">Visitantes</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-6 col-12 mb-3">
                                        <div class="card border-primary">
                                            <div class="card-body text-center">
                                                <i class="fas fa-book fa-2x text-primary mb-2"></i>
                                                <h5 class="card-title">{{ $dadosRelatorio['totalBiblias'] }}</h5>
                                                <p class="card-text">Bíblias (Alunos)</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tabela de Alunos -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">
                                                    <i class="fas fa-table me-2"></i>
                                                    Lista de Alunos
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-hover">
                                                        <thead class="table-dark">
                                                            <tr>
                                                                <th>Nome</th>
                                                                <th class="text-center">Presença</th>
                                                                <th class="text-center">Bíblia</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($dadosRelatorio['alunos'] as $aluno)
                                                                @php
                                                                    $presenca = \App\Helper\Helpers::verificaPresenca($aluno->user_id, $dadosRelatorio['turma']->id, $data);
                                                                    $material = \App\Helper\Helpers::verificamaterial($aluno->user_id, $dadosRelatorio['turma']->id, $data);
                                                                @endphp
                                                                <tr>
                                                                    <td>{{ $aluno->name }}</td>
                                                                    <td class="text-center">
                                                                        @if($presenca == 'Presente')
                                                                            <span class="badge bg-success">Presente</span>
                                                                        @else
                                                                            <span class="badge bg-danger">Ausente</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @if($material == 'checked')
                                                                            <span class="badge bg-info">Sim</span>
                                                                        @else
                                                                            <span class="badge bg-secondary">Não</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Resumo -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="card border-success">
                                            <div class="card-header">
                                                <h6 class="mb-0">
                                                    <i class="fas fa-chart-pie me-2"></i>
                                                    Resumo Geral
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6>Pontos Positivos:</h6>
                                                        <ul class="list-unstyled">
                                                            <li><i class="fas fa-check text-success me-2"></i>Total de presentes: {{ $dadosRelatorio['totalGeralPresentes'] }}</li>
                                                            <li><i class="fas fa-check text-success me-2"></i>Total de bíblias: {{ $dadosRelatorio['totalGeralBiblias'] }}</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6>Estatísticas:</h6>
                                                        <ul class="list-unstyled">
                                                            <li><i class="fas fa-info-circle text-info me-2"></i>Alunos matriculados: {{ $dadosRelatorio['totalAlunos'] }}</li>
                                                            <li><i class="fas fa-info-circle text-info me-2"></i>Visitantes: {{ $dadosRelatorio['totalVisitantes'] }}</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .card {
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .card-header {
            border-radius: 12px 12px 0 0 !important;
        }

        .table-responsive {
            border-radius: 8px;
        }

        .badge {
            font-size: 0.8rem;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 1rem;
            }

            .table-responsive {
                font-size: 0.9rem;
            }
        }
    </style>
</div>
