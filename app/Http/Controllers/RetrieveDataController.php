<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\DepositFund;
use App\Models\Resident;
use App\Models\ScannedCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RetrieveDataController extends Controller
{
    // Dashboard View
    public function Dashboard()
    {
        return view('dashboard');
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
        $no = 1;
        return view('generatereport', ['scannedCard' => $scanned, 'no' => $no]);
    }

    //deposit fund
    public function ViewDepositFund()
    {
        $deposit = DepositFund::orderByDesc('id')->get();
        $no = 1;
        $total_fund = DepositFund::get()->sum('amount');
        return view('depositfund', ['deposits' => $deposit, 'no' => $no, 'total' => $total_fund]);
    }
}
