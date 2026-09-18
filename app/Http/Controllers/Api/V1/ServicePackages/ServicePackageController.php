<?php

namespace App\Http\Controllers\Api\V1\ServicePackages;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServicePackageResource;
use App\Models\ServicePackage;

class ServicePackageController extends Controller
{

    public function index()
    {
        $packages = ServicePackage::query()->active()->get();

        return response()->json([
            'data' => ServicePackageResource::collection($packages),
        ]);
    }
}
