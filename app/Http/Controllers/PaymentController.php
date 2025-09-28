<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Lease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('Admin')) {
            $payments = Payment::with(['lease.unit.property', 'tenant'])->latest()->paginate(10);
            return view('admin.payments.index', compact('payments'));
        } elseif ($user->hasRole('Landlord')) {
            // Payments for landlord's properties
            $payments = Payment::whereHas('lease.unit.property', function ($query) use ($user) {
                $query->where('owner_id', $user->id);
            })->with(['lease.unit.property', 'tenant'])->latest()->paginate(10);
            return view('landlord.payment', compact('payments'));
        } elseif ($user->hasRole('Tenant')) {
            // Payments for tenant
            $payments = Payment::where('tenant_id', $user->id)->with(['lease.unit.property'])->latest()->paginate(10);
            return view('tenant.payments.index', compact('payments'));
        }
        abort(403);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Not implemented for now
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Not implemented for now
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payment = Payment::with(['lease.unit.property', 'tenant'])->findOrFail($id);
        $user = Auth::user();
        if ($user->hasRole('Admin') || ($user->hasRole('Landlord') && $payment->lease->unit->property->user_id == $user->id)) {
            return view('landlord.payment', compact('payment')); // Assuming same view for show
        }
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Not implemented for now
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Not implemented for now
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Not implemented for now
        abort(404);
    }

    /**
     * Display tenant's payments.
     *
     * @return \Illuminate\Http\Response
     */
    public function tenantPayments()
    {
        $user = Auth::user();
        $tenant = $user->tenant; // Assuming User has tenant relation
        if (!$tenant) {
            abort(403);
        }
        $payments = Payment::where('tenant_id', $tenant->id)->with(['lease.unit.property'])->latest()->paginate(10);
        return view('tenant.payments.index', compact('payments'));
    }

    /**
     * Display specific payment for tenant.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function tenantShow($id)
    {
        $user = Auth::user();
        $tenant = $user->tenant;
        if (!$tenant) {
            abort(403);
        }
        $payment = Payment::where('tenant_id', $tenant->id)->with(['lease.unit.property'])->findOrFail($id);
        return view('tenant.payments.show', compact('payment'));
    }

    /**
     * Process payment for tenant.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function processPayment(Request $request, $id)
    {
        $request->validate([
            'transaction_id' => 'required|string|unique:payments,transaction_id',
            'amount' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();
        $tenant = $user->tenant;
        if (!$tenant) {
            abort(403);
        }
        $payment = Payment::where('tenant_id', $tenant->id)->findOrFail($id);

        // Submit payment with transaction ID, status pending
        $payment->update([
            'transaction_id' => $request->transaction_id,
            'status' => 'pending',
        ]);

        return redirect()->route('tenant.payments.show', $payment->id)->with('success', 'Payment submitted successfully. Waiting for landlord approval.');
    }

    /**
     * Accept payment by landlord.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function acceptPayment($id)
    {
        $user = Auth::user();
        $payment = Payment::whereHas('lease.unit.property', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->findOrFail($id);

        $payment->update(['status' => 'paid']);

        return redirect()->route('landlord.payments.index')->with('success', 'Payment accepted successfully.');
    }
}
