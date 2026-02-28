<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Lead;
use App\Models\User;
use Carbon\Carbon;

class LeadController extends Controller
{
    /**
     * List leads.
     */
    public function index(Request $request)
    {
        $today = Carbon::today()->toDateString();

        $dateFrom = $request->input('date_from', $today);
        $dateTo = $request->input('date_to', $today);

        $leads = Lead::with('assignedTo')
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->latest()
            ->get();

        $users = User::all();

        return view('leads.index', compact('leads', 'users', 'dateFrom', 'dateTo'));
    }

    /**
     * Show form to create a lead.
     */
    public function create()
    {
        return view('leads.create');
    }

    /**
     * Store a newly created lead.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sender_email' => 'required|email',
            'sender_name' => 'nullable|string',
            'subject' => 'nullable|string',
            'body' => 'required|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        Lead::create([
            'sender_email' => $validated['sender_email'],
            'sender_name' => $validated['sender_name'],
            'subject' => $validated['subject'],
            'body' => $validated['body'],
            'received_at' => now(),
            'status' => !empty($validated['assigned_to']) ? 'assigned' : 'new',
            'assigned_to' => $validated['assigned_to'] ?? null,
            'assigned_at' => !empty($validated['assigned_to']) ? now() : null,
        ]);

        return redirect()->route('leads.index')->with('success', 'تم إضافة الرسالة بنجاح.');
    }

    /**
     * Assign a lead to a sales user.
     */
    public function assign(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $lead->update([
            'assigned_to' => $validated['user_id'],
            'status' => 'assigned',
            'assigned_at' => now(),
        ]);

        return redirect()->route('leads.index')->with('success', 'Lead assigned successfully.');
    }
}
