@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.drug.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.drugs.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.drug.fields.id') }}
                        </th>
                        <td>
                            {{ $drug->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.drug.fields.rxcui') }}
                        </th>
                        <td>
                            {{ $drug->rxcui }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.drug.fields.name') }}
                        </th>
                        <td>
                            {{ $drug->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.drug.fields.description') }}
                        </th>
                        <td>
                            {{ $drug->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.drug.fields.side_effects') }}
                        </th>
                        <td>
                            {{ $drug->side_effects }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.drugs.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection