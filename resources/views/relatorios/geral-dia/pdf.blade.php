<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório Geral do Dia - EBD</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            margin: 0 0 10px 0;
            color: #333;
        }

        .header h2 {
            font-size: 18px;
            margin: 0;
            color: #666;
        }

        .info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .stats-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-card {
            flex: 1;
            min-width: 200px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-align: center;
            background-color: #fff;
        }

        .stat-card h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #666;
        }

        .stat-card .value {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .total-row {
            background-color: #e9ecef;
            font-weight: bold;
        }

        .turma-name {
            text-align: left;
            font-weight: bold;
        }

        .chart-section {
            margin-top: 30px;
            page-break-inside: avoid;
        }

        .chart-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .page-break {
            page-break-before: always;
        }

        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório Geral do Dia - EBD</h1>
        <h2>Igreja Presbiteriana da Piçarreira</h2>
        <p>Data: {{ $dataExibicao }}</p>
    </div>

    <div class="info">
        <strong>Data do Relatório:</strong> {{ $dataExibicao }}<br>
        <strong>Total de Turmas:</strong> {{ count($dadosPorTurma) }}
    </div>

    <!-- Estatísticas Gerais -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total de Presenças</h3>
            <div class="value">{{ $estatisticasGerais['total_presencas'] }}</div>
        </div>
        <div class="stat-card">
            <h3>Total de Faltas</h3>
            <div class="value">{{ $estatisticasGerais['total_faltas'] }}</div>
        </div>
        <div class="stat-card">
            <h3>Total de Livros</h3>
            <div class="value">{{ $estatisticasGerais['total_livros'] }}</div>
        </div>
        <div class="stat-card">
            <h3>Total de Bíblias</h3>
            <div class="value">{{ $estatisticasGerais['total_biblias'] }}</div>
        </div>
        <div class="stat-card">
            <h3>Total de Visitantes</h3>
            <div class="value">{{ $estatisticasGerais['total_visitantes'] }}</div>
        </div>
        <div class="stat-card">
            <h3>Bíblias Visitantes</h3>
            <div class="value">{{ $estatisticasGerais['total_biblias_visitantes'] }}</div>
        </div>
    </div>

    <!-- Dados por Turma -->
    <table>
        <thead>
            <tr>
                <th>Turma</th>
                <th>Matric.</th>
                <th>Pres.</th>
                <th>Aus.</th>
                <th>Livros</th>
                <th>Bíblias</th>
                <th>Visit.</th>
                <th>Bíb. Visit.</th>
                <th>% Presença</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dadosPorTurma as $dado)
                <tr>
                    <td class="turma-name">{{ $dado['turma']->nome_turma }}</td>
                    <td>{{ $dado['total_matriculados'] }}</td>
                    <td>{{ $dado['presencas'] }}</td>
                    <td>{{ $dado['faltas'] }}</td>
                    <td>{{ $dado['livros'] }}</td>
                    <td>{{ $dado['biblias'] }}</td>
                    <td>{{ $dado['visitantes'] }}</td>
                    <td>{{ $dado['biblias_visitantes'] }}</td>
                    <td>{{ $dado['percentual_presenca'] }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Top Alunos -->
    @if($topAlunos->count() > 0)
    <div class="chart-section">
        <div class="chart-title">Top 10 Alunos - Mais Presenças no Dia</div>
        <table>
            <thead>
                <tr>
                    <th>Posição</th>
                    <th>Nome</th>
                    <th>Presenças no Dia</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topAlunos as $index => $aluno)
                    <tr>
                        <td>{{ $index + 1 }}º</td>
                        <td class="turma-name">{{ $aluno->name }}</td>
                        <td>{{ $aluno->presencas }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        <p>Relatório gerado em {{ date('d/m/Y H:i:s') }}</p>
        <p>Powered by José Cândido (ZECA) - https://techgamespi.vercel.app/</p>
    </div>
</body>
</html>
