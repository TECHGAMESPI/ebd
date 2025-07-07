<?php

namespace App\Livewire;

use App\Models\{AlunoPorTurma, Igreja, Turma};
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Livewire\Component;
use Carbon\Carbon;

class Relatorios extends Component
{
    public $turmas = [];
    public $igrejas;
    public $igreja_id;
    public $turma_id;
    public $data;
    public $mostrarRelatorio = false;
    public $dadosRelatorio = [];

    public array $rules = [
        'igreja_id' => 'required',
        'turma_id'  => 'required',
        'data' => 'required|date'
    ];

    public function mount()
    {
        $this->data = date('Y-m-d');
        $this->igrejas = Igreja::all();
        $this->turmas = [];
    }

    public function render()
    {
        return view('livewire.relatorios');
    }

    public function gerarRelatorio()
    {
        $this->validate([
            'igreja_id' => 'required',
            'turma_id' => 'required',
            'data' => 'required|date'
        ]);

        try {
            $dataFormatada = Carbon::parse($this->data)->format('d/m/Y');
        } catch (\Exception $e) {
            $dataFormatada = date('d/m/Y');
        }

        $turma = Turma::find($this->turma_id);
        $alunos = AlunoPorTurma::where('turma_id', $this->turma_id)
            ->orderBy('name')
            ->get();

        // Calcular estatísticas
        $totalAlunos = $alunos->count();
        $totalPresentes = 0;
        $totalBiblias = 0;
        $totalVisitantes = 0;
        $totalBibliasVisitantes = 0;

        foreach ($alunos as $aluno) {
            // Verificar presença
            $presenca = \App\Helper\Helpers::verificaPresenca($aluno->user_id, $this->turma_id, $this->data);
            if ($presenca == 'Presente') {
                $totalPresentes++;
            }

            // Verificar bíblia
            $material = \App\Helper\Helpers::verificamaterial($aluno->user_id, $this->turma_id, $this->data);
            if ($material == 'checked') {
                $totalBiblias++;
            }
        }

        // Buscar visitantes
        $visitantes = \App\Helper\Helpers::contaVisitantes($this->turma_id, $this->data);
        $totalVisitantes = $visitantes['total'] ?? 0;
        $totalBibliasVisitantes = $visitantes['com_material'] ?? 0;

        $this->dadosRelatorio = [
            'turma' => $turma,
            'alunos' => $alunos,
            'dataFormatada' => $dataFormatada,
            'totalAlunos' => $totalAlunos,
            'totalPresentes' => $totalPresentes,
            'totalBiblias' => $totalBiblias,
            'totalVisitantes' => $totalVisitantes,
            'totalBibliasVisitantes' => $totalBibliasVisitantes,
            'totalGeralPresentes' => $totalPresentes + $totalVisitantes,
            'totalGeralBiblias' => $totalBiblias + $totalBibliasVisitantes
        ];

        $this->mostrarRelatorio = true;
    }

    public function gerarPdf()
    {
        $this->validate([
            'igreja_id' => 'required',
            'turma_id' => 'required',
            'data' => 'required|date'
        ]);

        try {
            $dataFormatada = Carbon::parse($this->data)->format('d/m/Y');
        } catch (\Exception $e) {
            $dataFormatada = date('d/m/Y');
        }

        $turma = Turma::find($this->turma_id);
        $alunos = AlunoPorTurma::where('turma_id', $this->turma_id)
            ->orderBy('name')
            ->get();

        $pdf = Pdf::loadView('relatorios.aluno-por-turma', [
            'turma'         => $turma,
            'alunos'        => $alunos,
            'dataFormatada' => $dataFormatada,
            'data'   => $dataFormatada
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
        }, 'relatorio-' . date('d-m-Y', strtotime($this->data)) . '.pdf');
    }

    public function recuperaTurmas()
    {
        if (!$this->igreja_id) {
            $this->turmas = [];
            return;
        }

        $this->turmas = Turma::where('igreja_id', $this->igreja_id)->get();
    }
}
