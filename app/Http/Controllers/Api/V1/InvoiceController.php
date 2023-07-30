<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\InvoiceFilter;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Http\Requests\V1\StoreInvoiceRequest;
use App\Http\Requests\V1\UpdateInvoiceRequest;
use App\Http\Resources\V1\InvoiceResource;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = new InvoiceFilter();
        $queryItem = $filter->transform($request); // [column, operator, value]
        if (count($queryItem) == 0) {
            return InvoiceResource::collection(Invoice::paginate()) ;
        } else {
            $invoices = Invoice::where($queryItem)->paginate();
            return InvoiceResource::collection($invoices->appends($request->query()));
        }
    }


    public function bulkStore(StoreInvoiceRequest $request)
    {
        $bulk = collect($request->all())->map(function($arr, $key) {
            return Arr::except($arr, ['paidDate', 'billedDate', 'customerId']);
        });
        Invoice::insert($bulk->toArray());
    }


    public function show(Invoice $invoice)
    {
        Gate::authorize('view', $invoice);
        return InvoiceResource::make($invoice);
    }


    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $invoice->update($request->all());
        return response()->json([ "message" => "invoice updated successfully" ], 200);
    }


    public function destroy(Invoice $invoice)
    {
        Gate::authorize('delete', $invoice);
        $invoice->delete();
        return response()->json([ "message" => "invoice deleted successfully" ], 200);
    }
}
