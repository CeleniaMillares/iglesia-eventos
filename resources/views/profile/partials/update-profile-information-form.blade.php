<p class="text-secondary mb-4">Actualiza tu nombre, correo electrónico y datos de contacto. Los campos marcados con * son obligatorios.</p>

<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    <div class="row g-3">
        <div class="col-md-6">
            <label for="nombre" class="form-label fw-semibold">Nombre *</label>
            <input id="nombre" name="nombre" type="text" value="{{ old('nombre', $user->nombre) }}" required autofocus autocomplete="name" class="form-control @error('nombre') is-invalid @enderror">
            @error('nombre')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label for="email" class="form-label fw-semibold">Correo Electrónico *</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" class="form-control @error('email') is-invalid @enderror">
            @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="small mt-2">
                    <span class="text-secondary">Tu correo electrónico todavía no está verificado.</span>
                    <button type="submit" form="send-verification" class="btn btn-link btn-sm p-0 align-baseline">Reenviar correo de verificación</button>
                </div>

                @if (session('status') === 'verification-link-sent')
                    <div class="alert alert-success py-2 px-3 small mt-2" role="status">Enviamos un nuevo enlace de verificación a tu correo electrónico.</div>
                @endif
            @endif
        </div>

        <div class="col-md-4">
            <label for="telefono" class="form-label fw-semibold">Teléfono</label>
            <input id="telefono" name="telefono" type="text" value="{{ old('telefono', $user->telefono) }}" class="form-control @error('telefono') is-invalid @enderror">
            @error('telefono')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label for="celular" class="form-label fw-semibold">Celular</label>
            <input id="celular" name="celular" type="text" value="{{ old('celular', $user->celular) }}" class="form-control @error('celular') is-invalid @enderror">
            @error('celular')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label for="foto" class="form-label fw-semibold">Foto de perfil</label>
            <input id="foto" name="foto" type="file" accept="image/jpeg,image/png" class="form-control @error('foto') is-invalid @enderror">
            @error('foto')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-12">
            <label for="direccion" class="form-label fw-semibold">Dirección</label>
            <input id="direccion" name="direccion" type="text" value="{{ old('direccion', $user->direccion) }}" class="form-control @error('direccion') is-invalid @enderror">
            @error('direccion')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
    </div>

    @if ($user->miembro)
        <div class="border-top pt-4 mt-4">
            <h3 class="h6 fw-bold text-uppercase text-secondary mb-3">Datos del miembro</h3>
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="tipo_sangre" class="form-label fw-semibold">Tipo de sangre</label>
                    <input id="tipo_sangre" name="tipo_sangre" type="text" value="{{ old('tipo_sangre', $user->miembro->tipo_sangre) }}" class="form-control @error('tipo_sangre') is-invalid @enderror">
                    @error('tipo_sangre')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="numero_referencia" class="form-label fw-semibold">Número de referencia</label>
                    <input id="numero_referencia" name="numero_referencia" type="text" value="{{ old('numero_referencia', $user->miembro->numero_referencia) }}" class="form-control @error('numero_referencia') is-invalid @enderror">
                    @error('numero_referencia')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="telefono_emergencia" class="form-label fw-semibold">Teléfono de emergencia</label>
                    <input id="telefono_emergencia" name="telefono_emergencia" type="text" value="{{ old('telefono_emergencia', $user->miembro->telefono_emergencia) }}" class="form-control @error('telefono_emergencia') is-invalid @enderror">
                    @error('telefono_emergencia')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="contacto_emergencia" class="form-label fw-semibold">Contacto de emergencia</label>
                    <input id="contacto_emergencia" name="contacto_emergencia" type="text" value="{{ old('contacto_emergencia', $user->miembro->contacto_emergencia) }}" class="form-control @error('contacto_emergencia') is-invalid @enderror">
                    @error('contacto_emergencia')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="profesion" class="form-label fw-semibold">Profesión</label>
                    <input id="profesion" name="profesion" type="text" value="{{ old('profesion', $user->miembro->profesion) }}" class="form-control @error('profesion') is-invalid @enderror">
                    @error('profesion')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="estado_civil" class="form-label fw-semibold">Estado civil</label>
                    <input id="estado_civil" name="estado_civil" type="text" value="{{ old('estado_civil', $user->miembro->estado_civil) }}" class="form-control @error('estado_civil') is-invalid @enderror">
                    @error('estado_civil')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="conyuge" class="form-label fw-semibold">Cónyuge</label>
                    <input id="conyuge" name="conyuge" type="text" value="{{ old('conyuge', $user->miembro->conyuge) }}" class="form-control @error('conyuge') is-invalid @enderror">
                    @error('conyuge')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="hijos" class="form-label fw-semibold">Número de hijos</label>
                    <input id="hijos" name="hijos" type="number" min="0" value="{{ old('hijos', $user->miembro->hijos) }}" class="form-control @error('hijos') is-invalid @enderror">
                    @error('hijos')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    @endif

    <div class="d-flex flex-wrap align-items-center gap-3 border-top pt-3 mt-4">
        <button type="submit" class="btn btn-primary px-4">Guardar</button>

        @if (session('status') === 'profile-updated')
            <span class="text-success fw-semibold small" role="status">Guardado</span>
        @endif
    </div>
</form>
