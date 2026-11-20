<div>
    <label for="nombre" class="form-label">Nombre del rol</label>
    <input id="nombre" name="nombre" type="text" maxlength="50" required @readonly($esRolBase ?? false) value="{{ old('nombre', $role->nombre ?? '') }}" class="form-control @error('nombre') is-invalid @enderror">
    @if($esRolBase ?? false)
        <div class="form-text">Los roles base del sistema no pueden renombrarse.</div>
    @endif
    @error('nombre')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div>
    <label for="descripcion" class="form-label">Descripción</label>
    <textarea id="descripcion" name="descripcion" rows="4" maxlength="255" class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $role->descripcion ?? '') }}</textarea>
    @error('descripcion')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="d-flex justify-content-end gap-2 border-top pt-3">
    <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
</div>
