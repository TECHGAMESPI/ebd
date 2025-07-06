<?php

namespace App\Http\Controllers;

use App\Models\Turma;
use App\Models\User;
use App\Models\Chamada;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RelatorioGeralController extends Controller
{
    public function index()
    {
        $dataAtual = Carbon::now()->format('Y-m-d');

        return $this->gerarRelatorio(new Request(['data' => $dataAtual]));
    }

    public function gerarRelatorio(Request $request)
    {
        $request->validate([
            'data' => 'required|date'
        ]);

        try {
            $dataFormatada = Carbon::parse($request->data)->format('Y-m-d');
            $dataExibicao = Carbon::parse($request->data)->format('d/m/Y');

            $turmas = Turma::where('igreja_id', User::getIgreja()->id)
                ->with(['alunos.aluno'])
                ->get();

            // Lista para controlar professores já contados no total geral
            $professoresContados = collect();

            // Para cada turma, buscar professores associados e adicionar à lista de matriculados
            foreach ($turmas as $turma) {
                // Alunos já associados normalmente
                $alunos = $turma->alunos()->whereHas('aluno', function($query) {
                    $query->where('is_active', true);
                })->get();

                // Professores associados à turma
                $professores = \App\Models\ProfessorPorTurma::where('turma_id', $turma->id)
                    ->with('user')
                    ->get()
                    ->pluck('user');

                // Junta alunos (User) e professores (User), evitando duplicidade
                $matriculados = $alunos->pluck('aluno');
                foreach ($professores as $professor) {
                    if ($professor && !$matriculados->contains('id', $professor->id)) {
                        $matriculados->push($professor);
                    }
                }

                // Para a turma 7, garantir que todos os professores da igreja estejam presentes
                if ($turma->id == 7) {
                    $igrejaId = User::getIgreja()->id;
                    $todosProfessores = \App\Models\UsuariosPorIgreja::where('igreja_id', $igrejaId)
                        ->join('users', 'usuarios_por_igrejas.user_id', '=', 'users.id')
                        ->where('users.perfil_id', 3)
                        ->select('users.*')
                        ->get();
                    foreach ($todosProfessores as $professor) {
                        if ($professor && !$matriculados->contains('id', $professor->id)) {
                            $matriculados->push($professor);
                        }
                    }
                }

                // Para o total geral, marcar professores como já contados
                foreach ($matriculados as $matriculado) {
                    if ($matriculado->perfil_id == 3) {
                        $professoresContados->push($matriculado->id);
                    }
                }

                // Adiciona a lista de matriculados à turma (para a view)
                $turma->matriculados = $matriculados;
            }

            // Adiciona a lista de professores contados para a view calcular o total correto
            $turmas->professoresContados = $professoresContados->unique();

            return view('relatorios.relatorio-geral', [
                'turmas' => $turmas,
                'dataFormatada' => $dataFormatada,
                'dataExibicao' => $dataExibicao
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao processar data:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erro ao processar a data selecionada');
        }
    }
}
