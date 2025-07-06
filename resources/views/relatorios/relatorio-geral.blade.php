<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.1/font/bootstrap-icons.css">

    <style>
        img {
            width: 150px;
            margin-top: 2%;
        }
        .section-border {
            padding: 1em;
            margin: 0 1em;
        }
        .table th, .table td {
            padding: 0.5rem;
            font-size: 0.9rem;
        }

        /* Otimizações para tablets */
        @media (max-width: 1024px) and (min-width: 769px) {
            img {
                width: 120px;
                margin-top: 1.5%;
            }

            h2 {
                font-size: 1.4rem !important;
                line-height: 1.4;
            }

            .table th, .table td {
                padding: 0.4rem 0.3rem;
                font-size: 0.8rem;
            }

            .btn {
                padding: 0.4rem 0.6rem;
                font-size: 0.85rem;
            }

            .form-control {
                font-size: 0.85rem;
                padding: 0.4rem 0.6rem;
            }
        }

        /* Otimizações para mobile */
        @media (max-width: 768px) {
            img {
                width: 80px;
                margin-top: 1%;
            }

            .section-border {
                padding: 0.3em;
                margin: 0 0.3em;
            }

            h2 {
                font-size: 1rem !important;
                line-height: 1.2;
                margin-bottom: 0.5rem !important;
            }

            h6 {
                font-size: 0.7rem !important;
                margin-bottom: 0.3rem !important;
            }

            .table th, .table td {
                padding: 0.25rem 0.15rem;
                font-size: 0.65rem;
                vertical-align: middle;
                white-space: nowrap;
            }

            .table-responsive {
                font-size: 0.6rem;
                border: none;
            }

            .btn {
                padding: 0.3rem 0.4rem;
                font-size: 0.7rem;
                min-width: auto;
            }

            /* Melhorar botão no mobile */
            .btn-gerar {
                padding: 0.5rem 1rem !important;
                font-size: 0.8rem !important;
                font-weight: 600;
                border-radius: 6px;
                min-width: 80px;
                height: 38px;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.3rem;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                transition: all 0.2s ease;
            }

            .btn-gerar:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            }

            .btn-gerar:active {
                transform: translateY(0);
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }

            /* Melhorar formulário no mobile */
            .form-mobile {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
                width: 100%;
            }

            .form-row-mobile {
                display: flex;
                gap: 0.5rem;
                align-items: center;
                flex-wrap: wrap;
            }

            .input-date-mobile {
                flex: 1;
                min-width: 140px;
                max-width: 200px;
            }

            .form-control {
                font-size: 0.7rem;
                padding: 0.3rem 0.4rem;
                height: auto;
            }

            .container-fluid {
                padding-left: 0.3rem;
                padding-right: 0.3rem;
            }

            .row {
                margin-left: -0.3rem;
                margin-right: -0.3rem;
            }

            .col-12 {
                padding-left: 0.3rem;
                padding-right: 0.3rem;
            }

            /* Abreviações para mobile */
            .mobile-hide {
                display: none !important;
            }

            .mobile-show {
                display: table-cell !important;
            }

            /* Ajuste para tabela responsiva */
            .table-mobile {
                min-width: 100%;
                table-layout: fixed;
            }

            .table-mobile th:nth-child(1) { /* Turma */
                width: 25%;
                min-width: 60px;
            }
            .table-mobile th:nth-child(2) { /* Matric */
                width: 12%;
                min-width: 35px;
            }
            .table-mobile th:nth-child(3) { /* Pres */
                width: 10%;
                min-width: 30px;
            }
            .table-mobile th:nth-child(4) { /* Aus */
                width: 10%;
                min-width: 30px;
            }
            .table-mobile th:nth-child(5) { /* Prof Aus */
                width: 15%;
                min-width: 45px;
            }
            .table-mobile th:nth-child(6) { /* Visit */
                width: 10%;
                min-width: 30px;
            }
            .table-mobile th:nth-child(7) { /* Bíb(A) */
                width: 9%;
                min-width: 35px;
            }
            .table-mobile th:nth-child(8) { /* Bíb(V) */
                width: 9%;
                min-width: 35px;
            }

            /* Melhorar scroll horizontal */
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: thin;
            }

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
        }

        /* Otimizações para telas muito pequenas */
        @media (max-width: 480px) {
            img {
                width: 60px;
                margin-top: 0.5%;
            }

            h2 {
                font-size: 0.9rem !important;
                line-height: 1.1;
            }

            h6 {
                font-size: 0.6rem !important;
            }

            .table th, .table td {
                padding: 0.2rem 0.1rem;
                font-size: 0.6rem;
            }

            /* Botão ainda mais otimizado para telas pequenas */
            .btn-gerar {
                padding: 0.4rem 0.8rem !important;
                font-size: 0.75rem !important;
                min-width: 70px;
                height: 36px;
            }

            .form-mobile {
                gap: 0.4rem;
            }

            .form-row-mobile {
                gap: 0.4rem;
            }

            .input-date-mobile {
                min-width: 120px;
                max-width: 180px;
            }

            .btn {
                padding: 0.25rem 0.3rem;
                font-size: 0.65rem;
            }

            .form-control {
                font-size: 0.65rem;
                padding: 0.25rem 0.3rem;
            }

            .container-fluid {
                padding-left: 0.2rem;
                padding-right: 0.2rem;
            }

            .section-border {
                padding: 0.2em;
                margin: 0 0.2em;
            }

            /* Ajustar larguras para telas muito pequenas */
            .table-mobile th:nth-child(1) { /* Turma */
                width: 30%;
                min-width: 50px;
            }
            .table-mobile th:nth-child(2) { /* Matric */
                width: 10%;
                min-width: 25px;
            }
            .table-mobile th:nth-child(3) { /* Pres */
                width: 10%;
                min-width: 25px;
            }
            .table-mobile th:nth-child(4) { /* Aus */
                width: 10%;
                min-width: 25px;
            }
            .table-mobile th:nth-child(5) { /* Prof Aus */
                width: 15%;
                min-width: 35px;
            }
            .table-mobile th:nth-child(6) { /* Visit */
                width: 10%;
                min-width: 25px;
            }
            .table-mobile th:nth-child(7) { /* Bíb(A) */
                width: 8%;
                min-width: 25px;
            }
            .table-mobile th:nth-child(8) { /* Bíb(V) */
                width: 7%;
                min-width: 25px;
            }
        }

        /* Otimizações para orientação landscape em mobile */
        @media (max-width: 768px) and (orientation: landscape) {
            .table th, .table td {
                padding: 0.2rem 0.1rem;
                font-size: 0.7rem;
            }

            h2 {
                font-size: 1.1rem !important;
            }

            .mobile-hide {
                display: table-cell !important;
            }

            /* Botão otimizado para landscape */
            .btn-gerar {
                padding: 0.4rem 0.9rem !important;
                font-size: 0.8rem !important;
                min-width: 85px;
                height: 40px;
            }

            .form-row-mobile {
                flex-direction: row;
                gap: 0.6rem;
            }
        }

        @media print {
            .container-fluid {
                width: 100%;
                margin: 0;
                padding: 0;
            }

            .mobile-hide {
                display: table-cell !important;
            }

            .table th, .table td {
                padding: 0.5rem;
                font-size: 0.9rem;
            }

            img {
                width: 150px;
            }

            h2 {
                font-size: 1.5rem !important;
            }
        }

        /* Melhorias gerais */
        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .bg-light {
            background-color: #f8f9fa !important;
        }

        .font-weight-bold {
            font-weight: 700 !important;
        }

        .text-center {
            text-align: center !important;
        }

        /* Melhorar acessibilidade */
        .table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
        }

        /* Hover effects para melhor UX */
        .table tbody tr:hover {
            background-color: rgba(0,0,0,.075);
        }

        /* Melhorar espaçamento do formulário */
        .form-row {
            margin-bottom: 1rem;
        }

        /* Indicador de scroll horizontal */
        .scroll-indicator {
            text-align: center;
            color: #6c757d;
            font-size: 0.8rem;
            margin-bottom: 0.5rem;
            display: none;
        }

        @media (max-width: 768px) {
            .scroll-indicator {
                display: block;
            }
        }
    </style>

    <title>Relatório Geral - EBD</title>
