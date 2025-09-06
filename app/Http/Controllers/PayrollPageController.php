<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payroll;
use App\Models\User;

class PayrollPageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Payroll::with('user')->orderByDesc('year')->orderByDesc('month');
        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        } else if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('month')) {
            $query->where('month', (int)$request->month);
        }
        if ($request->filled('year')) {
            $query->where('year', (int)$request->year);
        }

        $items = $query->paginate(20)->appends($request->query());
        $users = $user->role === 'admin' ? User::orderBy('name')->get(['id','name']) : collect();

        return view('payroll.index', [
            'items' => $items,
            'users' => $users,
            'pageTitle' => __('app.payroll')
        ]);
    }
}


