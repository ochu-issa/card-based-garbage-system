<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\CardActivities;
use App\Models\DepositFund;
use App\Models\Resident;
use App\Models\ScannedCard;
use Illuminate\Http\Request;
use Illuminate\Log\LogManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Monolog\Handler\StreamHandler as HandlerStreamHandler;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;


class RetrieveDataController extends Controller
{
    // Dashboard View
    public function Dashboard()
    {
        $totalResident = Resident::get()->count(); //Count all Resident
        $totalCard = Card::get()->count(); //count all cards
        $total_fund = DepositFund::get()->sum('amount'); //sum
        $card = Card::all();
        $activeCard = 0;
        foreach ($card as $cards) {
            //dd($cards->id);
            $activeCard = Resident::where('card_id', $cards->id)->get()->count();
        }
        return view('dashboard', [
            'totalResident' => $totalResident,
            'totalCard' => $totalCard,
            'totalAmount' => $total_fund,
            'activeCard' => $activeCard,
        ]);
    }

    // Manage card View
    public function ManageCard()
    {
        // $cards = Card::orderByDesc('id')->get();
        $cards = Card::leftJoin('residents', 'cards.id', '=', 'residents.card_id')
            ->select('cards.*', DB::raw('IF(residents.card_id IS NULL, "unsigned", "assigned") as card_status'))
            ->orderByDesc('id')->get();
        $no = 1;

        return view('managecard', ['cards' => $cards, 'no' => $no]);
    }

    //Manage user view
    public function ManageUser()
    {
        $cards = Card::leftJoin('residents', 'cards.id', '=', 'residents.card_id')
            ->select('cards.*', DB::raw('IF(residents.card_id IS NULL, "unsigned", "assigned") as card_status'))
            ->orderByDesc('id')->get();
        $no = 1;
        $residents = Resident::orderByDesc('id')->get();
        return view('manageuser', ['cards' => $cards, 'no' => $no, 'residents' => $residents]);
    }

    //Generate Report
    public function GenerateReport()
    {
        $scanned = ScannedCard::orderByDesc('id')->get();
        return view('generatereport', ['scannedCard' => $scanned]);
    }

    //deposit fund
    public function ViewDepositFund()
    {
        $deposit = DepositFund::orderByDesc('id')->get();
        $no = 1;
        $total_fund = DepositFund::get()->sum('amount');
        return view('depositfund', ['deposits' => $deposit, 'no' => $no, 'total' => $total_fund]);
    }

    //log activites
    public function viewLogs()
    {
        $logs = CardActivities::latest()->get();
        return view('logActivities', ['logs' => $logs]);
    }

    public function showFile()
    {
        return view('sample');
    }

    public function consumeApi(Request $request)
    {
        $card_number = $request->card_number;
        $api = app('App\Http\Controllers\SaveDataController')->ScanCard($card_number);
        if($api->getStatusCode() === 200)
        {
            return back()->with('success', 'api success');

        }else{
            return back()->with('error', 'api error');
        }
    }
}