</head>

<body>
    <div class="container-fluid px-3">
        <!-- Indicador de scroll horizontal -->
        <div class="scroll-indicator">
            <i class="fas fa-arrows-alt-h"></i> Deslize horizontalmente para ver mais colunas
        </div>

        <!-- Formulário de seleção de data -->
        <div class="row mb-3">
            <div class="col-lg-6 col-md-8 col-12">
                <div class="form-mobile">
                    <form action="{{ route('relatorio.geral') }}" method="GET" class="form-row-mobile">
                        <input type="date"
                               name="data"
                               class="form-control input-date-mobile"
                               value="{{ $dataFormatada ?? date('Y-m-d') }}"
                               required
                               max="{{ date('Y-m-d') }}">

                        <button type="submit" class="btn btn-primary btn-gerar">
                            <i class="fas fa-search"></i> Gerar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Conteúdo do relatório -->
        <section class="border section-border">
            <div class="d-flex align-items-center mb-3 flex-wrap">
                <img src="https://ebd.hiveble.com.br/img/missao-png.png" alt="Logo da ipp" class="mr-3">
                <div class="flex-grow-1">
                    <h2 class="mb-1">Relatório Geral - EBD da IPB Piçarreira - Dia {{ $dataExibicao }}</h2>
                    <h6>Powered by <a href="https://techgamespi.vercel.app/" target="_blank">José Cândido(ZECA)</a></h6>
                </div>
            </div>
        </section>

        <section class="mt-3">
            <div class="table-responsive">
                <table class="table table-bordered table-sm table-mobile">
                    <thead>
                        <tr class="text-center">
                            <th>Turma</th>
                            <th>Matric.</th>
                            <th>Pres.</th>
                            <th>Aus.</th>
                            <th>Prof. Aus.</th>
                            <th>Visit.</th>
                            <th>Bíb.(A)</th>
                            <th>Bíb.(V)</th>
                            <th>Total Pres.</th>
                            <th>Total Bíb.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Para calcular o total geral sem duplicar professores
                            $alunosUnicos = collect();
                            $professoresUnicos = collect();
                            $professoresAusentesUnicos = collect();
                            $total_geral_presentes = 0;
                            $total_geral_ausentes = 0;
                            $total_geral_professores_ausentes = 0;
                            $total_geral_visitantes = 0;
                            $total_geral_biblias_alunos = 0;
                            $total_geral_biblias_visitantes = 0;
                        @endphp

                        @if(isset($turmas) && $turmas->count() > 0)
                            @php
                                \Log::info('Data na view:', ['dataFormatada' => $dataFormatada]);
                            @endphp

                            @foreach($turmas as $turma)
                                @php
                                    $matriculados = $turma->matriculados ?? collect();
                                    $total_matriculados = $matriculados->count();
                                    $total_presentes = 0;
                                    $total_biblias = 0;
                                    $total_ausentes = 0;
                                    $total_professores_ausentes = 0;

                                    foreach ($matriculados as $matriculado) {
                                        // Separa alunos e professores para o total geral
                                        if ($matriculado->perfil_id == 3) {
                                            $professoresUnicos->push($matriculado->id);
                                        } else {
                                            $alunosUnicos->push($matriculado->id);
                                        }

                                        try {
                                            // Para professores, verificar presença em todas as turmas
                                            if ($matriculado->perfil_id == 3) {
                                                $turmasDoProfessor = \App\Models\ProfessorPorTurma::where('professor_id', $matriculado->id)->pluck('turma_id');
                                                $presenteEmAlgumaTurma = false;
                                                $ausenteEmTodas = true;

                                                foreach ($turmasDoProfessor as $turmaProf) {
                                                    $presencaTurma = \App\Helper\Helpers::verificaPresenca($matriculado->id, $turmaProf, $dataFormatada);
                                                    if ($presencaTurma == 'Presente') {
                                                        $presenteEmAlgumaTurma = true;
                                                        $ausenteEmTodas = false;
                                                        break;
                                                    }
                                                }

                                                // Verificar também na turma 7 (Oficiais e Professores)
                                                $presencaTurma7 = \App\Helper\Helpers::verificaPresenca($matriculado->id, 7, $dataFormatada);
                                                if ($presencaTurma7 == 'Presente') {
                                                    $presenteEmAlgumaTurma = true;
                                                    $ausenteEmTodas = false;
                                                }

                                                // Aplicar regra: presente se estiver presente em qualquer turma
                                                if ($presenteEmAlgumaTurma) {
                                                    $total_presentes++;
                                                } else {
                                                    $total_professores_ausentes++;
                                                    // Para o total geral, adicionar apenas uma vez
                                                    $professoresAusentesUnicos->push($matriculado->id);
                                                }
                                            } else {
                                                // Para alunos, lógica normal
                                                $presenca = \App\Helper\Helpers::verificaPresenca(
                                                    $matriculado->id,
                                                    $turma->id,
                                                    $dataFormatada
                                                );
                                                $material = \App\Helper\Helpers::verificamaterial(
                                                    $matriculado->id,
                                                    $turma->id,
                                                    $dataFormatada
                                                );
                                                if ($presenca == 'Presente') {
                                                    $total_presentes++;
                                                } else {
                                                    $total_ausentes++;
                                                }
                                                if ($material == 'checked') {
                                                    $total_biblias++;
                                                }
                                            }
                                        } catch (\Exception $e) {
                                            continue;
                                        }
                                    }

                                    // Busca visitantes
                                    $visitantes = \App\Helper\Helpers::contaVisitantes($turma->id, $dataFormatada);
                                    $total_visitantes = $visitantes['total'] ?? 0;
                                    $total_visitantes_com_biblia = $visitantes['com_material'] ?? 0;

                                    // Acumula totais gerais
                                    $total_geral_presentes += $total_presentes;
                                    $total_geral_ausentes += $total_ausentes;
                                    $total_geral_visitantes += $total_visitantes;
                                    $total_geral_biblias_alunos += $total_biblias;
                                    $total_geral_biblias_visitantes += $total_visitantes_com_biblia;
                                @endphp

                                <tr class="text-center">
                                    <td>{{ $turma->nome_turma }}</td>
                                    <td>{{ $total_matriculados }}</td>
                                    <td>{{ $total_presentes }}</td>
                                    <td>{{ $total_ausentes }}</td>
                                    <td>{{ $total_professores_ausentes }}</td>
                                    <td>{{ $total_visitantes }}</td>
                                    <td>{{ $total_biblias }}</td>
                                    <td>{{ $total_visitantes_com_biblia }}</td>
                                    <td>{{ $total_presentes + $total_visitantes }}</td>
                                    <td>{{ $total_biblias + $total_visitantes_com_biblia }}</td>
                                </tr>
                            @endforeach
                        @endif

                        @php
                            // Calcula o total geral sem duplicar professores
                            $total_geral_matriculados = $alunosUnicos->unique()->count() + $professoresUnicos->unique()->count();
                            $total_geral_professores_ausentes = $professoresAusentesUnicos->unique()->count();
                        @endphp

                        <!-- Linha de totais -->
                        <tr class="text-center font-weight-bold bg-light">
                            <td>TOTAIS</td>
                            <td>{{ $total_geral_matriculados }}</td>
                            <td>{{ $total_geral_presentes }}</td>
                            <td>{{ $total_geral_ausentes }}</td>
                            <td>{{ $total_geral_professores_ausentes }}</td>
                            <td>{{ $total_geral_visitantes }}</td>
                            <td>{{ $total_geral_biblias_alunos }}</td>
                            <td>{{ $total_geral_biblias_visitantes }}</td>
                            <td>{{ $total_geral_presentes + $total_geral_visitantes }}</td>
                            <td>{{ $total_geral_biblias_alunos + $total_geral_biblias_visitantes }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <footer class="mt-3">
            <p class="small text-center">Relatório Geral EBD da Igreja Presbiteriana da Piçarreira - {{ date('Y') }} </p>
            <p class="small text-center">Desenvolvido por <a href="https://techgamespi.vercel.app/" target="_blank">José Cândido(ZECA)</a></p>
        </footer>
    </div>
</body>
</html>
