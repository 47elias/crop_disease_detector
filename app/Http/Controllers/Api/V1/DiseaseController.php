<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ListDiseasesRequest;
use App\Http\Resources\DiseaseResource;
use App\Models\Disease;
use Illuminate\Support\Carbon;

class DiseaseController extends Controller
{
    public function index(ListDiseasesRequest $request): mixed
    {
        $query = Disease::query()->with(['crop', 'treatmentGuidelines'])->orderBy('model_class_index');
        if ($request->filled('updated_since')) {
            $since = Carbon::parse($request->string('updated_since')->toString());
            $query->where('updated_at', '>', $since);
        }

        return DiseaseResource::collection($query->get());
    }
}
