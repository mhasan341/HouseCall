@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.drug.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.drugs.update", [$drug->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="rxcui">{{ trans('cruds.drug.fields.rxcui') }}</label>
                <input class="form-control {{ $errors->has('rxcui') ? 'is-invalid' : '' }}" type="text" name="rxcui" id="rxcui" value="{{ old('rxcui', $drug->rxcui) }}" required>
                @if($errors->has('rxcui'))
                    <span class="text-danger">{{ $errors->first('rxcui') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.drug.fields.rxcui_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.drug.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $drug->name) }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.drug.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="description">{{ trans('cruds.drug.fields.description') }}</label>
                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description" required>{{ old('description', $drug->description) }}</textarea>
                @if($errors->has('description'))
                    <span class="text-danger">{{ $errors->first('description') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.drug.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="side_effects">{{ trans('cruds.drug.fields.side_effects') }}</label>
                <textarea class="form-control {{ $errors->has('side_effects') ? 'is-invalid' : '' }}" name="side_effects" id="side_effects">{{ old('side_effects', $drug->side_effects) }}</textarea>
                @if($errors->has('side_effects'))
                    <span class="text-danger">{{ $errors->first('side_effects') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.drug.fields.side_effects_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection