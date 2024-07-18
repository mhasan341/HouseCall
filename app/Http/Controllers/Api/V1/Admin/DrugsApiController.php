<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDrugRequest;
use App\Http\Requests\UpdateDrugRequest;
use App\Http\Resources\Admin\DrugResource;
use App\Models\Drug;
use Gate;
use GuzzleHttp\Client;
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

    // Performs a search request to NIH and return the data we need
    public function search(Request $request)
    {
        $query = $request->input('drug_name');
        $client = new Client();

        try {
            $response = $client->get("https://rxnav.nlm.nih.gov/REST/drugs.json?name={$query}&expand=psn");
            $data = json_decode($response->getBody(), true);

            $results = collect($data['drugGroup']['conceptGroup'])
                ->flatMap(function ($group) {
                    return $group['conceptProperties'] ?? [];
                })
                ->filter(function ($drug) {
                    return $drug['tty'] === 'SBD';
                })
                ->map(function ($drug) {

                    return [
                        'rxcui' => $drug['rxcui'],
                        'name' => $drug['name'],
                        'synonym' => $drug['synonym'],
                        'language' => $drug['language'],
                        'psn' => $drug['psn']
                    ];
                })
                ->take(5);

            // saving to database for later use
            foreach ($results as $drug) {
                    Drug::create([
                        'rxcui' => $drug["rxcui"],
                        'name' => $drug["name"],
                        'synonym' => $drug['synonym'],
                        'language' => $drug['language'],
                        'psn' => $drug['psn']
                    ]);
            }

            // Return only rxcui and name in the response
            $responseResults = $results->map(function ($drug) {
                return [
                    'rxcui' => $drug['rxcui'],
                    'name' => $drug['name']
                ];
            })->values();

            return response()->json($responseResults);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch drug data'], 500);
        }
    }
}
