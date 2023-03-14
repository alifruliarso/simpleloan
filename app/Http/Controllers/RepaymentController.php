<?php

namespace App\Http\Controllers;

use App\Http\Requests\RepaymentRequest;
use App\Http\Resources\RepaymentResource;
use App\Services\PayRepaymentService;
use App\Services\RepaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;
use OpenApi\Attributes as OAT;

class RepaymentController extends Controller
{
    protected $repaymentService;

    protected $payRepaymentService;

    public function __construct(RepaymentService $repaymentService, PayRepaymentService $payRepaymentService
    ) {
        $this->repaymentService = $repaymentService;
        $this->payRepaymentService = $payRepaymentService;
    }

    #[OAT\Get(
        tags: ['loan'],
        path: '/api/loans/{id}/repayment',
        summary: 'Get list of repayment',
        operationId: 'RepaymentController.index',
        security: [['BearerToken' => []]],
        parameters:[
            new OAT\Parameter(
                name:'id', in:'path', required:true, schema:new OAT\Schema(type:'integer'), example: 1, description:'Loan ID'
            ),
        ],
        responses: [
            new OAT\Response(
                response: HttpResponse::HTTP_OK,
                description: 'OK',
                content: new OAT\JsonContent(type:'array', items: new OAT\Items(ref:'#/components/schemas/RepaymentResource'))
            ),
        ]
    )]
    public function index(Request $request, $id)
    {
        $repayment = $this->repaymentService->getByLoanId($id);
        $this->authorize('view', $repayment);

        $page = $request->input('page') ?: 1;
        $limit = $request->input('limit') ?: 10;
        $status = $request->input('status');
        $filters = [
            'loanId' => $id,
        ];
        if (strlen($status) != 0) {
            $filters['status'] = $status;
        }
        $data = $this->repaymentService->list($request->user()->id, $filters, $page, $limit);

        return RepaymentResource::collection($data);
    }

    #[OAT\Post(
        tags: ['loan'],
        path: '/api/loans/pay',
        summary: 'Pay a loan',
        operationId: 'RepaymentController.pay',
        security: [['BearerToken' => []]],
        requestBody: new OAT\RequestBody(
            required: true,
            content: new OAT\JsonContent(ref: '#/components/schemas/RepaymentRequest')

        ),
        responses: [
            new OAT\Response(
                response: HttpResponse::HTTP_NO_CONTENT,
                description: 'No content'
            ),
        ]
    )]
    public function pay(RepaymentRequest $request): JsonResponse
    {
        $repayment = $this->repaymentService->getByLoanId($request->loan_id);
        $this->authorize('view', $repayment);

        $userId = $request->user()->id;
        $this->payRepaymentService->pay($request, $userId);

        return Response::json(null, HttpResponse::HTTP_NO_CONTENT);
    }
}
