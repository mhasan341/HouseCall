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
                <label for="synonym">{{ trans('cruds.drug.fields.synonym') }}</label>
                <input class="form-control {{ $errors->has('synonym') ? 'is-invalid' : '' }}" type="text" name="synonym" id="synonym" value="{{ old('synonym', $drug->synonym) }}">
                @if($errors->has('synonym'))
                    <span class="text-danger">{{ $errors->first('synonym') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.drug.fields.synonym_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="language">{{ trans('cruds.drug.fields.language') }}</label>
                <input class="form-control {{ $errors->has('language') ? 'is-invalid' : '' }}" type="text" name="language" id="language" value="{{ old('language', $drug->language) }}">
                @if($errors->has('language'))
                    <span class="text-danger">{{ $errors->first('language') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.drug.fields.language_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="psn">{{ trans('cruds.drug.fields.psn') }}</label>
                <input class="form-control {{ $errors->has('psn') ? 'is-invalid' : '' }}" type="text" name="psn" id="psn" value="{{ old('psn', $drug->psn) }}">
                @if($errors->has('psn'))
                    <span class="text-danger">{{ $errors->first('psn') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.drug.fields.psn_helper') }}</span>
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