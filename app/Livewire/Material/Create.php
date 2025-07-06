<?php

namespace App\Livewire\Material;

    use Illuminate\Database\QueryException;
    use Livewire\Component;

    class Create extends Component
    {
        public array $fields = [];
        public $turmas = [];
        public $anos = [];

        protected array $rules = [
            'fields.titulo'      => 'required',
            'fields.descricao'   => 'required',
            'fields.publicar_em' => 'required',
            'fields.turma_id'    => 'required',
            'fields.ano'         => 'required',
        ];

        protected array $messages = [
            'required' => 'Campo obrigatório',
        ];

        public function mount()
        {
            $this->fields['material_global'] = false;
            $this->turmas = \App\Models\Turma::where('igreja_id', auth()->user()->getIgreja()->id)->where('is_active', true)->orderBy('nome_turma')->get();
            $anoAtual = date('Y');
            $this->anos = range($anoAtual, $anoAtual + 3);
        }
        public function render()
        {
            return view('livewire.material.create', [
                'turmas' => $this->turmas,
                'anos' => $this->anos
            ]);
        }

        public function store()
        {
            $this->validate();

            try {
                $this->fields['igreja_id'] = auth()->user()->getIgreja()->id;
                $material                  = auth()->user()->materials()->create($this->fields);

                toastr()->addSuccess('material criado', 'Sucesso');

                return redirect(route('material.show', $material->id));
            } catch (QueryException $e) {

                env('APP_ENV') == 'local' ? toastr()->addError($e->getMessage()) : toastr()->addError('Não foi possível cadastrar', 'Erro!');
            }
        }

        public function changeGlobalPublish()
        {

            if ($this->fields['material_global'] == false) {
                return $this->fields['material_global'] = true;
            }

            if ($this->fields['material_global'] == true) {
                return $this->fields['material_global'] = false;
            }
        }
    }
