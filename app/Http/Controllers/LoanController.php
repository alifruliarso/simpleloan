<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLoanRequest;
use App\Http\Resources\LoanResource;
use App\Services\LoanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;
use OpenApi\Attributes as OAT;

class LoanController extends Controller
{
    protected $loanService;

    public function __construct(LoanService $loanService
    ) {
        $this->loanService = $loanService;
    }

    #[OAT\Get(
        tags: ['loan'],
        path: '/api/loans',
        summary: 'Get list of loans',
        operationId: 'LoanController.index',
        security: [['BearerToken' => []]],
        responses: [
            new OAT\Response(
                response: HttpResponse::HTTP_OK,
                description: 'OK',
                content: new OAT\JsonContent(type:'array', items: new OAT\Items(ref:'#/components/schemas/LoanResource'))
            ),
        ]
    )]
    public function index(Request $request)
    {
        $page = $request->get('page') ?: 1;
        $limit = $request->get('limit') ?: 10;
        $data = $this->loanService->list($request->user()->id, $page, $limit);

        return LoanResource::collection($data);
    }

    #[OAT\Post(
        tags: ['loan'],
        path: '/api/loans',
        summary: 'Create a loan',
        operationId: 'LoanController.store',
        security: [['BearerToken' => []]],
        requestBody: new OAT\RequestBody(
            required: true,
            content: new OAT\JsonContent(ref: '#/components/schemas/CreateLoanRequest')

        ),
        responses: [
            new OAT\Response(
                response: HttpResponse::HTTP_CREATED,
                description: 'Created',
                content: new OAT\JsonContent(ref: '#/components/schemas/LoanResource')
            ),
            new OAT\Response(
                response: HttpResponse::HTTP_UNPROCESSABLE_ENTITY,
                description: 'Unprocessable entity',
                content: new OAT\JsonContent(ref: '#/components/schemas/ValidationError')
            ),
        ]
    )]
    public function store(CreateLoanRequest $request): JsonResponse
    {
        $loan = $this->loanService->create($request);

        return Response::json(new LoanResource($loan), HttpResponse::HTTP_CREATED);
    }

    #[OAT\Put(
        tags: ['loan'],
        path: '/api/loans/{id}/approve',
        summary: 'Approve a loan',
        operationId: 'LoanController.approve',
        security: [['BearerToken' => []]],
        parameters:[
            new OAT\Parameter(
                name:'id', in:'path', required:true, schema:new OAT\Schema(type:'integer'), example: 1, description:'Loan ID'
            ),
        ],
        responses: [
            new OAT\Response(
                response: HttpResponse::HTTP_NO_CONTENT,
                description: 'No content'
            ),
        ]
    )]
    public function approve(Request $request, $id): JsonResponse
    {
        $this->loanService->approve($id);

        return Response::json(null, HttpResponse::HTTP_NO_CONTENT);
    }
}
