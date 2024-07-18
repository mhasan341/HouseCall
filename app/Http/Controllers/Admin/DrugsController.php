<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDrugRequest;
use App\Http\Requests\StoreDrugRequest;
use App\Http\Requests\UpdateDrugRequest;
use App\Models\Drug;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class DrugsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('drug_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Drug::query()->select(sprintf('%s.*', (new Drug)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'drug_show';
                $editGate      = 'drug_edit';
                $deleteGate    = 'drug_delete';
                $crudRoutePart = 'drugs';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('rxcui', function ($row) {
                return $row->rxcui ?: '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.drugs.index');
    }

    public function create()
    {
        abort_if(Gate::denies('drug_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.drugs.create');
    }
    //u607848850_hcthp
//qA:Q4p!q]99
//u607848850_ksa

    public function store(StoreDrugRequest $request)
    {
        $drug = Drug::create($request->all());

        return redirect()->route('admin.drugs.index');
    }

    public function edit(Drug $drug)
    {
        abort_if(Gate::denies('drug_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.drugs.edit', compact('drug'));
    }

    public function update(UpdateDrugRequest $request, Drug $drug)
    {
        $drug->update($request->all());

        return redirect()->route('admin.drugs.index');
    }

    public function show(Drug $drug)
    {
        abort_if(Gate::denies('drug_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.drugs.show', compact('drug'));
    }

    public function destroy(Drug $drug)
    {
        abort_if(Gate::denies('drug_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $drug->delete();

        return back();
    }

    public function massDestroy(MassDestroyDrugRequest $request)
    {
        $drugs = Drug::find(request('ids'));

        foreach ($drugs as $drug) {
            $drug->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
