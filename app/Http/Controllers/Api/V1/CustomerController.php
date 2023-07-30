<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\CustomerFilter;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Http\Requests\V1\StoreCustomerRequest;
use App\Http\Requests\V1\UpdateCustomerRequest;
use App\Http\Resources\V1\CustomerResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CustomerController extends Controller
{

    public function index(Request $request)
    {
        $filter = new CustomerFilter();

        $queryItem = $filter->transform($request); // [column, operator, value]

        $includeInvoices = $request->query('includeInvoices');

        $customers = Customer::where($queryItem);

        if ($includeInvoices) {
            $customers = $customers->with('invoices');
        }

        return CustomerResource::collection($customers->paginate()->appends($request->query()));
    }



    public function store(StoreCustomerRequest $request)
    {
        return CustomerResource::make(Customer::create($request->all()));
    }


    public function show(Customer $customer)
    {
        Gate::authorize('view', $customer);
        $includeInvoices = request()->query('includeInvoices');
        if ($includeInvoices) {
            return CustomerResource::make($customer->loadMissing('invoices'));
        }
        return CustomerResource::make($customer);
    }


    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->all());
        return response()->json([ "message" => "customer updated successfully" ], 200);
    }


    public function destroy(Customer $customer)
    {
        Gate::authorize('delete', $customer);
        $customer->delete();
        return response()->json([ "message" => "customer deleted successfully" ], 200);
    }
}
