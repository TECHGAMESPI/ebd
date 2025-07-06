<?php

namespace App\Http\Controllers;

use App\Models\{Turma, User, Chamada, UsuariosPorIgreja};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RelatorioPeriodoController extends Controller
{
    public function index()
    {
        return view('relatorios.periodo.index');
    }

    public function gerarRelatorio(Request $request)
    {
        $request->validate([
            'tipo_periodo' => 'required|in:mensal,semestral,anual',
            'ano' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'mes' => 'required_if:tipo_periodo,mensal|integer|min:1|max:12',
            'semestre' => 'required_if:tipo_periodo,semestral|integer|min:1|max:2',
        ]);

        $tipoPeriodo = $request->tipo_periodo;
        $ano = $request->ano;
        $mes = $request->mes;
        $semestre = $request->semestre;

        // Determinar período
        switch ($tipoPeriodo) {
            case 'mensal':
                $dataInicio = Carbon::create($ano, $mes, 1)->startOfMonth();
                $dataFim = Carbon::create($ano, $mes, 1)->endOfMonth();
                $meses = [
                    1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
                    5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
                    9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
                ];
                $periodoTexto = $meses[$mes] . '/' . $ano;
                break;

            case 'semestral':
                $mesInicio = ($semestre == 1) ? 1 : 7;
                $mesFim = ($semestre == 1) ? 6 : 12;
                $dataInicio = Carbon::create($ano, $mesInicio, 1)->startOfMonth();
                $dataFim = Carbon::create($ano, $mesFim, 1)->endOfMonth();
                $periodoTexto = $semestre . 'º Semestre/' . $ano;
                break;

            case 'anual':
                $dataInicio = Carbon::create($ano, 1, 1)->startOfYear();
                $dataFim = Carbon::create($ano, 12, 31)->endOfYear();
                $periodoTexto = $ano;
                break;
        }

        // Buscar dados do período
        $dados = $this->buscarDadosPeriodo($dataInicio, $dataFim);

        return view('relatorios.periodo.resultado', [
            'dados' => $dados,
            'periodoTexto' => $periodoTexto,
            'tipoPeriodo' => $tipoPeriodo,
            'dataInicio' => $dataInicio,
            'dataFim' => $dataFim,
            'ano' => $ano,
            'mes' => $mes,
            'semestre' => $semestre
        ]);
    }

    private function buscarDadosPeriodo($dataInicio, $dataFim)
    {
        $igrejaId = User::getIgreja()->id;

        // Estatísticas gerais
        $estatisticasGerais = [
            'total_presencas' => Chamada::where('igreja_id', $igrejaId)
                ->whereBetween('data', [$dataInicio->format('Y-m-d'), $dataFim->format('Y-m-d')])
                ->where('falta_justificada', false)
                ->where('livro', false)
                ->count(),

            'total_faltas' => Chamada::where('igreja_id', $igrejaId)
                ->whereBetween('data', [$dataInicio->format('Y-m-d'), $dataFim->format('Y-m-d')])
                ->where('falta_justificada', true)
                ->count(),

            'total_livros' => Chamada::where('igreja_id', $igrejaId)
                ->whereBetween('data', [$dataInicio->format('Y-m-d'), $dataFim->format('Y-m-d')])
                ->where('livro', true)
                ->count(),

            'total_biblias' => Chamada::where('igreja_id', $igrejaId)
                ->whereBetween('data', [$dataInicio->format('Y-m-d'), $dataFim->format('Y-m-d')])
                ->where('material', true)
                ->count(),

            'total_visitantes' => DB::table('visitantes')
                ->where('igreja_id', $igrejaId)
                ->whereBetween('data', [$dataInicio->format('Y-m-d'), $dataFim->format('Y-m-d')])
                ->sum('quantidade'),

            'total_biblias_visitantes' => DB::table('visitantes')
                ->where('igreja_id', $igrejaId)
                ->whereBetween('data', [$dataInicio->format('Y-m-d'), $dataFim->format('Y-m-d')])
                ->sum('biblias'),
        ];

        // Dados por turma
        $turmas = Turma::where('igreja_id', $igrejaId)->get();
        $dadosPorTurma = [];

        foreach ($turmas as $turma) {
            // Alunos da turma (exclui professores nas turmas comuns)
            if ($turma->id == 7) {
                // Na turma 7, conta todos os alunos cadastrados normalmente
                $alunos = DB::table('aluno_por_turmas')
                    ->where('turma_id', $turma->id)
                    ->where('igreja_id', $igrejaId)
                    ->count();
            } else {
                // Nas demais turmas, só conta alunos que não são professores
                $alunos = DB::table('aluno_por_turmas')
                    ->join('users', 'aluno_por_turmas.user_id', '=', 'users.id')
                    ->where('aluno_por_turmas.turma_id', $turma->id)
                    ->where('aluno_por_turmas.igreja_id', $igrejaId)
                    ->where('users.perfil_id', '!=', 3)
                    ->count();
            }

            // Professores associados à turma
            $professores = DB::table('professor_por_turmas')
                ->where('turma_id', $turma->id)
                ->where('igreja_id', $igrejaId)
                ->count();

            // Para a turma 7, adicionar todos os professores da igreja que não estão associados
            if ($turma->id == 7) {
                $professoresAssociados = DB::table('professor_por_turmas')
                    ->where('igreja_id', $igrejaId)
                    ->pluck('professor_id');

                $todosProfessores = DB::table('usuarios_por_igrejas')
                    ->join('users', 'usuarios_por_igrejas.user_id', '=', 'users.id')
                    ->where('usuarios_por_igrejas.igreja_id', $igrejaId)
                    ->where('users.perfil_id', 3)
                    ->whereNotIn('users.id', $professoresAssociados)
                    ->count();

                $professores += $todosProfessores;
            }

            // Presenças da turma
            $presencas = Chamada::where('turma_id', $turma->id)
                ->where('igreja_id', $igrejaId)
                ->whereBetween('data', [$dataInicio->format('Y-m-d'), $dataFim->format('Y-m-d')])
                ->where('falta_justificada', false)
                ->where('livro', false)
                ->count();

            // Faltas da turma
            $faltas = Chamada::where('turma_id', $turma->id)
                ->where('igreja_id', $igrejaId)
                ->whereBetween('data', [$dataInicio->format('Y-m-d'), $dataFim->format('Y-m-d')])
                ->where('falta_justificada', true)
                ->count();

            // Livros da turma
            $livros = Chamada::where('turma_id', $turma->id)
                ->where('igreja_id', $igrejaId)
                ->whereBetween('data', [$dataInicio->format('Y-m-d'), $dataFim->format('Y-m-d')])
                ->where('livro', true)
                ->count();

            // Bíblias da turma
            $biblias = Chamada::where('turma_id', $turma->id)
                ->where('igreja_id', $igrejaId)
                ->whereBetween('data', [$dataInicio->format('Y-m-d'), $dataFim->format('Y-m-d')])
                ->where('material', true)
                ->count();

            // Visitantes da turma
            $visitantes = DB::table('visitantes')
                ->where('turma_id', $turma->id)
                ->where('igreja_id', $igrejaId)
                ->whereBetween('data', [$dataInicio->format('Y-m-d'), $dataFim->format('Y-m-d')])
                ->sum('quantidade');

            // Bíblias dos visitantes da turma
            $bibliasVisitantes = DB::table('visitantes')
                ->where('turma_id', $turma->id)
                ->where('igreja_id', $igrejaId)
                ->whereBetween('data', [$dataInicio->format('Y-m-d'), $dataFim->format('Y-m-d')])
                ->sum('biblias');

            $dadosPorTurma[] = [
                'turma' => $turma,
                'alunos' => $alunos,
                'professores' => $professores,
                'presencas' => $presencas,
                'faltas' => $faltas,
                'livros' => $livros,
                'biblias' => $biblias,
                'visitantes' => $visitantes,
                'biblias_visitantes' => $bibliasVisitantes,
                'total_matriculados' => $turma->id == 7 ? $alunos + $professores : $alunos,
                'percentual_presenca' => ($alunos + $professores) > 0 ?
                    round(($presencas / (($alunos + $professores) * $this->calcularDiasAula($dataInicio, $dataFim))) * 100, 1) : 0
            ];
        }

        // Top alunos (mais presenças)
        $topAlunos = DB::table('chamadas')
            ->join('users', 'chamadas.aluno_id', '=', 'users.id')
            ->where('chamadas.igreja_id', $igrejaId)
            ->whereBetween('chamadas.data', [$dataInicio->format('Y-m-d'), $dataFim->format('Y-m-d')])
            ->where('chamadas.falta_justificada', false)
            ->where('chamadas.livro', false)
            ->select('users.name', DB::raw('COUNT(*) as total_presencas'))
            ->groupBy('users.id', 'users.name')
            ->orderBy('total_presencas', 'desc')
            ->limit(10)
            ->get();

        // Evolução mensal (para relatórios anuais e semestrais)
        $evolucaoMensal = [];
        if ($dataFim->diffInMonths($dataInicio) > 1) {
            $mesAtual = $dataInicio->copy();
            while ($mesAtual <= $dataFim) {
                $presencasMes = Chamada::where('igreja_id', $igrejaId)
                    ->whereYear('data', $mesAtual->year)
                    ->whereMonth('data', $mesAtual->month)
                    ->where('falta_justificada', false)
                    ->where('livro', false)
                    ->count();

                $meses = [
                    1 => 'Jan', 2 => 'Fev', 3 => 'Mar', 4 => 'Abr',
                    5 => 'Mai', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
                    9 => 'Set', 10 => 'Out', 11 => 'Nov', 12 => 'Dez'
                ];

                $evolucaoMensal[] = [
                    'mes' => $meses[$mesAtual->month] . '/' . $mesAtual->year,
                    'presencas' => $presencasMes
                ];

                $mesAtual->addMonth();
            }
        }

        return [
            'estatisticasGerais' => $estatisticasGerais,
            'dadosPorTurma' => $dadosPorTurma,
            'topAlunos' => $topAlunos,
            'evolucaoMensal' => collect($evolucaoMensal)
        ];
    }

    private function calcularDiasAula($dataInicio, $dataFim)
    {
        // Assumindo que as aulas são aos domingos (dia 0)
        $diasAula = 0;
        $data = $dataInicio->copy();

        while ($data <= $dataFim) {
            if ($data->dayOfWeek == 0) { // Domingo
                $diasAula++;
            }
            $data->addDay();
        }

        return $diasAula;
    }
}
