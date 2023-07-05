<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\CardActivities;
use App\Models\DepositFund;
use App\Models\Resident;
use App\Models\ScannedCard;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class SaveDataController extends Controller
{
    //validation rule
    public function validationRule($validator)
    {
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Error: Something went wrong!');
        }
    }

    //register card using number
    public function RegisterCard(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'card_number' => 'required'
        ]);

        $this->validationRule($validator);

        $check = Card::where('card_number', $request->card_number)->first();
        if ($check) {
            return back()->with('error', 'Card already exist!');
        }

        Card::create([
            'card_number' => $request->card_number,
            'balance' => 5000,
        ]);

        return redirect()->back()->with('success', 'Card registered successfully!');
    }

    //register card using csv
    public function UploadCsv(Request $request)
    {
        $file = $request->file('excel_file');

        // Validate the file
        $validator = Validator::make(
            ['file' => $file],
            ['file' => 'required|mimes:csv,xlsx,xls']
        );

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        // Load the CSV file data into an array
        $cards = Excel::toArray([], $file);

        // Track the existing card numbers
        $existingCardNumbers = Card::pluck('card_number')->toArray();

        // Loop through the array and register each card
        $registeredCards = 0;
        for ($i = 1; $i < count($cards[0]); $i++) {
            $cardNumber = $cards[0][$i][1]; // Assumes that "card number" is the second column in the CSV file

            // Check if the card number already exists
            if (in_array($cardNumber, $existingCardNumbers)) {
                continue; // Skip the current card if it already exists
            }

            Card::create([
                'card_number' => $cardNumber,
                'balance' => 5000,
            ]);

            $registeredCards++;
        }

        return redirect()->back()->with('success', $registeredCards . ' card(s) registered successfully!');
    }


    //deposit fund for the card
    public function DepositFund(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'card_number' => 'required',
            'amount' => 'required'
        ]);

        $this->validationRule($validator);

        $check_card = Card::where('card_number', $request->card_number)->first();
        $cardcheck = Card::where('card_number', $request->card_number)->with('resident')->first();
        if ($check_card) {
            if (!$cardcheck->resident) {
                return back()->with('error', 'Sorry! Transaction can only be done for an assigned card!');
            }
            if ($check_card->status == 0) { // check if card is blocked
                return back()->with('error', 'This card is blocked, fund cannot be completed!');
            }


            $add_fund = $check_card->balance + $request->amount;
            $check_card->update(['balance' => $add_fund]);
            DepositFund::create([
                'card_number' => $request->card_number,
                'amount' => $request->amount
            ]);

            //sending message
            $card_number = $request->card_number;
            $amount = $request->amount;
            $data = $this->getResidentInfo($card_number);
            $fullName = $data['full_name'];
            $phoneNumber = $data['phone_number'];
            $this->sendSmsDeposit($fullName, $request->card_number, $phoneNumber, $amount);
            //...

            return back()->with('success', 'Card deposited successfully!');
        } else {
            return back()->with('error', 'Card number is not registered!');
        }
    }

    //download receipt
    public function DownloadReceipt($id)
    {
        $deposit = DepositFund::find($id);

        $pdf = PDF::loadView('receipt', ['deposit' => $deposit]);
        $filename = 'receipt-' . $id . '.pdf';

        return $pdf->download($filename);
    }

    //register resident
    public function RegisterResident(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'f_name' => 'required|regex:/^[A-Za-z\s\-]+$/u',
            'l_name' => 'required|regex:/^[A-Za-z\s\-]+$/u',
            'gender' => 'required|in:Male,Female',
            'address' => 'required|string',
            'phone_number' => ['required', 'string', 'unique:residents', 'regex:/^255[0-9]{9}$/'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $card_id = Card::where('card_number', $request->cardnumber)->first()->id;
        //dd($card_id);
        Resident::create([
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'gender' => $request->gender,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'card_id' => $card_id,
        ]);

        return redirect()->back()->with('success', 'Resident registered successfully!');
    }

    //scaning card -API FUNCTION
    public function ScanCard($card_number)
    {
        $card = Card::where('card_number', $card_number)->first();
        if ($card) { // Check if the card is valid

            if ($card->status == 0) { // Check if the card is blocked
                $logs = Log::channel('cardActivities')->info('Card scanned: Blocked card - ' . $card_number);
                $logs = 'Card scanned: Blocked card - ' . $card_number;
                CardActivities::create(['logname' => $logs]);
                return response('blocked');
            }

            $resident = Resident::where('card_id', $card->id)->first();
            if ($resident) { // Check if the card is assigned to a resident

                if ($card->balance >= 1000) { // Check if the card has enough balance
                    // Deduct balance from the card
                    $card->balance -= 1000;
                    $card->save();

                    // Record scanned card
                    $today = Carbon::today();
                    $scannedCard = ScannedCard::where('card_number', $card_number)
                        ->whereMonth('created_at', $today->month)
                        ->first();
                    if ($scannedCard) {
                        $scannedCard->increment('scanned_times');
                    } else {
                        $scannedCard = new ScannedCard();
                        $scannedCard->card_number = $card_number;
                        $scannedCard->resident_full_name = $resident->f_name . ' ' . $resident->l_name;
                        $scannedCard->address = $resident->address;
                        $scannedCard->phone_number = $resident->phone_number;
                        $scannedCard->scanned_times = 1;
                        $scannedCard->save();
                    }

                    Log::channel('cardActivities')->info('Card scanned: Successful scan - ' . $card_number);
                    $logs = 'Card scanned: Successful scan - ' . $card_number;
                    CardActivities::create(['logname' => $logs]);

                    //sending message
                    $data = $this->getResidentInfo($card_number);
                    $fullName = $data['full_name'];
                    $phoneNumber = $data['phone_number'];
                    $this->sendSmsCard($fullName, $card_number, $phoneNumber);
                    //...

                    return response('success');
                } else {
                    Log::channel('cardActivities')->info('Card scanned: Insufficient balance - ' . $card_number);
                    $logs = 'Card scanned: Insufficient balance - ' . $card_number;
                    CardActivities::create(['logname' => $logs]);
                    return response('insufficient');
                }
            } else {
                Log::channel('cardActivities')->info('Card scanned: Unassigned card - ' . $card_number);
                $logs = 'Card scanned: Unassigned card - ' . $card_number;
                CardActivities::create(['logname' => $logs]);
                return response('unsigned');
            }
        } else {
            Log::channel('cardActivities')->info('Card scanned: Invalid card - ' . $card_number);
            $logs = 'Card scanned: Invalid card - ' . $card_number;
            // dd($logs);
            CardActivities::create(['logname' => $logs]);
            return response('invalid');
        }
    }

    //function to get users data
    public function getResidentInfo($card_number)
    {
        $card = Card::where('card_number', $card_number)->with('resident')->first();
        return  [
            'phone_number' => $card->resident->phone_number,
            'full_name' => $card->resident->f_name . ' ' . $card->resident->l_name,
        ];
    }

    //function to send SMS
    public function sendSmsCard($fullName, $card_number, $phoneNumber)
    {
        $formattedDate = Carbon::now()->format('d/m/Y h:iA');
        $api_key = '79093d2c66b12e48';
        $secret_key = 'ZmNiZGE5YzhkYWRhZjA2OTgyMWUyMzg3ZTk5MGNjZmE2ZTIzZTUxYzg0NmIxNGY4YjVkOWVjNzQ5ZTExY2ZmMg==';

        $postData = array(
            'source_addr' => 'INFO',
            'encoding' => 0,
            'schedule_time' => '',
            'message' => 'Card# ' . $card_number . ' ' . $fullName . ' imetumika ' . $formattedDate . '. Malipo ya Tsh 1,000 yamefanyika kikamilifu. Piga namba +255652762026 iwapo hutambui muamala huu.',
            'recipients' => [array('recipient_id' => '1', 'dest_addr' => $phoneNumber)]
        );

        $Url = 'https://apisms.beem.africa/v1/send';

        $ch = curl_init($Url);
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . base64_encode("$api_key:$secret_key"),
                'Content-Type: application/json'
            ),
            CURLOPT_POSTFIELDS => json_encode($postData)
        ));

        $response = curl_exec($ch);

        if ($response === FALSE) {
            $error = curl_error($ch);
            return response("Error: " . $error);
        } else {
            $decodedResponse = json_decode($response, true);

            if ($decodedResponse && isset($decodedResponse['status']) && $decodedResponse['status'] == '01') {
                return response("Message sent successfully.");
            } else {
                return response("Error sending message: " . $response);
            }
        }
    }

    //function to send SMS
    public function sendSmsDeposit($fullName, $card_number, $phoneNumber, $amount)
    {
        $formattedDate = Carbon::now()->format('d/m/Y h:iA');
        $api_key = '79093d2c66b12e48';
        $secret_key = 'ZmNiZGE5YzhkYWRhZjA2OTgyMWUyMzg3ZTk5MGNjZmE2ZTIzZTUxYzg0NmIxNGY4YjVkOWVjNzQ5ZTExY2ZmMg==';

        $postData = array(
            'source_addr' => 'INFO',
            'encoding' => 0,
            'schedule_time' => '',
            'message' => 'Card# ' . $card_number . ' ' . $fullName . ' kiasi cha ' . $amount . ' kimewekwa ' . $formattedDate . ' Piga namba +255652762026 iwapo hutambui muamala huu.',
            'recipients' => [array('recipient_id' => '1', 'dest_addr' => $phoneNumber)]
        );

        $Url = 'https://apisms.beem.africa/v1/send';

        $ch = curl_init($Url);
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . base64_encode("$api_key:$secret_key"),
                'Content-Type: application/json'
            ),
            CURLOPT_POSTFIELDS => json_encode($postData)
        ));

        $response = curl_exec($ch);

        if ($response === FALSE) {
            $error = curl_error($ch);
            return response("Error: " . $error);
        } else {
            $decodedResponse = json_decode($response, true);

            if ($decodedResponse && isset($decodedResponse['status']) && $decodedResponse['status'] == '01') {
                return response("Message sent successfully.");
            } else {
                return response("Error sending message: " . $response);
            }
        }
    }


    //generate for scanned card report function
    public function generateReport(Request $request)
    {
        // Retrieve the selected time range
        $when = $request->when;
        $filter = $request->filter;

        if ($filter == 'All') {
            // Retrieve the scanned cards based on the selected time range
            $scannedCards = ScannedCard::whereBetween('created_at', $this->getDateRange($when))->get();
        } elseif ($filter == 'Good Collector') {
            // Retrieve the scanned cards based on the selected time range and scanned_times greater than 3
            $scannedCards = ScannedCard::where('scanned_times', '>=', 3)->whereBetween('created_at', $this->getDateRange($when))->get();
        } elseif ($filter == 'Bad Collector') {
            // Retrieve the scanned cards based on the selected time range and scanned_times less than 3
            $scannedCards = ScannedCard::where('scanned_times', '<=', 2)->whereBetween('created_at', $this->getDateRange($when))->get();
        }

        // Generate the HTML for the report using a Blade view
        $html = View::make('report', ['scannedCards' => $scannedCards, 'DateRange' => $when, 'filter' => $filter])->render();

        // Create a new instance of the DOMPDF class
        $dompdf = new Dompdf();

        // Load the HTML into DOMPDF
        $dompdf->loadHtml($html);

        // Set the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to the browser
        return $dompdf->stream('report.pdf');
    }


    //get range function
    function getDateRange($when)
    {
        switch ($when) {
            case 'This Week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'This Month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'Last Month':
                $startDate = Carbon::now()->subMonth()->startOfMonth();
                $endDate = Carbon::now()->subMonth()->endOfMonth();
                break;
            case 'Last Two Months':
                $startDate = Carbon::now()->subMonths(2)->startOfMonth();
                $endDate = Carbon::now()->subMonths(1)->endOfMonth();
                break;
            case 'Last Three Months':
                $startDate = Carbon::now()->subMonths(3)->startOfMonth();
                $endDate = Carbon::now()->subMonths(1)->endOfMonth();
                break;
            default:
                // Handle the default case or throw an exception
                throw new InvalidArgumentException('Invalid "when" value provided');
        }

        return [$startDate, $endDate];
    }
    //generate report of payment
    public function paymentReport(Request $request)
    {
        // Retrieve the selected time range
        $when = $request->when;
        $depositFund = DepositFund::whereBetween('created_at', $this->getDateRange($when))->get();
        $total = $depositFund->sum('amount');
        $html = View::make('payment-report', ['depositFunds' => $depositFund, 'total' => $total, 'DateRange' => $when])->render();
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return $dompdf->stream('payment-report.pdf');
    }

    //block card function
    public function blockCard(Request $request)
    {
        // dd('test');
        $card_id = $request->card_id;
        Card::where('id', $card_id)
            ->first()
            ->update([
                'status' => 0,
            ]);
        return redirect()->back()->with('success', 'Card blocked!');
    }

    //un-block card function
    public function unBlockCard(Request $request)
    {
        // dd('test');
        $card_id = $request->card_id;
        Card::where('id', $card_id)
            ->first()
            ->update([
                'status' => 1,
            ]);
        return redirect()->back()->with('success', 'Card is now active!');
    }

    //delete card
    public function deleteCard(Request $request)
    {
        $card_id = $request->card_id;
        Card::find($card_id)->delete();
        return redirect()->back()->with('success', 'Card and associated resident deleted successfully');
    }

    //delete user
    public function deleteResident(Request $request)
    {
        $user_id = $request->user_id;
        Resident::find($user_id)->delete();
        return redirect()->back()->with('success', 'Resident deleted successfully');
    }

    //call Seedevent
    public function seedEvent()
    {
        Artisan::call('migrate:fresh --seed');
        return response()->json('Succcess');
    }

    //call Optimize Event
    public function optimizeEvent()
    {
        Artisan::call('optimize:clear');
        return response()->json('Succcess');
    }

    //call clear Event
    public function configEvent()
    {
        Artisan::call('config:clear');
        return response()->json('Succcess');
    }

    //call clear Event
    public function cacheEvent()
    {
        Artisan::call('cache:clear');
        return response()->json('Succcess');
    }
}
