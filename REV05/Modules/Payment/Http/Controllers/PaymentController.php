<?php

namespace Modules\Payment\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Payment\Entities\Payment;
use Modules\SmsHandler\Http\Controllers\SmsHandlerController;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $Payment = Payment::where('id', 'like', "%{$request->search}%")->orderBy('id', 'desc')->paginate(20);

        $Data = [
            'Payment'
        ];

        return view('payment::index', compact($Data));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('payment::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $Payment = Payment::with('Freelancer')->find($id);

        $Data = [
            'Payment'
        ];
        return view('payment::edit', compact($Data));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $Payment = Payment::find($id);

        if ($Payment->update(['status' => $request->status])) {
            if ($request->status == 'paid') {
                $wpCategory = $Payment->category;
                $workPackage = $wpCategory ? $wpCategory->workPackage : null;
                $WorkPackageCategory = $Payment->category;

                if ($workPackage) {
                    $workPackage->load('wpCategory.payments');

                    $allCategoriesCompleted = $workPackage->wpCategory->every(function ($category) {
                        return $category->status === 'completed';
                    });

                    $allPaymentsPaid = $workPackage->wpCategory->every(function ($category) {
                        return $category->payments->every(function ($payment) {
                            return $payment->status === 'paid';
                        });
                    });

                    if ($allCategoriesCompleted && $allPaymentsPaid) {
                        $workPackage->update(['status' => 'completed']);
                    }
                }

                SmsHandlerController::Send([$Payment->Users->phone], " تبریک، صورتحساب مرحله «{$WorkPackageCategory->title}» از بسته کاری «{$workPackage->title}» پرداخت شد.");

            }

            return back()->with('notification', ['class' => 'success', 'message' => 'وضعیت با موفقیت ثبت شد.']);
        } else {
            return redirect()->back()->with('notification', [
                'status' => 'danger',
                'message' => 'انجام نشد',
            ]);
        }


    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
