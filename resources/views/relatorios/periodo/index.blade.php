<x-app-layout>
    @section('cabecalho')
        Relatórios por Período
    @endsection

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fas fa-chart-line me-2"></i>
                            Relatórios por Período
                        </h4>
                        <p class="card-text">Selecione o tipo de relatório e o período desejado</p>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('relatorio.periodo.gerar') }}" method="GET" id="formRelatorio">
                            <div class="row">
                                <!-- Tipo de Período -->
                                <div class="col-md-6 col-12 mb-3">
                                    <label for="tipo_periodo" class="form-label">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        Tipo de Relatório
                                    </label>
                                    <select class="form-select" id="tipo_periodo" name="tipo_periodo" required>
                                        <option value="">Selecione o tipo</option>
                                        <option value="mensal">Mensal</option>
                                        <option value="semestral">Semestral</option>
                                        <option value="anual">Anual</option>
                                    </select>
                                </div>

                                <!-- Ano -->
                                <div class="col-md-6 col-12 mb-3">
                                    <label for="ano" class="form-label">
                                        <i class="fas fa-calendar me-1"></i>
                                        Ano
                                    </label>
                                    <select class="form-select" id="ano" name="ano" required>
                                        <option value="">Selecione o ano</option>
                                        @for($i = date('Y') + 1; $i >= 2020; $i--)
                                            <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <!-- Mês (apenas para relatório mensal) -->
                                <div class="col-md-6 col-12 mb-3" id="div_mes" style="display: none;">
                                    <label for="mes" class="form-label">
                                        <i class="fas fa-calendar-day me-1"></i>
                                        Mês
                                    </label>
                                    <select class="form-select" id="mes" name="mes">
                                        <option value="">Selecione o mês</option>
                                        <option value="1">Janeiro</option>
                                        <option value="2">Fevereiro</option>
                                        <option value="3">Março</option>
                                        <option value="4">Abril</option>
                                        <option value="5">Maio</option>
                                        <option value="6">Junho</option>
                                        <option value="7">Julho</option>
                                        <option value="8">Agosto</option>
                                        <option value="9">Setembro</option>
                                        <option value="10">Outubro</option>
                                        <option value="11">Novembro</option>
                                        <option value="12">Dezembro</option>
                                    </select>
                                </div>

                                <!-- Semestre (apenas para relatório semestral) -->
                                <div class="col-md-6 col-12 mb-3" id="div_semestre" style="display: none;">
                                    <label for="semestre" class="form-label">
                                        <i class="fas fa-calendar-week me-1"></i>
                                        Semestre
                                    </label>
                                    <select class="form-select" id="semestre" name="semestre">
                                        <option value="">Selecione o semestre</option>
                                        <option value="1">1º Semestre (Jan-Jun)</option>
                                        <option value="2">2º Semestre (Jul-Dez)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Botões -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary btn-gerar-periodo">
                                            <i class="fas fa-search me-1"></i>
                                            Gerar Relatório
                                        </button>
                                        <button type="reset" class="btn btn-secondary">
                                            <i class="fas fa-undo me-1"></i>
                                            Limpar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Informações sobre os relatórios -->
                <div class="row mt-4">
                    <div class="col-md-4 col-12 mb-3">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <i class="fas fa-calendar-alt fa-2x text-primary mb-2"></i>
                                <h5 class="card-title">Relatório Mensal</h5>
                                <p class="card-text">Estatísticas detalhadas de um mês específico, incluindo presenças, faltas, materiais e visitantes por turma.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-12 mb-3">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <i class="fas fa-chart-bar fa-2x text-success mb-2"></i>
                                <h5 class="card-title">Relatório Semestral</h5>
                                <p class="card-text">Análise de 6 meses com evolução mensal, comparações e tendências de crescimento.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-12 mb-3">
                        <div class="card border-warning">
                            <div class="card-body text-center">
                                <i class="fas fa-chart-line fa-2x text-warning mb-2"></i>
                                <h5 class="card-title">Relatório Anual</h5>
                                <p class="card-text">Visão completa do ano com todas as estatísticas, ranking de alunos e análise de crescimento.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .btn-gerar-periodo {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.2s ease;
        }

        .btn-gerar-periodo:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px 12px 0 0 !important;
        }

        .form-select, .form-control {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            transition: border-color 0.2s ease;
        }

        .form-select:focus, .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        @media (max-width: 768px) {
            .btn-gerar-periodo {
                padding: 0.6rem 1.2rem;
                font-size: 0.9rem;
            }

            .card-body {
                padding: 1rem;
            }

            .row {
                margin-left: -0.5rem;
                margin-right: -0.5rem;
            }

            .col-12, .col-md-4, .col-md-6 {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tipoPeriodo = document.getElementById('tipo_periodo');
            const divMes = document.getElementById('div_mes');
            const divSemestre = document.getElementById('div_semestre');
            const mes = document.getElementById('mes');
            const semestre = document.getElementById('semestre');

            tipoPeriodo.addEventListener('change', function() {
                // Ocultar todos os campos específicos
                divMes.style.display = 'none';
                divSemestre.style.display = 'none';
                mes.removeAttribute('required');
                semestre.removeAttribute('required');

                // Mostrar campo específico baseado na seleção
                switch(this.value) {
                    case 'mensal':
                        divMes.style.display = 'block';
                        mes.setAttribute('required', 'required');
                        break;
                    case 'semestral':
                        divSemestre.style.display = 'block';
                        semestre.setAttribute('required', 'required');
                        break;
                }
            });

            // Definir mês atual como padrão
            const mesAtual = new Date().getMonth() + 1;
            mes.value = mesAtual;

            // Definir semestre atual como padrão
            const semestreAtual = mesAtual <= 6 ? 1 : 2;
            semestre.value = semestreAtual;
        });
    </script>
</x-app-layout>
