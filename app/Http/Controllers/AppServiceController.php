<?php

namespace App\Http\Controllers;

use App\Http\Requests\CollectionRequest;
use App\Models\Card;
use App\Models\DepositFund;
use App\Models\Resident;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class AppServiceController extends Controller
{
    use HttpResponses;
    //check if number is exist?
    public function linkCard(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'card_number' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'error' => 'The card Number field is required',
            ], 400);
        }

        $card_number = $request->card_number;
        $card = Card::where('card_number', $card_number)->first();

        if ($card) {
            return response()->json([
                'card' => $card,
            ], 200);
        } else {
            return response()->json([
                'error' => 'Card number does not exist in our database',
            ], 404);
        }
    }

    //Request for service
    public function collectionRequest(CollectionRequest $request)
    {
        $request->validated($request->all());

        $total_payment = $request->total_package * 1000; //each package cost 1000tsh
        $card = Card::where('card_number', $request->card_number)->first(); //check card


        if ($card) {
            if ($card->status == 1) {
                if ($total_payment <= $card->balance) {
                    $check_resident = Resident::where('card_id', $card->id)->first(); //check resident
                    if ($check_resident) {
                        $new_balane = $card->balance - $total_payment; //new balance
                        $card->update(['balance' => $new_balane]);
                        //add payment record
                        $payment_record = DepositFund::create([
                            'card_number' => $request->card_number,
                            'amount' => $total_payment,
                        ]);
                        //success 200 OK
                        return $this->success([
                            'card' => $card,
                            'resident' => $check_resident,
                            'payment' => $payment_record,
                        ]);
                    } else {
                        return $this->error('', 'The card is not assigned to any resident.', 401);
                    }
                } else {
                    return $this->error('', 'You dont have enough balance to make request.', 401);
                }
            } else {
                return $this->error('', 'This card is blocked by Administrator.', 401);
            }
        } else {
            return $this->error('', 'Card does not exist.', 401);
        }
    }
}
