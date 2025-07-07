<?php

namespace App\Http\Controllers;

use App\Models\{Turma, User, Chamada, UsuariosPorIgreja};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class RelatorioGeralDiaController extends Controller
{
    public function index()
    {
        return view('relatorios.geral-dia.index');
    }

    public function gerarRelatorio(Request $request)
    {
        $request->validate([
            'data' => 'required|date'
        ]);

        try {
            $data = Carbon::parse($request->data);
            $dataFormatada = $data->format('Y-m-d');
            $dataExibicao = $data->format('d/m/Y');
            $igrejaId = User::getIgreja()->id;

            // Estatísticas gerais do dia
            $estatisticasGerais = [
                'total_presencas' => Chamada::where('igreja_id', $igrejaId)
                    ->where('data', $dataFormatada)
                    ->where('falta_justificada', false)
                    ->where('livro', false)
                    ->count(),

                'total_faltas' => Chamada::where('igreja_id', $igrejaId)
                    ->where('data', $dataFormatada)
                    ->where('falta_justificada', true)
                    ->count(),

                'total_livros' => Chamada::where('igreja_id', $igrejaId)
                    ->where('data', $dataFormatada)
                    ->where('livro', true)
                    ->count(),

                'total_biblias' => Chamada::where('igreja_id', $igrejaId)
                    ->where('data', $dataFormatada)
                    ->where('material', true)
                    ->count(),

                'total_visitantes' => DB::table('visitantes')
                    ->where('igreja_id', $igrejaId)
                    ->where('data', $dataFormatada)
                    ->sum('quantidade'),

                'total_biblias_visitantes' => DB::table('visitantes')
                    ->where('igreja_id', $igrejaId)
                    ->where('data', $dataFormatada)
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
                    ->where('data', $dataFormatada)
                    ->where('falta_justificada', false)
                    ->where('livro', false)
                    ->count();

                // Faltas da turma
                $faltas = Chamada::where('turma_id', $turma->id)
                    ->where('data', $dataFormatada)
                    ->where('falta_justificada', true)
                    ->count();

                // Livros da turma
                $livros = Chamada::where('turma_id', $turma->id)
                    ->where('data', $dataFormatada)
                    ->where('livro', true)
                    ->count();

                // Bíblias da turma
                $biblias = Chamada::where('turma_id', $turma->id)
                    ->where('data', $dataFormatada)
                    ->where('material', true)
                    ->count();

                // Visitantes da turma
                $visitantes = DB::table('visitantes')
                    ->where('turma_id', $turma->id)
                    ->where('data', $dataFormatada)
                    ->sum('quantidade');

                // Bíblias dos visitantes da turma
                $bibliasVisitantes = DB::table('visitantes')
                    ->where('turma_id', $turma->id)
                    ->where('data', $dataFormatada)
                    ->sum('biblias');

                $totalMatriculados = $alunos + $professores;
                $percentualPresenca = $totalMatriculados > 0 ? round(($presencas / $totalMatriculados) * 100, 1) : 0;

                $dadosPorTurma[] = [
                    'turma' => $turma,
                    'alunos' => $alunos,
                    'professores' => $professores,
                    'total_matriculados' => $totalMatriculados,
                    'presencas' => $presencas,
                    'faltas' => $faltas,
                    'livros' => $livros,
                    'biblias' => $biblias,
                    'visitantes' => $visitantes,
                    'biblias_visitantes' => $bibliasVisitantes,
                    'percentual_presenca' => $percentualPresenca
                ];
            }

            // Top alunos do dia
            $topAlunos = DB::table('chamadas')
                ->join('users', 'chamadas.aluno_id', '=', 'users.id')
                ->where('chamadas.igreja_id', $igrejaId)
                ->where('chamadas.data', $dataFormatada)
                ->where('chamadas.falta_justificada', false)
                ->where('chamadas.livro', false)
                ->select('users.name', 'users.id', DB::raw('COUNT(*) as presencas'))
                ->groupBy('users.id', 'users.name')
                ->orderBy('presencas', 'DESC')
                ->limit(10)
                ->get();

            return view('relatorios.geral-dia.resultado', [
                'data' => $data,
                'dataFormatada' => $dataFormatada,
                'dataExibicao' => $dataExibicao,
                'estatisticasGerais' => $estatisticasGerais,
                'dadosPorTurma' => $dadosPorTurma,
                'topAlunos' => $topAlunos
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao gerar relatório do dia:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erro ao gerar o relatório');
        }
    }

    public function gerarPdf(Request $request)
    {
        $request->validate([
            'data' => 'required|date'
        ]);

        try {
            $data = Carbon::parse($request->data);
            $dataFormatada = $data->format('Y-m-d');
            $dataExibicao = $data->format('d/m/Y');
            $igrejaId = User::getIgreja()->id;

            // Estatísticas gerais do dia
            $estatisticasGerais = [
                'total_presencas' => Chamada::where('igreja_id', $igrejaId)
                    ->where('data', $dataFormatada)
                    ->where('falta_justificada', false)
                    ->where('livro', false)
                    ->count(),

                'total_faltas' => Chamada::where('igreja_id', $igrejaId)
                    ->where('data', $dataFormatada)
                    ->where('falta_justificada', true)
                    ->count(),

                'total_livros' => Chamada::where('igreja_id', $igrejaId)
                    ->where('data', $dataFormatada)
                    ->where('livro', true)
                    ->count(),

                'total_biblias' => Chamada::where('igreja_id', $igrejaId)
                    ->where('data', $dataFormatada)
                    ->where('material', true)
                    ->count(),

                'total_visitantes' => DB::table('visitantes')
                    ->where('igreja_id', $igrejaId)
                    ->where('data', $dataFormatada)
                    ->sum('quantidade'),

                'total_biblias_visitantes' => DB::table('visitantes')
                    ->where('igreja_id', $igrejaId)
                    ->where('data', $dataFormatada)
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
                    ->where('data', $dataFormatada)
                    ->where('falta_justificada', false)
                    ->where('livro', false)
                    ->count();

                // Faltas da turma
                $faltas = Chamada::where('turma_id', $turma->id)
                    ->where('data', $dataFormatada)
                    ->where('falta_justificada', true)
                    ->count();

                // Livros da turma
                $livros = Chamada::where('turma_id', $turma->id)
                    ->where('data', $dataFormatada)
                    ->where('livro', true)
                    ->count();

                // Bíblias da turma
                $biblias = Chamada::where('turma_id', $turma->id)
                    ->where('data', $dataFormatada)
                    ->where('material', true)
                    ->count();

                // Visitantes da turma
                $visitantes = DB::table('visitantes')
                    ->where('turma_id', $turma->id)
                    ->where('data', $dataFormatada)
                    ->sum('quantidade');

                // Bíblias dos visitantes da turma
                $bibliasVisitantes = DB::table('visitantes')
                    ->where('turma_id', $turma->id)
                    ->where('data', $dataFormatada)
                    ->sum('biblias');

                $totalMatriculados = $alunos + $professores;
                $percentualPresenca = $totalMatriculados > 0 ? round(($presencas / $totalMatriculados) * 100, 1) : 0;

                $dadosPorTurma[] = [
                    'turma' => $turma,
                    'alunos' => $alunos,
                    'professores' => $professores,
                    'total_matriculados' => $totalMatriculados,
                    'presencas' => $presencas,
                    'faltas' => $faltas,
                    'livros' => $livros,
                    'biblias' => $biblias,
                    'visitantes' => $visitantes,
                    'biblias_visitantes' => $bibliasVisitantes,
                    'percentual_presenca' => $percentualPresenca
                ];
            }

            // Top alunos do dia
            $topAlunos = DB::table('chamadas')
                ->join('users', 'chamadas.aluno_id', '=', 'users.id')
                ->where('chamadas.igreja_id', $igrejaId)
                ->where('chamadas.data', $dataFormatada)
                ->where('chamadas.falta_justificada', false)
                ->where('chamadas.livro', false)
                ->select('users.name', 'users.id', DB::raw('COUNT(*) as presencas'))
                ->groupBy('users.id', 'users.name')
                ->orderBy('presencas', 'DESC')
                ->limit(10)
                ->get();

            $pdf = Pdf::loadView('relatorios.geral-dia.pdf', [
                'data' => $data,
                'dataFormatada' => $dataFormatada,
                'dataExibicao' => $dataExibicao,
                'estatisticasGerais' => $estatisticasGerais,
                'dadosPorTurma' => $dadosPorTurma,
                'topAlunos' => $topAlunos
            ]);

            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
                'charset' => 'utf-8',
                'enable_remote' => true,
                'enable_css_float' => true,
                'enable_javascript' => false,
                'is_remote_enabled' => true
            ]);

            $pdf->setPaper('A4', 'portrait');

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->stream();
            }, 'relatorio-geral-dia-' . $dataFormatada . '.pdf');

        } catch (\Exception $e) {
            \Log::error('Erro ao gerar PDF do relatório geral do dia:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erro ao gerar o PDF');
        }
    }
}
