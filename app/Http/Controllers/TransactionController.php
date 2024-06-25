<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('user')->where('user_id', Auth::id())->get();

        return view('transactions.index', compact('transactions'));
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function showDeposits()
    {
        $deposits = Transaction::where('transaction_type', 'deposit')->where('user_id', Auth::id())->with('user')->get();

        return view('transactions.deposits', compact('deposits'));
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        $amount = $request->amount;

        $user->balance += $amount;
        $user->save();

        $user->transactions()->create([
            'transaction_type' => 'deposit',
            'amount' => $amount,
        ]);

        return redirect()->back()->with('success', 'Deposit successful!');
    }

    public function showWithdrawals()
    {
        $withdrawals = Transaction::where('transaction_type', 'withdrawal')->where('user_id', Auth::id())->with('user')->get();
        return view('transactions.withdrawals', compact('withdrawals'));
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        $amount = $request->amount;

        $fee = $this->calculateWithdrawalFee($user, $amount);

        if ($user->balance < ($amount + $fee)) {
            return redirect()->back()->with('success', 'Insufficient balance');
        }

        $user->balance -= ($amount + $fee);
        $user->save();

        $user->transactions()->create([
            'transaction_type' => 'withdrawal',
            'amount' => $amount,
            'fee' => $fee,
        ]);

        return redirect()->back()->with('success', 'Withdrawal successful!');
    }

    private function calculateWithdrawalFee($user, $amount)
    {
        $fee = 0;
        $date = Carbon::now();

        if ($user->account_type == 'Individual') {
            if ($date->isFriday()) {
                return $fee;
            }

            if ($amount <= 1000) {
                return $fee;
            }

            $freeMonthlyWithdrawal = 5000;
            $monthlyWithdrawals = $user->transactions()
                ->where('transaction_type', 'withdrawal')
                ->whereMonth('created_at', $date->month)
                ->sum('amount');

            if ($monthlyWithdrawals < $freeMonthlyWithdrawal) {
                if ($monthlyWithdrawals + $amount <= $freeMonthlyWithdrawal) {
                    return $fee;
                } else {
                    $amountToCharge = ($monthlyWithdrawals + $amount) - $freeMonthlyWithdrawal;
                    return $amountToCharge * 0.015 / 100;
                }
            }

            $amountToCharge = $amount - 1000;
            return $amountToCharge * 0.015 / 100;
        } elseif ($user->account_type == 'Business') {
            $totalWithdrawals = $user->transactions()
                ->where('transaction_type', 'withdrawal')
                ->sum('amount');

            if ($totalWithdrawals > 50000) {
                return $amount * 0.015 / 100;
            } else {
                return $amount * 0.025 / 100;
            }
        }

        return $fee;
    }
}
