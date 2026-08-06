<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PaymentMethodController extends Controller
{
    /**
     * Display listing of payment methods.
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::latest()->paginate(15);
        return view('admin.payment_methods.index', compact('paymentMethods'));
    }

    /**
     * Store a newly created payment method.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:MFS,Bank',
            'number' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:4096',
            'instruction' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'pm_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/payment_methods'), $filename);
            $imagePath = 'uploads/payment_methods/' . $filename;
        }

        PaymentMethod::create([
            'name' => $request->name,
            'type' => $request->type,
            'number' => $request->number,
            'image' => $imagePath,
            'instruction' => $request->instruction,
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : true,
        ]);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Payment method added successfully!');
    }

    /**
     * Update specified payment method.
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:MFS,Bank',
            'number' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:4096',
            'instruction' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($paymentMethod->image && File::exists(public_path($paymentMethod->image))) {
                File::delete(public_path($paymentMethod->image));
            }

            $file = $request->file('image');
            $filename = 'pm_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/payment_methods'), $filename);
            $paymentMethod->image = 'uploads/payment_methods/' . $filename;
        }

        $paymentMethod->name = $request->name;
        $paymentMethod->type = $request->type;
        $paymentMethod->number = $request->number;
        $paymentMethod->instruction = $request->instruction;
        $paymentMethod->is_active = $request->has('is_active') ? (bool) $request->is_active : false;
        $paymentMethod->save();

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Payment method updated successfully!');
    }

    /**
     * Toggle payment method active status.
     */
    public function toggleStatus(PaymentMethod $paymentMethod)
    {
        $paymentMethod->is_active = !$paymentMethod->is_active;
        $paymentMethod->save();

        return redirect()->back()
            ->with('success', 'Payment method status updated!');
    }

    /**
     * Remove specified payment method.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->image && File::exists(public_path($paymentMethod->image))) {
            File::delete(public_path($paymentMethod->image));
        }

        $paymentMethod->delete();

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Payment method deleted successfully!');
    }
}
