<x-app-layout>
    @section('cabecalho')
        Relatório Geral do Dia
    @endsection

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fas fa-calendar-day me-2"></i>
                            Relatório Geral do Dia
                        </h4>
                        <p class="card-text">Selecione a data para gerar o relatório detalhado do dia</p>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('relatorio.geral-dia.gerar') }}" method="GET" id="formRelatorio">
                            <div class="row">
                                <!-- Data -->
                                <div class="col-md-6 col-12 mb-3">
                                    <label for="data" class="form-label">
                                        <i class="fas fa-calendar me-1"></i>
                                        Data
                                    </label>
                                    <input type="date" class="form-control" id="data" name="data"
                                           value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>

                            <!-- Botões -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary btn-gerar-dia">
                                            <i class="fas fa-search me-1"></i>
                                            Gerar Relatório
                                        </button>
                                        <button type="button" class="btn btn-danger btn-gerar-dia" id="btnPdf">
                                            <i class="fas fa-file-pdf me-1"></i>
                                            Gerar PDF
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

                <!-- Informações sobre o relatório -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card border-info">
                            <div class="card-body text-center">
                                <i class="fas fa-chart-bar fa-2x text-info mb-2"></i>
                                <h5 class="card-title">Relatório Geral do Dia</h5>
                                <p class="card-text">
                                    Este relatório apresenta estatísticas detalhadas de um dia específico,
                                    incluindo presenças, faltas, materiais, visitantes e dados por turma.
                                    Ideal para análise diária e acompanhamento pontual das atividades da EBD.
                                </p>
                                <div class="row mt-3">
                                    <div class="col-md-3 col-6 mb-2">
                                        <i class="fas fa-users text-success"></i>
                                        <small>Presenças</small>
                                    </div>
                                    <div class="col-md-3 col-6 mb-2">
                                        <i class="fas fa-user-times text-danger"></i>
                                        <small>Faltas</small>
                                    </div>
                                    <div class="col-md-3 col-6 mb-2">
                                        <i class="fas fa-book text-warning"></i>
                                        <small>Livros</small>
                                    </div>
                                    <div class="col-md-3 col-6 mb-2">
                                        <i class="fas fa-book-open text-info"></i>
                                        <small>Bíblias</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .btn-gerar-dia {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.2s ease;
        }

        .btn-gerar-dia:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .card-header {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            border-radius: 12px 12px 0 0 !important;
        }

        .form-control {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            transition: border-color 0.2s ease;
        }

        .form-control:focus {
            border-color: #17a2b8;
            box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
        }

        @media (max-width: 768px) {
            .btn-gerar-dia {
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

            .col-12, .col-md-6 {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnPdf = document.getElementById('btnPdf');

            // Reset do formulário
            document.querySelector('button[type="reset"]').addEventListener('click', function() {
                document.getElementById('data').value = '{{ date('Y-m-d') }}';
            });

            // Validação do formulário
            document.getElementById('formRelatorio').addEventListener('submit', function(e) {
                const data = document.getElementById('data').value;

                if (!data) {
                    e.preventDefault();
                    alert('Por favor, selecione uma data.');
                    return false;
                }

                // Verificar se a data não é futura
                const dataSelecionada = new Date(data);
                const hoje = new Date();
                hoje.setHours(23, 59, 59); // Fim do dia atual

                if (dataSelecionada > hoje) {
                    e.preventDefault();
                    alert('Não é possível gerar relatório para datas futuras.');
                    return false;
                }
            });

            // Funcionalidade do botão PDF
            btnPdf.addEventListener('click', function() {
                const data = document.getElementById('data').value;

                if (!data) {
                    alert('Por favor, selecione uma data.');
                    return;
                }

                // Verificar se a data não é futura
                const dataSelecionada = new Date(data);
                const hoje = new Date();
                hoje.setHours(23, 59, 59);

                if (dataSelecionada > hoje) {
                    alert('Não é possível gerar relatório para datas futuras.');
                    return;
                }

                // Gerar URL para PDF
                const pdfUrl = '{{ route("relatorio.geral-dia.pdf") }}?data=' + data;
                window.open(pdfUrl, '_blank');
            });
        });
    </script>
</x-app-layout>
