<x-app-layout>
    @section('cabecalho')
        Relatório Geral do Dia - {{ $dataExibicao }}
    @endsection

    <div class="container-fluid">
        <!-- Cabeçalho do Relatório -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-1">
                                    <i class="fas fa-calendar-day me-2"></i>
                                    Relatório Geral do Dia
                                </h3>
                                <p class="mb-0">Data: {{ $dataExibicao }}</p>
                            </div>
                            <div class="text-end">
                                <a href="{{ route('relatorio.geral-dia.index') }}" class="btn btn-light btn-sm">
                                    <i class="fas fa-arrow-left me-1"></i>
                                    Voltar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estatísticas Gerais -->
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="mb-3">
                    <i class="fas fa-chart-pie me-2"></i>
                    Estatísticas Gerais
                </h4>
            </div>

            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card border-success">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-2x text-success mb-2"></i>
                        <h5 class="card-title">{{ number_format($estatisticasGerais['total_presencas']) }}</h5>
                        <p class="card-text">Total de Presenças</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card border-danger">
                    <div class="card-body text-center">
                        <i class="fas fa-user-times fa-2x text-danger mb-2"></i>
                        <h5 class="card-title">{{ number_format($estatisticasGerais['total_faltas']) }}</h5>
                        <p class="card-text">Total de Faltas</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card border-warning">
                    <div class="card-body text-center">
                        <i class="fas fa-book fa-2x text-warning mb-2"></i>
                        <h5 class="card-title">{{ number_format($estatisticasGerais['total_livros']) }}</h5>
                        <p class="card-text">Total de Livros</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card border-info">
                    <div class="card-body text-center">
                        <i class="fas fa-book-open fa-2x text-info mb-2"></i>
                        <h5 class="card-title">{{ number_format($estatisticasGerais['total_biblias']) }}</h5>
                        <p class="card-text">Total de Bíblias</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estatísticas Adicionais -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card border-primary">
                    <div class="card-body text-center">
                        <i class="fas fa-user-plus fa-2x text-primary mb-2"></i>
                        <h5 class="card-title">{{ number_format($estatisticasGerais['total_visitantes']) }}</h5>
                        <p class="card-text">Total de Visitantes</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card border-secondary">
                    <div class="card-body text-center">
                        <i class="fas fa-book fa-2x text-secondary mb-2"></i>
                        <h5 class="card-title">{{ number_format($estatisticasGerais['total_biblias_visitantes']) }}</h5>
                        <p class="card-text">Bíblias (Visitantes)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dados por Turma -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-table me-2"></i>
                            Dados por Turma
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Indicador de scroll horizontal -->
                        <div class="scroll-indicator d-md-none mb-2">
                            <i class="fas fa-arrows-alt-h"></i> Deslize horizontalmente para ver mais colunas
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Turma</th>
                                        <th>Matriculados</th>
                                        <th>Presenças</th>
                                        <th>Faltas</th>
                                        <th>Livros</th>
                                        <th>Bíblias</th>
                                        <th>Bíblias (V)</th>
                                        <th>Visitantes</th>
                                        <th>% Presença</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dadosPorTurma as $turma)
                                        <tr>
                                            <td>
                                                <strong>{{ $turma['turma']->nome_turma }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    {{ $turma['alunos'] }} alunos, {{ $turma['professores'] }} professores
                                                </small>
                                            </td>
                                            <td class="text-center">{{ $turma['total_matriculados'] }}</td>
                                            <td class="text-center text-success">{{ $turma['presencas'] }}</td>
                                            <td class="text-center text-danger">{{ $turma['faltas'] }}</td>
                                            <td class="text-center text-warning">{{ $turma['livros'] }}</td>
                                            <td class="text-center text-info">{{ $turma['biblias'] }}</td>
                                            <td class="text-center">{{ $turma['biblias_visitantes'] }}</td>
                                            <td class="text-center">{{ $turma['visitantes'] }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-{{ $turma['percentual_presenca'] >= 80 ? 'success' : ($turma['percentual_presenca'] >= 60 ? 'warning' : 'danger') }}">
                                                    {{ $turma['percentual_presenca'] }}%
                                                </span>
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

        <!-- Top Alunos -->
        @if($topAlunos->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-trophy me-2"></i>
                            Top 10 Alunos - Mais Presenças no Dia
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-primary">
                                    <tr>
                                        <th width="10%">#</th>
                                        <th>Nome</th>
                                        <th class="text-center">Presenças</th>
                                        <th class="text-center">Progresso</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topAlunos as $index => $aluno)
                                        <tr>
                                            <td class="text-center">
                                                @if($index < 3)
                                                    <i class="fas fa-medal text-{{ $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'danger') }}"></i>
                                                @else
                                                    <strong>{{ $index + 1 }}</strong>
                                                @endif
                                            </td>
                                            <td>{{ $aluno->name }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-success">{{ $aluno->presencas }}</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-success"
                                                         style="width: {{ $aluno->presencas * 10 }}%">
                                                        {{ $aluno->presencas * 10 }}%
                                                    </div>
                                                </div>
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
        @endif

        <!-- Resumo do Dia -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-info">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-line me-2"></i>
                            Resumo do Dia
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Pontos Positivos:</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success me-2"></i>Total de presenças: {{ number_format($estatisticasGerais['total_presencas']) }}</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Total de bíblias: {{ number_format($estatisticasGerais['total_biblias']) }}</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Visitantes: {{ number_format($estatisticasGerais['total_visitantes']) }}</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Pontos de Atenção:</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Total de faltas: {{ number_format($estatisticasGerais['total_faltas']) }}</li>
                                    <li><i class="fas fa-info-circle text-info me-2"></i>Total de livros: {{ number_format($estatisticasGerais['total_livros']) }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
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

        .scroll-indicator {
            background: #f8f9fa;
            padding: 8px;
            border-radius: 6px;
            text-align: center;
            font-size: 0.9rem;
            color: #6c757d;
        }

        .progress {
            border-radius: 10px;
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

            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.8rem;
            }
        }
    </style>
</x-app-layout>
