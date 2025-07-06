<x-app-layout>
    @section('cabecalho')
        Relatório {{ ucfirst($tipoPeriodo) }} - {{ $periodoTexto }}
    @endsection

    <div class="container-fluid">
        <!-- Cabeçalho do Relatório -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-1">
                                    <i class="fas fa-chart-line me-2"></i>
                                    Relatório {{ ucfirst($tipoPeriodo) }}
                                </h3>
                                <p class="mb-0">Período: {{ $dataInicio->format('d/m/Y') }} a {{ $dataFim->format('d/m/Y') }}</p>
                            </div>
                            <div class="text-end">
                                <a href="{{ route('relatorio.periodo.index') }}" class="btn btn-light btn-sm">
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
                        <h5 class="card-title">{{ number_format($dados['estatisticasGerais']['total_presencas']) }}</h5>
                        <p class="card-text">Total de Presenças</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card border-danger">
                    <div class="card-body text-center">
                        <i class="fas fa-user-times fa-2x text-danger mb-2"></i>
                        <h5 class="card-title">{{ number_format($dados['estatisticasGerais']['total_faltas']) }}</h5>
                        <p class="card-text">Total de Faltas</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card border-warning">
                    <div class="card-body text-center">
                        <i class="fas fa-book fa-2x text-warning mb-2"></i>
                        <h5 class="card-title">{{ number_format($dados['estatisticasGerais']['total_livros']) }}</h5>
                        <p class="card-text">Total de Livros</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card border-info">
                    <div class="card-body text-center">
                        <i class="fas fa-book-open fa-2x text-info mb-2"></i>
                        <h5 class="card-title">{{ number_format($dados['estatisticasGerais']['total_biblias']) }}</h5>
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
                        <h5 class="card-title">{{ number_format($dados['estatisticasGerais']['total_visitantes']) }}</h5>
                        <p class="card-text">Total de Visitantes</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card border-secondary">
                    <div class="card-body text-center">
                        <i class="fas fa-book fa-2x text-secondary mb-2"></i>
                        <h5 class="card-title">{{ number_format($dados['estatisticasGerais']['total_biblias_visitantes']) }}</h5>
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
                                    @foreach($dados['dadosPorTurma'] as $turma)
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
        @if($dados['topAlunos']->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-trophy me-2"></i>
                            Top 10 Alunos - Mais Presenças
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
                                    @foreach($dados['topAlunos'] as $index => $aluno)
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
                                                <strong>{{ $aluno->total_presencas }}</strong>
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    @php
                                                        $maxPresencas = $dados['topAlunos']->first()->total_presencas;
                                                        $percentual = $maxPresencas > 0 ? ($aluno->total_presencas / $maxPresencas) * 100 : 0;
                                                    @endphp
                                                    <div class="progress-bar bg-success" style="width: {{ $percentual }}%">
                                                        {{ number_format($percentual, 1) }}%
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

        <!-- Evolução Mensal (apenas para relatórios semestrais e anuais) -->
        @if(count($dados['evolucaoMensal']) > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-line me-2"></i>
                            Evolução Mensal de Presenças
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-info">
                                    <tr>
                                        <th>Mês</th>
                                        <th class="text-center">Presenças</th>
                                        <th class="text-center">Gráfico</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $maxPresencas = $dados['evolucaoMensal']->max('presencas');
                                    @endphp
                                    @foreach($dados['evolucaoMensal'] as $mes)
                                        <tr>
                                            <td><strong>{{ $mes['mes'] }}</strong></td>
                                            <td class="text-center">{{ number_format($mes['presencas']) }}</td>
                                            <td>
                                                <div class="progress" style="height: 25px;">
                                                    @php
                                                        $percentual = $maxPresencas > 0 ? ($mes['presencas'] / $maxPresencas) * 100 : 0;
                                                    @endphp
                                                    <div class="progress-bar bg-primary" style="width: {{ $percentual }}%">
                                                        {{ number_format($percentual, 1) }}%
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

        <!-- Resumo Final -->
        <div class="row">
            <div class="col-12">
                <div class="card border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-clipboard-check me-2"></i>
                            Resumo do Período
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Principais Destaques:</h6>
                                <ul>
                                    <li><strong>Total de presenças:</strong> {{ number_format($dados['estatisticasGerais']['total_presencas']) }}</li>
                                    <li><strong>Total de visitantes:</strong> {{ number_format($dados['estatisticasGerais']['total_visitantes']) }}</li>
                                    <li><strong>Total de bíblias:</strong> {{ number_format($dados['estatisticasGerais']['total_biblias']) }}</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Melhor Desempenho:</h6>
                                @if($dados['topAlunos']->count() > 0)
                                    <p><strong>Aluno com mais presenças:</strong> {{ $dados['topAlunos']->first()->name }} ({{ $dados['topAlunos']->first()->total_presencas }} presenças)</p>
                                @endif
                                @php
                                    $melhorTurma = collect($dados['dadosPorTurma'])->sortByDesc('percentual_presenca')->first();
                                @endphp
                                @if($melhorTurma)
                                    <p><strong>Turma com melhor percentual:</strong> {{ $melhorTurma['turma']->nome_turma }} ({{ $melhorTurma['percentual_presenca'] }}%)</p>
                                @endif
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
            margin-bottom: 1rem;
        }

        .card-header {
            border-radius: 12px 12px 0 0 !important;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            font-weight: 600;
            vertical-align: middle;
        }

        .progress {
            border-radius: 10px;
            background-color: #f8f9fa;
        }

        .progress-bar {
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .badge {
            font-size: 0.8rem;
            padding: 0.5rem 0.8rem;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 1rem;
            }

            .table-responsive {
                font-size: 0.8rem;
            }

            .progress {
                height: 18px !important;
            }

            .progress-bar {
                font-size: 0.7rem;
            }

            .badge {
                font-size: 0.7rem;
                padding: 0.4rem 0.6rem;
            }

            /* Ajustes para tabela com mais colunas */
            .table th, .table td {
                padding: 0.3rem 0.2rem;
                font-size: 0.7rem;
                white-space: nowrap;
            }

            /* Abreviações para mobile */
            .mobile-hide {
                display: none !important;
            }

            /* Scroll horizontal melhorado */
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
        }

        /* Melhorar scroll horizontal */
        .table-responsive::-webkit-scrollbar {
            height: 6px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Indicador de scroll horizontal */
        .scroll-indicator {
            text-align: center;
            color: #6c757d;
            font-size: 0.8rem;
            padding: 0.5rem;
            background-color: #f8f9fa;
            border-radius: 6px;
            border: 1px solid #dee2e6;
        }

        .scroll-indicator i {
            margin-right: 0.5rem;
            color: #007bff;
        }
    </style>
</x-app-layout>
