<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Services\DomainService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    public function __construct(
        private DomainService $domainService
    ) {
    }

    /**
     * @param Request $request
     * @param DomainService $domainService
     * @return JsonResponse
     */
    public function getDomain(int $id): JsonResponse
    {
        return response()->json(Domain::find($id), 200);
    }

    /**
     * @param Request $request
     * @param DomainService $domainService
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        return response()->json(Domain::all(), 200);
    }

    /**
     * @param Request $request
     * @param DomainService $domainService
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $create = $this->domainService->create($request);

        return response()->json($create);
    }

    /**
     * @param Request $request
     * @param DomainService $domainService
     * @return JsonResponse
     */
    public function patch(Request $request): JsonResponse
    {
        $create = $this->domainService->patch($request);

        return response()->json($create);
    }


    /**
     * @param int $id
     * @param DomainService $domainService
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $delete = $this->domainService->delete($id);

        return response()->json($delete);
    }
}
