<x-app-layout>
    <div class="surface-card p-3 p-md-4">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
            <div>
                <h2 class="h4 fw-bold mb-1">Editar miembro</h2>
                <p class="text-secondary mb-0">Actualiza los datos personales y de contacto.</p>
            </div>
            <a href="{{ route('miembros.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Volver
            </a>
        </div>

        @if($errors->any())
            <div role="alert" class="alert alert-danger">Revisa los campos indicados.</div>
        @endif

        @php($miembro = $user->miembro)
        <form action="{{ route('miembros.update', $user) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <section class="row g-3 mb-4" aria-labelledby="account-details-title">
                <h3 id="account-details-title" class="col-12 h5">Datos de cuenta</h3>
                <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombre completo *</label>
                    <input id="nombre" name="nombre" type="text" required maxlength="100" value="{{ old('nombre', $user->nombre) }}" class="form-control @error('nombre') is-invalid @enderror">
                    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Correo electrónico *</label>
                    <input id="email" name="email" type="email" required value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input id="telefono" name="telefono" type="text" maxlength="20" value="{{ old('telefono', $miembro?->telefono) }}" class="form-control @error('telefono') is-invalid @enderror">
                    @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="celular" class="form-label">Celular</label>
                    <input id="celular" name="celular" type="text" maxlength="30" value="{{ old('celular', $user->celular) }}" class="form-control @error('celular') is-invalid @enderror">
                    @error('celular')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input id="direccion" name="direccion" type="text" maxlength="255" value="{{ old('direccion', $miembro?->direccion) }}" class="form-control @error('direccion') is-invalid @enderror">
                    @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="foto" class="form-label">Foto de perfil</label>
                    <input id="foto" name="foto" type="file" accept="image/jpeg,image/png" class="form-control @error('foto') is-invalid @enderror">
                    @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                @if($user->foto)
                    <div class="col-md-6 d-flex align-items-end">
                        <img src="{{ asset('storage/'.$user->foto) }}" alt="Foto actual" class="rounded" style="width:56px;height:56px;object-fit:cover;">
                    </div>
                @endif
            </section>

            <section class="row g-3 border-top pt-4 mb-4" aria-labelledby="member-details-title">
                <h3 id="member-details-title" class="col-12 h5">Datos del miembro</h3>
                <div class="col-md-6">
                    <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento</label>
                    <input id="fecha_nacimiento" name="fecha_nacimiento" type="date" max="{{ today()->toDateString() }}" value="{{ old('fecha_nacimiento', $miembro?->fecha_nacimiento?->toDateString()) }}" class="form-control @error('fecha_nacimiento') is-invalid @enderror">
                    @error('fecha_nacimiento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="tipo_sangre" class="form-label">Tipo de sangre</label>
                    <select id="tipo_sangre" name="tipo_sangre" class="form-select @error('tipo_sangre') is-invalid @enderror">
                        <option value="">Selecciona</option>
                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $tipo)
                            <option value="{{ $tipo }}" @selected(old('tipo_sangre', $miembro?->tipo_sangre) === $tipo)>{{ $tipo }}</option>
                        @endforeach
                    </select>
                    @error('tipo_sangre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="numero_referencia" class="form-label">Número de referencia</label>
                    <input id="numero_referencia" name="numero_referencia" type="text" maxlength="255" value="{{ old('numero_referencia', $miembro?->numero_referencia) }}" class="form-control @error('numero_referencia') is-invalid @enderror">
                    @error('numero_referencia')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="estado_civil" class="form-label">Estado civil</label>
                    <select id="estado_civil" name="estado_civil" class="form-select @error('estado_civil') is-invalid @enderror">
                        <option value="">Selecciona</option>
                        @foreach(['soltero', 'casado', 'divorciado', 'viudo'] as $estado)
                            <option value="{{ $estado }}" @selected(old('estado_civil', $miembro?->estado_civil) === $estado)>{{ ucfirst($estado) }}</option>
                        @endforeach
                    </select>
                    @error('estado_civil')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="conyuge" class="form-label">Cónyuge</label>
                    <input id="conyuge" name="conyuge" type="text" maxlength="255" value="{{ old('conyuge', $miembro?->conyuge) }}" class="form-control @error('conyuge') is-invalid @enderror">
                    @error('conyuge')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="hijos" class="form-label">Número de hijos</label>
                    <input id="hijos" name="hijos" type="number" min="0" value="{{ old('hijos', $miembro?->hijos ?? 0) }}" class="form-control @error('hijos') is-invalid @enderror">
                    @error('hijos')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="profesion" class="form-label">Profesión</label>
                    <input id="profesion" name="profesion" type="text" maxlength="255" value="{{ old('profesion', $miembro?->profesion) }}" class="form-control @error('profesion') is-invalid @enderror">
                    @error('profesion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="contacto_emergencia" class="form-label">Contacto de emergencia</label>
                    <input id="contacto_emergencia" name="contacto_emergencia" type="text" maxlength="255" value="{{ old('contacto_emergencia', $miembro?->contacto_emergencia) }}" class="form-control @error('contacto_emergencia') is-invalid @enderror">
                    @error('contacto_emergencia')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="telefono_emergencia" class="form-label">Teléfono de emergencia</label>
                    <input id="telefono_emergencia" name="telefono_emergencia" type="text" maxlength="255" value="{{ old('telefono_emergencia', $miembro?->telefono_emergencia) }}" class="form-control @error('telefono_emergencia') is-invalid @enderror">
                    @error('telefono_emergencia')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </section>

            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                <a href="{{ route('miembros.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </form>
    </div>
</x-app-layout>
