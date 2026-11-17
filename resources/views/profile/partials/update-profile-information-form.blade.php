<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="nombre" :value="__('Nombre')" />
            <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full" :value="old('nombre', $user->nombre)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('nombre')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="telefono" :value="__('Teléfono')" />
            <x-text-input id="telefono" name="telefono" type="text" class="mt-1 block w-full" :value="old('telefono', $user->telefono)" />
            <x-input-error class="mt-2" :messages="$errors->get('telefono')" />
        </div>

        <div>
            <x-input-label for="celular" :value="__('Celular')" />
            <x-text-input id="celular" name="celular" type="text" class="mt-1 block w-full" :value="old('celular', $user->celular)" />
            <x-input-error class="mt-2" :messages="$errors->get('celular')" />
        </div>

        <div>
            <x-input-label for="direccion" :value="__('Dirección')" />
            <x-text-input id="direccion" name="direccion" type="text" class="mt-1 block w-full" :value="old('direccion', $user->direccion)" />
            <x-input-error class="mt-2" :messages="$errors->get('direccion')" />
        </div>

        <div>
            <x-input-label for="foto" :value="__('Foto de perfil')" />
            <input id="foto" name="foto" type="file" accept="image/jpeg,image/png" class="mt-1 block w-full">
            <x-input-error class="mt-2" :messages="$errors->get('foto')" />
        </div>

        @if ($user->miembro)
            <div class="border-t pt-6">
                <h3 class="text-base font-medium text-gray-900">Datos del miembro</h3>
            </div>

            <div>
                <x-input-label for="tipo_sangre" :value="__('Tipo de sangre')" />
                <x-text-input id="tipo_sangre" name="tipo_sangre" type="text" class="mt-1 block w-full" :value="old('tipo_sangre', $user->miembro->tipo_sangre)" />
                <x-input-error class="mt-2" :messages="$errors->get('tipo_sangre')" />
            </div>

            <div>
                <x-input-label for="numero_referencia" :value="__('Número de referencia')" />
                <x-text-input id="numero_referencia" name="numero_referencia" type="text" class="mt-1 block w-full" :value="old('numero_referencia', $user->miembro->numero_referencia)" />
                <x-input-error class="mt-2" :messages="$errors->get('numero_referencia')" />
            </div>

            <div>
                <x-input-label for="contacto_emergencia" :value="__('Contacto de emergencia')" />
                <x-text-input id="contacto_emergencia" name="contacto_emergencia" type="text" class="mt-1 block w-full" :value="old('contacto_emergencia', $user->miembro->contacto_emergencia)" />
                <x-input-error class="mt-2" :messages="$errors->get('contacto_emergencia')" />
            </div>

            <div>
                <x-input-label for="telefono_emergencia" :value="__('Teléfono de emergencia')" />
                <x-text-input id="telefono_emergencia" name="telefono_emergencia" type="text" class="mt-1 block w-full" :value="old('telefono_emergencia', $user->miembro->telefono_emergencia)" />
                <x-input-error class="mt-2" :messages="$errors->get('telefono_emergencia')" />
            </div>

            <div>
                <x-input-label for="estado_civil" :value="__('Estado civil')" />
                <x-text-input id="estado_civil" name="estado_civil" type="text" class="mt-1 block w-full" :value="old('estado_civil', $user->miembro->estado_civil)" />
                <x-input-error class="mt-2" :messages="$errors->get('estado_civil')" />
            </div>

            <div>
                <x-input-label for="conyuge" :value="__('Cónyuge')" />
                <x-text-input id="conyuge" name="conyuge" type="text" class="mt-1 block w-full" :value="old('conyuge', $user->miembro->conyuge)" />
                <x-input-error class="mt-2" :messages="$errors->get('conyuge')" />
            </div>

            <div>
                <x-input-label for="hijos" :value="__('Número de hijos')" />
                <x-text-input id="hijos" name="hijos" type="number" min="0" class="mt-1 block w-full" :value="old('hijos', $user->miembro->hijos)" />
                <x-input-error class="mt-2" :messages="$errors->get('hijos')" />
            </div>

            <div>
                <x-input-label for="profesion" :value="__('Profesión')" />
                <x-text-input id="profesion" name="profesion" type="text" class="mt-1 block w-full" :value="old('profesion', $user->miembro->profesion)" />
                <x-input-error class="mt-2" :messages="$errors->get('profesion')" />
            </div>
        @endif

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
