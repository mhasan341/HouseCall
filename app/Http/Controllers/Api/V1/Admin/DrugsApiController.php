<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDrugRequest;
use App\Http\Requests\UpdateDrugRequest;
use App\Http\Resources\Admin\DrugResource;
use App\Models\Drug;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DrugsApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('drug_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DrugResource(Drug::all());
    }

    public function store(StoreDrugRequest $request)
    {
        $drug = Drug::create($request->all());

        return (new DrugResource($drug))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Drug $drug)
    {
        abort_if(Gate::denies('drug_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DrugResource($drug);
    }

    public function update(UpdateDrugRequest $request, Drug $drug)
    {
        $drug->update($request->all());

        return (new DrugResource($drug))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Drug $drug)
    {
        abort_if(Gate::denies('drug_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $drug->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
