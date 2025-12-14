<?php

namespace Modules\Payment\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Modules\Payment\Entities\Payment;

class PaymentAPIController extends Controller
{
    /*
    * Get Payment List
    * Route: /api/payment
    * GET
    * */
    public function PaymentList()
    {
        $Payment = Payment::where('users_id', auth('sanctum')->user()->id)->where('status', '!=', 'paid')->orderBy('created_at', 'desc')->with('workPackage')->with('category')->get();
        return response()->json(['getData' => $Payment]);
    }

    /*
    * Get Payment List
    * Route: /api/paid
    * GET
    * */
    public function Paid()
    {
        $Payment = Payment::where('users_id', auth('sanctum')->user()->id)->where('status', 'paid')->orderBy('created_at', 'desc')->with('workPackage')->with('category')->get();
        return response()->json(['getData' => $Payment]);
    }

    /*
    * Get Checkout
    * Route: /api/payment/{id}
    * GET
    * */
    public function Checkout($id)
    {
        $Payment = Payment::with('Freelancer', 'Users')->find($id);
        /* Check Delay */
        if ($Payment->category->completed_at) {
            $activationDate = Carbon::createFromFormat('Y-m-d H:i:s', $Payment->category->activation_at);
            $dueDate = $activationDate->copy()->addDays($Payment->category->due_date);
            $completedDate = Carbon::createFromFormat('Y-m-d H:i:s', $Payment->category->completed_at);

            if ($completedDate->gt($dueDate)) {
                $Payment['delay'] = $completedDate->diffInDays($dueDate) . ' روز تأخیر';
            } else {
                $Payment['delay'] = 'بدون تأخیر';
            }
        } else {
            $Payment['delay'] = 'محاسبه نشده است';
        }

        if (auth('sanctum')->user()->id === $Payment->users_id) {
            return response()->json(['getData' => $Payment]);
        } else {
            return response()->json(['status' => 402]);
        }
    }
}
