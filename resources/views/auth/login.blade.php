<x-guest-layout>

    <style>
        .message-error {
            color: red;
            font-weight: 500;
        }

        .padding-small {
            padding: 0.5rem;
        }
    </style>

    <div class="row justify-content-center pt-5">

        <div class="col-xl-5 col-lg-5 col-md-6">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="col-lg pb-4">
                        <div class="p-2 pb-3 ">
                            <div class="text-center pb-4">
                                <img src="{{ asset('img/logo_ebd.png') }}" class="img-fluid pt-5" alt="login">
                            </div>
                            <form class="user" method="post" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group">
                                    <input type="text" name="email" class="form-control form-control-user"
                                        id="email" placeholder="Insira o seu email." value="{{ old('email') }}">
                                    @error('email')
                                        <small class="message-error">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <input type="password" name="password" class="form-control form-control-user"
                                        id="password" placeholder="Senha">
                                    @error('password')
                                        <small class="message-error">{{ $message }}</small>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Login
                                </button>
                                <!-- <div class="text-center mt-2 ">
                                    <p>ou</p>
                                </div>

                                <div class="mt-2">
                                    <a href="{{ url('auth/google') }}" class="btn btn-danger  btn-user btn-block ">
                                        <div class="d-flex align-items-center justify-content-center ">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-google" viewBox="0 0 16 16">
                                                <path
                                                    d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z" />
                                            </svg>
                                            <span class="padding-small">
                                                Login
                                                com Google</span>
                                        </div>
                                    </a>
                                </div> -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
