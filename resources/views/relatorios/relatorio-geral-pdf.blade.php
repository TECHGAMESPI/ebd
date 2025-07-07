<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.1/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #222;
            margin: 0;
            padding: 0;
        }
        .container-pdf {
            max-width: 900px;
            margin: 0 auto;
            padding: 24px 18px 18px 18px;
        }
        .header-pdf {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 18px 18px 10px 18px;
            margin-bottom: 18px;
            background: #fff;
        }
        .header-table {
            width: 100%;
            border: none;
        }
        .header-table td {
            border: none;
            vertical-align: middle;
        }
        .logo-pdf {
            width: 90px;
            min-width: 70px;
            max-width: 100px;
        }
        .titulo-pdf {
            font-size: 1.7rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            line-height: 1.2;
        }
        .data-pdf {
            font-size: 1.15rem;
            font-weight: 600;
            margin-bottom: 0;
        }
        .table {
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
        }
        .table th, .table td {
            padding: 0.45rem 0.3rem;
            text-align: center;
            vertical-align: middle;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
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
        footer {
            margin-top: 18px;
        }
        .small {
            font-size: 0.85rem;
        }
        @media print {
            body, .container-pdf {
                margin: 0;
                padding: 0;
                box-shadow: none;
            }
        }
    </style>
    <title>Relatório Geral - EBD</title>
</head>
<body>
    <div class="container-pdf">
        <div class="header-pdf">
            <table class="header-table">
                <tr>
                    <td style="width: 110px; text-align: left;">
                        <img src="https://ebd.hiveble.com.br/img/missao-png.png" alt="Logo da Igreja Presbiteriana da Piçarreira" class="logo-pdf">
                    </td>
                    <td style="text-align: left;">
                        <div class="titulo-pdf">Relatório Geral - EBD da Igreja Presbiteriana da Piçarreira</div>
                        <div class="data-pdf">Data: {{ $dataExibicao }}</div>
                    </td>
                </tr>
            </table>
        </div>
        <div>
            <table class="table table-bordered">
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
                    @foreach($turmas as $turma)
                        @php
                            $matriculados = $turma->matriculados ?? collect();
                            $total_matriculados = $matriculados->count();
                            $total_presentes = 0;
                            $total_biblias = 0;
                            $total_ausentes = 0;
                            $total_professores_ausentes = 0;
                            foreach ($matriculados as $matriculado) {
                                if ($matriculado->perfil_id == 3) {
                                    $professoresUnicos->push($matriculado->id);
                                } else {
                                    $alunosUnicos->push($matriculado->id);
                                }
                                try {
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
                                        $presencaTurma7 = \App\Helper\Helpers::verificaPresenca($matriculado->id, 7, $dataFormatada);
                                        if ($presencaTurma7 == 'Presente') {
                                            $presenteEmAlgumaTurma = true;
                                            $ausenteEmTodas = false;
                                        }
                                        if ($presenteEmAlgumaTurma) {
                                            $total_presentes++;
                                        } else {
                                            $total_professores_ausentes++;
                                            $professoresAusentesUnicos->push($matriculado->id);
                                        }
                                    } else {
                                        $presenca = \App\Helper\Helpers::verificaPresenca($matriculado->id, $turma->id, $dataFormatada);
                                        $material = \App\Helper\Helpers::verificamaterial($matriculado->id, $turma->id, $dataFormatada);
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
                            $visitantes = \App\Helper\Helpers::contaVisitantes($turma->id, $dataFormatada);
                            $total_visitantes = $visitantes['total'] ?? 0;
                            $total_visitantes_com_biblia = $visitantes['com_material'] ?? 0;
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
                    @php
                        $total_geral_matriculados = $alunosUnicos->unique()->count() + $professoresUnicos->unique()->count();
                        $total_geral_professores_ausentes = $professoresAusentesUnicos->unique()->count();
                    @endphp
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
        <footer>
            <p class="small text-center">Relatório Geral EBD {{ date('Y') }}</p>
            <p class="small text-center">Desenvolvido por <a href="https://techgamespi.vercel.app/" target="_blank">José Cândido(ZECA)</a></p>
        </footer>
    </div>
</body>
</html>
