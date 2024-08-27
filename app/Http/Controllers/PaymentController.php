<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function create(Request $request)
    {
        $v = Validator::make($request->all(), [
            'user' => 'required|exists:users,id',
            'withdrawal' => 'required|int'
        ]);
        if ($v->fails())
            return response()->json([
                'Validation failed' => [
                    'errors' => $v->errors()
                ]
            ], 422);

        $user = User::find($request->user);
        $totalRevenue = $user->userRevenue();

        $payments = Payment::where('user_id', $user->id)
            ->where('status', 'paid')
            ->get();
        $resultSum = 0;
        foreach ($payments as $payment) {
            $resultSum += $payment->withdrawal;
        }

        if ($totalRevenue - $resultSum < $request->withdrawal)
            return response()->json([
                'message' => 'Your revenue is not enough to withdraw this amount'
            ], 422);

        Payment::create([
            'user_id' => $request->user,
            'withdrawal' => $request->withdrawal,
        ]);
        return response()->json([
            'message' => 'Succcess create payment'
        ], 201);
    }

    public function listPaymentsNew(Request $request)
    {
        $payments = Payment::where('status', null)->get();
        return response()->json(['payments' => PaymentResource::collection($payments)], 200);
    }

    public function listPaymentsClosed(Request $request)
    {
        $payments = Payment::where('status', '!=', null)->get();
        return response()->json(['payments' => PaymentResource::collection($payments)], 200);
    }

    public function paid(Request $request, Payment $payment)
    {
        if ($payment->status !== null)
            return response()->json([
                'message' => 'This billing already closed'
            ], 200);

        if ($payment->balance() < $payment->withdrawal)
            return response()->json([
                'message' => 'The publisher does not have revenue enough to complete the withdraw'
            ], 200);

        $payment->status = 'paid';
        $payment->save();
        return response()->json([
            'message' => 'Success paid',
            'billing' => PaymentResource::make($payment)
        ], 200);
    }

    public function cancel(Request $request, Payment $payment)
    {
        if ($payment->status !== null)
            return response()->json([
                'message' => 'This billing already closed'
            ], 200);

        $payment->status = 'cancel';
        $payment->save();
        return response()->json([
            'message' => 'Success cancel',
            'billing' => PaymentResource::make($payment)
        ], 200);
    }
}
