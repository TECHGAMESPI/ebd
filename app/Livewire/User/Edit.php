<?php

namespace App\Livewire\User;

use App\Models\{Perfil, User};
use DateTime;
use Exception;
use Illuminate\Support\Facades\{Hash, Storage};
use Intervention\Image\Facades\Image;
use Livewire\{Component, WithFileUploads};

class Edit extends Component
{
    use WithFileUploads;

    public $user_id;

    public $profile;

    public $profiles;

    public $name;

    public $email;

    public $googleEmail;

    public $maritalStatus;

    public $phone;

    public $date;

    public $path_photo;

    public $photo;

    public $turmas;
    public $turma_id;

    protected $rules = [
        'name'          => 'required',
        'email'         => 'required',
        'maritalStatus' => 'required',
        'phone'         => 'nullable',
        'date'          => 'required',
        'googleEmail'   => 'nullable|email',
        'photo'         => 'nullable|mimes:jpg,jpeg,png',
    ];

    protected $messages = [
        'name.required'          => 'Campo obrigatório',
        'email.required'         => 'Campo obrigatório',
        'maritalStatus.required' => 'Campo obrigatório',
        'date.required'          => 'Campo obrigatório',
        'googleEmail.email'      => 'E-mail inválido',
        'photo.mimes'            => 'A foto precisa ser de um formato válido (jpg,jpeg,png)',
    ];

    public function mount()
    {
        $this->profiles = Perfil::where('id', '!=', Perfil::ADMINISTRADOR)->get();

        if (auth()->user()->perfil_id == Perfil::ADMINISTRADOR) {
            $this->profiles = Perfil::all();
        }

        $user                = User::find(request('id'));
        $this->user_id       = $user->id;
        $this->profile       = $user->perfil_id;
        $this->name          = $user->name;
        $this->email         = $user->email;
        $this->googleEmail   = $user->google_email;
        $this->maritalStatus = $user->estado_civil;
        $this->path_photo    = $user->path_photo;
        $this->phone         = $user->telefone;
        $this->date          = $user->data_nascimento;

        // Carregar turmas disponíveis e turma atual
        $this->turmas = \App\Models\Turma::where('igreja_id', $user->getIgreja()->id)->where('is_active', true)->orderBy('nome_turma')->get();
        $turmaAtual = \App\Models\AlunoPorTurma::where('user_id', $user->id)->first();
        $this->turma_id = $turmaAtual ? $turmaAtual->turma_id : '';
    }

    public function render()
    {
        return view('livewire.user.edit');
    }

    public function update()
    {
        $this->validate();

        try {
            $user                  = User::find($this->user_id);
            $user->name            = $this->name;
            $user->email           = $this->email;
            $user->google_email    = $this->googleEmail;
            $user->estado_civil    = $this->maritalStatus;
            $user->perfil_id       = $this->profile;
            $user->data_nascimento = $this->date;
            $user->telefone        = $this->phone;
            $user->path_photo      = $this->photo ? $this->updatePhoto($user->path_photo) : $user->path_photo;
            $user->save();

            // Atualizar turma do usuário (apenas uma por vez)
            if ($this->turma_id) {
                // Remove vínculos antigos
                \App\Models\AlunoPorTurma::where('user_id', $user->id)->delete();
                // Cria novo vínculo
                \App\Models\AlunoPorTurma::create([
                    'user_id' => $user->id,
                    'turma_id' => $this->turma_id,
                    'igreja_id' => $user->getIgreja()->id,
                    'name' => $user->name,
                ]);
            }

            toastr()->addSuccess('Usuário atualizado com sucesso', 'Feito!');

            return redirect(route('user.edit', ['id' => $user->id]));
        } catch (Exception $e) {
            // Para depuração, você pode querer logar a exceção:
            // \Log::error('Erro ao atualizar usuário: ' . $e->getMessage());
            toastr()->addError('Não foi possível atualizar', 'Erro!');
            // Se precisar ver o erro completo, descomente a linha abaixo (apenas para depuração)
            // dd($e->getMessage());
        }
    }

    /**
     * Atualiza a foto do perfil, apaga a antiga se necessário e salva a nova.
     *
     * @param string|null $old_path_photo O caminho da foto antiga no banco de dados.
     * @return string O novo caminho da foto.
     */
    protected function updatePhoto(?string $old_path_photo): string
    {
        // 1. Apaga a foto antiga se existir e não for a foto padrão
        // Verifica se $old_path_photo não é nulo e não é o caminho da foto padrão
        if ($old_path_photo && $old_path_photo !== "img/profile/user-512.webp") {
            // Extrai o caminho relativo ao disco 'public'
            // Ex: 'storage/users/nome.webp' -> 'users/nome.webp'
            $path_to_delete = str_replace('storage/', '', $old_path_photo);

            // Verifica se o arquivo existe no disco 'public' antes de tentar apagar
            if (Storage::disk('public')->exists($path_to_delete)) {
                Storage::disk('public')->delete($path_to_delete);
            }
        }

        $today = new DateTime('now');
        // Gera um nome de arquivo único usando hash SHA1 do nome original e microsegundos da data atual
        $nameFile = hash('sha1', $this->photo->getClientOriginalName() . $today->format('u'));
        $finalFileName = $nameFile . '.webp'; // Define a extensão final como webp

        // 2. Processa a imagem com Intervention Image
        $img = Image::make($this->photo->getRealPath());
        // Se quiser recortar a imagem para 200x200, descomente e ajuste:
        // $img->fit(200, 200, function ($constraint) {
        //     $constraint->upsize();
        // });

        // 3. Salva a imagem usando o disco 'public' do Laravel
        // O método stream() de Intervention Image permite obter o conteúdo da imagem como um stream de dados.
        // Converte a imagem para WebP com 50% de qualidade e obtém o stream.
        $imgStream = $img->encode('webp', 50)->stream();

        // O Laravel (via Storage::disk('public')->put) criará o diretório 'users'
        // dentro de 'storage/app/public' se ele não existir,
        // desde que as permissões de 'storage/app/public' estejam corretas.
        Storage::disk('public')->put('users/' . $finalFileName, $imgStream);

        // 4. Retorna o caminho para ser salvo no banco de dados
        // Este é o caminho relativo que será prefixado com asset('storage/') no Blade.
        return 'storage/users/' . $finalFileName;
    }

    public function resetPassword()
    {
        try {
            $user           = User::find($this->user_id);
            $user->password = Hash::make(env('PASSWORD_DEFAULT'));
            $user->save();
            toastr()->addSuccess('Senha resetada com sucesso', 'Feito!');
        } catch (Exception $e) {
            toastr()->addError('Não foi possível resetar a senha', 'Erro!');
        }
    }
}
