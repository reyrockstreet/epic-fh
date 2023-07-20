<?php

namespace App\Http\Controllers;

use App\Models\AccountGames;
use App\Models\User;
//use App\Models\InvoicePayment;
use App\Models\Games;
use App\Models\GoldIncome;
//use App\Models\Transaction;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReportGoldController extends Controller
{

    public function index(Request $request)
    {

        if(\Auth::user()->can('manage revenue'))
        {
            $farmer = User::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $farmer->prepend('Select Farmer', '');

            $account = AccountGames::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('account_name', 'id');
            $account->prepend('Select Account', '');

            $games = Games::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $games->prepend('Select Games', '');

            // query untuk menampilkan list added gold by user
            //$query = GoldIncome::where('created_by', '=', \Auth::user()->creatorId());
            $query = GoldIncome::select('*');


            if(count(explode('to', $request->date)) > 1)
            {
                $date_range = explode(' to ', $request->date);
                $query->whereBetween('date', $date_range);
            }
            elseif(!empty($request->date))
            {
                $date_range = [$request->date , $request->date];
                $query->whereBetween('date', $date_range);
            }

            if(!empty($request->user))
            {
                $query->where('created_by', '=', $request->user);
            }
            if(!empty($request->account))
            {
                $query->where('account_id', '=', $request->account);
            }
            
            if(!empty($request->farmer))
            {
                $query->where('created_by', '=', $request->farmer);
            }

            if(!empty($request->games))
            {
                $query->where('games_id', '=', $request->games);
            }

            

            $goldincomes = $query->get();

            return view('reportgold.index', compact('goldincomes', 'farmer', 'account', 'games'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {

        if(\Auth::user()->can('create revenue'))
        {
            /*
            $customers = Customer::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $customers->prepend('--', 0);
            */
            $games = Games::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $accounts   = AccountGames::select('*', \DB::raw("CONCAT(account_name,' ',account_email) AS name"))->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            //$server = "--";

            //return view('reportgold.create', compact('games', 'accounts', 'server'));
            return view('reportgold.create', compact('games', 'accounts'));

        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }


    public function store(Request $request)
    {
        if(\Auth::user()->can('create revenue'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'date' => 'required',
                                   'amount' => 'required',
                                   'account_id' => 'required',
                                   'games_id' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $goldincome                 = new GoldIncome();
            $goldincome->date           = $request->date;
            $goldincome->amount         = $request->amount;
            $goldincome->account_id     = $request->account_id;
            $goldincome->games_id       = $request->games_id;
            //$goldincome->server_id      = $goldincome->server_id;
            $goldincome->description    = $request->description;
            if(!empty($request->proof))
            {
                //storage limit
                $image_size = $request->file('proof')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);

                if($result==1)
                {
                    $fileName = time() . "_" . $request->proof->getClientOriginalName();
                    $goldincome->proof = $fileName;
                    $dir = 'uploads/goldIncome';
                    $url = '';
                    $path = Utility::upload_file($request, 'proof', $fileName, $dir, []);
                    if ($path['flag'] == 0) {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                }
            }


            $goldincome->created_by     = \Auth::user()->farmerId();
            $goldincome->save();

            /* For Transaction
            $games            = Games::where('id', $request->games_id)->first();
            $revenue->payment_id = $revenue->id;
            $revenue->type       = 'Revenue';
            $revenue->category   = $category->name;
            $revenue->user_id    = $revenue->customer_id;
            $revenue->user_type  = 'Customer';
            $revenue->account    = $request->account_id;
            Transaction::addTransaction($revenue);

            $customer         = Customer::where('id', $request->customer_id)->first();
            $payment          = new InvoicePayment();
            $payment->name    = !empty($customer) ? $customer['name'] : '';
            $payment->date    = \Auth::user()->dateFormat($request->date);
            $payment->amount  = \Auth::user()->priceFormat($request->amount);
            $payment->invoice = '';

            if(!empty($customer))
            {
                Utility::userBalance('customer', $customer->id, $revenue->amount, 'credit');
            }

            Utility::bankAccountBalance($request->account_id, $revenue->amount, 'credit');
            */

            //For Notification
            $setting  = Utility::settings(\Auth::user()->creatorId());
            $revenueNotificationArr = [
                'revenue_amount' => \Auth::user()->priceFormat($request->amount),
                'customer_name' => !empty($customer)?$customer->name:'-',
                'user_name' => \Auth::user()->name,
                'revenue_date' => $request->date,
            ];
            //Slack Notification
            if(isset($setting['revenue_notification']) && $setting['revenue_notification'] ==1)
            {
                Utility::send_slack_msg('new_revenue', $revenueNotificationArr);
            }
            //Telegram Notification
            if(isset($setting['telegram_revenue_notification']) && $setting['telegram_revenue_notification'] ==1)
            {
                Utility::send_telegram_msg('new_revenue', $revenueNotificationArr);
            }
            //Twilio Notification
            if(isset($setting['twilio_revenue_notification']) && $setting['twilio_revenue_notification'] ==1)
            {
                Utility::send_twilio_msg(!empty($customer)?$customer->contact:'-','new_revenue', $revenueNotificationArr);
            }


            //webhook
            $module ='New Gold Income';
            $webhook =  Utility::webhookSetting($module);
            if($webhook)
            {
                $parameter = json_encode($revenue);
                $status = Utility::WebhookCall($webhook['url'],$parameter,$webhook['method']);
                if($status == true)
                {
                    return redirect()->route('revenue.index')->with('success', __('Gold successfully added.'));
                }
                else
                {
                    return redirect()->back()->with('error', __('Webhook call failed.'));
                }
            }

            return redirect()->route('reportgold.index')->with('success', __('Gold successfully added.'). ((isset($result) && $result!=1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''));

        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function edit(Revenue $revenue)
    {
        if(\Auth::user()->can('edit revenue'))
        {
            $customers = Customer::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $customers->prepend('--', 0);
            $categories = ProductServiceCategory::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 1)->get()->pluck('name', 'id');
            $accounts   = BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('revenue.edit', compact('customers', 'categories', 'accounts', 'revenue'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

/*
    public function update(Request $request, Revenue $revenue)
    {

        if(\Auth::user()->can('edit revenue'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'date' => 'required',
                                   'amount' => 'required',
                                   'account_id' => 'required',
                                   'category_id' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $customer = Customer::where('id', $request->customer_id)->first();
            if(!empty($customer))
            {
                Utility::userBalance('customer', $customer->id, $revenue->amount, 'debit');
            }

            Utility::bankAccountBalance($revenue->account_id, $revenue->amount, 'debit');

            $revenue->date           = $request->date;
            $revenue->amount         = $request->amount;
            $revenue->account_id     = $request->account_id;
            $revenue->customer_id    = $request->customer_id;
            $revenue->category_id    = $request->category_id;
            $revenue->payment_method = 0;
            $revenue->reference      = $request->reference;
            $revenue->description    = $request->description;
            if(!empty($request->add_receipt))
            {
                //storage limit
                $file_path = '/uploads/revenue/'.$revenue->add_receipt;
                $image_size = $request->file('add_receipt')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);

                if($result==1)
                {
                    Utility::changeStorageLimit(\Auth::user()->creatorId(), $file_path);
                    $path = storage_path('uploads/revenue/' . $revenue->add_receipt);

                    if(file_exists($path))
                    {
                        \File::delete($path);
                    }
                    $fileName = time() . "_" . $request->add_receipt->getClientOriginalName();
                    $revenue->add_receipt = $fileName;
                    $dir        = 'uploads/revenue';
                    $url = '';
                    $path = Utility::upload_file($request,'add_receipt',$fileName,$dir,[]);
                    if($path['flag']==0){
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                }
            }

            $revenue->save();

            $category            = ProductServiceCategory::where('id', $request->category_id)->first();
            $revenue->category   = $category->name;
            $revenue->payment_id = $revenue->id;
            $revenue->type       = 'Revenue';
            $revenue->account    = $request->account_id;
            Transaction::editTransaction($revenue);

            if(!empty($customer))
            {
                Utility::userBalance('customer', $customer->id, $request->amount, 'credit');
            }

            Utility::bankAccountBalance($request->account_id, $request->amount, 'credit');

            return redirect()->route('revenue.index')->with('success', __('Revenue Updated Successfully'). ((isset($result) && $result!=1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''));

        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

*/
    public function destroy(Revenue $revenue)
    {

        if(\Auth::user()->can('delete revenue'))
        {
            if($revenue->created_by == \Auth::user()->creatorId())
            {
                if(!empty($revenue->add_receipt))
                {
                    //storage limit
                    $file_path = '/uploads/revenue/'.$revenue->add_receipt;
                    $result = Utility::changeStorageLimit(\Auth::user()->creatorId(), $file_path);

                }
                $revenue->delete();
                $type = 'Revenue';
                $user = 'Customer';
                Transaction::destroyTransaction($revenue->id, $type, $user);

                if($revenue->customer_id != 0)
                {
                    Utility::userBalance('customer', $revenue->customer_id, $revenue->amount, 'debit');
                }

                Utility::bankAccountBalance($revenue->account_id, $revenue->amount, 'debit');

                return redirect()->route('revenue.index')->with('success', __('Revenue successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
