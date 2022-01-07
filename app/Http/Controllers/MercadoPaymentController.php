<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use LivePixel\MercadoPago\MP;

class MercadoPaymentController extends Controller
{
    public $secret_key;
    public $app_id;
    public $is_enabled;
    public $currancy;

    public function setPaymentDetail()
    {
        $user = Auth::user();
        if($user->getGuard() == 'client')
        {
            $payment_setting = Utility::getPaymentSetting($user->currentWorkspace->id);
            $this->currancy  = (isset($user->currentWorkspace->currency_code)) ? $user->currentWorkspace->currency_code : 'USD';
        }
        else
        {
            $payment_setting = Utility::getAdminPaymentSetting();
            $this->currancy  = !empty(env('CURRENCY')) ? env('CURRENCY') : 'USD';
        }

        $this->secret_key = isset($payment_setting['mercado_secret_key']) ? $payment_setting['mercado_secret_key'] : '';
        $this->app_id     = isset($payment_setting['mercado_app_id']) ? $payment_setting['mercado_app_id'] : '';
        $this->is_enabled = isset($payment_setting['is_mercado_enabled']) ? $payment_setting['is_mercado_enabled'] : 'off';
    }

    public function invoicePayWithMercado(Request $request, $slug, $invoice_id)
    {
        $this->setPaymentDetail();

        $validatorArray = [
            'amount' => 'required',
        ];
        $validator      = Validator::make(
            $request->all(), $validatorArray
        );

        if($validator->fails())
        {
            return redirect()->back()->with('error', __($validator->errors()->first()));
        }

        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $invoice          = Invoice::find($request->invoice_id);

        if($invoice->getDueAmount() < $request->amount)
        {
            return redirect()->route(
                'client.invoices.show', [
                                          $currentWorkspace->slug,
                                          $invoice_id,
                                      ]
            )->with('error', __('Invalid amount.'));
        }

        $preference_data = array(
            "items" => array(
                array(
                    "title" => "Invoice : " . $request->invoice_id,
                    "quantity" => 1,
                    "currency_id" => $this->currancy,
                    "unit_price" => (float)$request->amount,
                ),
            ),
        );

        try
        {
            $mp         = new MP($this->app_id, $this->secret_key);
            $preference = $mp->create_preference($preference_data);

            return redirect($preference['response']['init_point']);
        }
        catch(Exception $e)
        {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function getInvoicePaymentStatus($invoice_id, Request $request)
    {
        $this->setPaymentDetail();

        Log::info(json_encode($request->all()));
    }
}
