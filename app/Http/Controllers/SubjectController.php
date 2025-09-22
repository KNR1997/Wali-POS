<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroySubjectRequest;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Models\Subject;
use App\Services\Subject\SubjectService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * The service instance
     */
    private SubjectService $subjectService;

    /**
     * Constructor
     */
    public function __construct(SubjectService $subjectService)
    {
        $this->subjectService = $subjectService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('list', Subject::class);

        return $this->subjectService->index($request->all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return JsonResponse|\Illuminate\Http\Response
     *
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Subject::class);

        return $this->responseDataSuccess(['properties' => $this->properties()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function store(StoreSubjectRequest $request)
    {
        $this->authorize('create', Subject::class);

        $input = $request->validated();
        $record = $this->subjectService->create($input);
        if (! is_null($record)) {
            return $this->responseStoreSuccess(['record' => $record]);
        } else {
            return $this->responseStoreFail();
        }
    }

    /**
     *  Show the form for editing the specified resource.
     *
     *
     * @return SubjectResource|JsonResponse
     *
     * @throws AuthorizationException
     */
    public function show(Subject $subject)
    {
        $this->authorize('view', Subject::class);

        $model = $this->subjectService->get($subject);

        return $this->responseDataSuccess(['model' => $model, 'properties' => $this->properties()]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     *
     * @return JsonResponse|\Illuminate\Http\Response
     *
     * @throws AuthorizationException
     */
    public function edit(Subject $subject)
    {
        $this->authorize('edit', Subject::class);

        return $this->show($subject);
    }

    /**
     * Update the specified resource in storage.
     *
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        $this->authorize('edit', Subject::class);

        $data = $request->validated();
        if ($this->subjectService->update($subject, $data)) {
            return $this->responseUpdateSuccess(['record' => $subject->fresh()]);
        } else {
            return $this->responseUpdateFail();
        }
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function destroy(DestroySubjectRequest $request, Subject $subject)
    {
        $this->authorize('delete', Subject::class);

        if ($this->subjectService->destroy($subject)) {
            return $this->responseDeleteSuccess(['record' => $subject]);
        }

        return $this->responseDeleteFail();

    }

    /**
     * Render properties
     *
     * @return array
     */
    public function properties()
    {
        return [];
    }
}