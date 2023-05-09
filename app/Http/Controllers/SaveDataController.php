<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\DepositFund;
use App\Models\Resident;
use App\Models\ScannedCard;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\View;


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

        // Loop through the array and register each card
        for ($i = 1; $i < count($cards[0]); $i++) {
            Card::create([
                'card_number' => $cards[0][$i][1], // Assumes that "card number" is the second column in the CSV file
                'balance' => 5000,
            ]);
        }

        return redirect()->back()->with('success', 'Card registered successfully!');
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
        if ($check_card) {

            if ($check_card->status == 0) { // check if card is blocked
                return redirect()->back()->with('error', 'This card is blocked, fund can not be complete!');
            }

            $add_fund = $check_card->balance + $request->amount;
            $check_card->update(['balance' => $add_fund]);
            DepositFund::create([
                'card_number' => $request->card_number,
                'amount' => $request->amount
            ]);
            return redirect()->back()->with('success', 'Card deposited successfully!');
        } else {
            return redirect()->back()->with('error', 'Card number is not registered!');
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
            'f_name' => 'required|string',
            'l_name' => 'required|string',
            'gender' => 'required',
            'address' => 'required',
            'phone_number' => 'required|unique:residents',
        ]);

        $this->validationRule($validator);

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

    //scaning card
    public function ScanCard($card_number)
    {
        $card = Card::where('card_number', $card_number)->first();
        if ($card) { //check the card is valid

            if ($card->status == 0) { // check if card is blocked
                return response()->json(['message' => 'Card is blocked'], 400);
            }

            $resident = Resident::where('card_id', $card->id)->first();
            if ($resident) { // check if card is assigned to resident

                if ($card->balance >= 1000) { // check if card has enough balance
                    // deduct balance from card
                    $card->balance -= 1000;
                    $card->save();

                    // record scanned card
                    $today = Carbon::today();
                    $scannedCard = ScannedCard::where('card_number', $card_number)
                        ->where('created_at', '>=', $today)
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

                    return response()->json(['message' => 'Card scanned successfully']);
                    // return 'Success';
                } else {
                    return response()->json(['message' => 'Card balance is not sufficient'], 400);
                }
            } else {
                return response()->json(['message' => 'Card is not assigned to any resident'], 404); //return to node mcu
            }
        } else {
            return response()->json(['message' => 'Invalid card'], 404); //return to node mcu
        }
    }

    //generate for scanned card report function
    public function generateReport(Request $request)
    {
        // Retrieve the selected time range
        $when = $request->when;

        // Retrieve the scanned cards based on the selected time range
        $scannedCards = ScannedCard::whereBetween('created_at', $this->getDateRange($when))->get();

        // Generate the HTML for the report using a Blade view
        $html = View::make('report', ['scannedCards' => $scannedCards, 'DateRange' => $when])->render();

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
    private function getDateRange($when)
    {
        switch ($when) {
            case 'This Week':
                return [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];

            case 'This Month':
                return [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];

            case 'Last Month':
                return [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()];

            case 'Last Two Month':
                return [Carbon::now()->subMonths(2)->startOfMonth(), Carbon::now()->endOfMonth()];

            case 'Last Three Month':
                return [Carbon::now()->subMonths(3)->startOfMonth(), Carbon::now()->endOfMonth()];

            default:
                return [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
        }
    }

    //generate report of payment
    public function paymentReport(Request $request)
    {
        // Retrieve the selected time range
        $when = $request->when;

        // Retrieve the scanned cards based on the selected time range
        $depositFund = DepositFund::whereBetween('created_at', $this->getDateRange($when))->get();
        $total = $depositFund->sum('amount');
        // Generate the HTML for the report using a Blade view
        $html = View::make('payment-report', ['depositFunds' => $depositFund, 'total' => $total, 'DateRange' => $when])->render();

        // Create a new instance of the DOMPDF class
        $dompdf = new Dompdf();

        // Load the HTML into DOMPDF
        $dompdf->loadHtml($html);

        // Set the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to the browser
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

    //block card function
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
}
