<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TenantLead;
use App\Models\Unit;
use App\Models\Property;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use App\Notifications\NewTenantLeadNotification;

class TenantLeadController extends Controller
{
    /**
     * Display a listing of tenant leads
     */
    public function index(Request $request)
    {
        $query = TenantLead::with(['unit', 'property', 'assignedAgent'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%')
                  ->orWhere('phone', 'like', '%' . $searchTerm . '%')
                  ->orWhere('message', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $leads = $query->paginate(15);

        // Get filter options
        $agents = User::whereHas('roles', function($q) {
            $q->where('name', 'Agent');
        })->get();

        $stats = [
            'total' => TenantLead::count(),
            'new' => TenantLead::where('status', 'new')->count(),
            'contacted' => TenantLead::where('status', 'contacted')->count(),
            'qualified' => TenantLead::where('status', 'qualified')->count(),
            'converted' => TenantLead::where('status', 'converted')->count(),
            'overdue' => TenantLead::where('follow_up_date', '<', now())->whereIn('status', ['new', 'contacted'])->count()
        ];

        return view('admin.tenant-leads.index', compact('leads', 'agents', 'stats'));
    }

    /**
     * Show the form for creating a new lead
     */
    public function create()
    {
        $units = Unit::availableForListing()->with('property')->get();
        $properties = Property::approved()->get();
        $agents = User::whereHas('roles', function($q) {
            $q->where('name', 'Agent');
        })->get();

        return view('admin.tenant-leads.create', compact('units', 'properties', 'agents'));
    }

    /**
     * Store a newly created lead
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'unit_id' => 'nullable|exists:units,id',
            'property_id' => 'nullable|exists:properties,id',
            'preferred_move_in_date' => 'nullable|date',
            'budget_range' => 'nullable|string|max:50',
            'group_size' => 'nullable|integer|min:1|max:20',
            'message' => 'nullable|string',
            'source' => 'required|in:website,referral,social_media,advertising,agent,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id'
        ]);

        $validated['ip_address'] = $request->ip();
        $validated['user_agent'] = $request->userAgent();

        $lead = TenantLead::create($validated);

        // Notify assigned agent if any
        if ($lead->assigned_to) {
            $agent = User::find($lead->assigned_to);
            if ($agent) {
                $agent->notify(new NewTenantLeadNotification($lead));
            }
        }

        // Notify admin about new lead
        $admins = User::whereHas('roles', function($q) {
            $q->where('name', 'Admin');
        })->get();

        Notification::send($admins, new NewTenantLeadNotification($lead));

        return redirect()->route('admin.tenant-leads.index')
            ->with('success', 'Tenant lead created successfully.');
    }

    /**
     * Display the specified lead
     */
    public function show(TenantLead $tenantLead)
    {
        $tenantLead->load(['unit', 'property', 'assignedAgent']);

        return view('admin.tenant-leads.show', compact('tenantLead'));
    }

    /**
     * Show the form for editing the lead
     */
    public function edit(TenantLead $tenantLead)
    {
        $units = Unit::availableForListing()->with('property')->get();
        $properties = Property::approved()->get();
        $agents = User::whereHas('roles', function($q) {
            $q->where('name', 'Agent');
        })->get();

        return view('admin.tenant-leads.edit', compact('tenantLead', 'units', 'properties', 'agents'));
    }

    /**
     * Update the specified lead
     */
    public function update(Request $request, TenantLead $tenantLead)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'unit_id' => 'nullable|exists:units,id',
            'property_id' => 'nullable|exists:properties,id',
            'preferred_move_in_date' => 'nullable|date',
            'budget_range' => 'nullable|string|max:50',
            'group_size' => 'nullable|integer|min:1|max:20',
            'message' => 'nullable|string',
            'status' => 'required|in:new,contacted,qualified,converted,rejected,closed',
            'source' => 'required|in:website,referral,social_media,advertising,agent,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'follow_up_date' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);

        $tenantLead->update($validated);

        return redirect()->route('admin.tenant-leads.show', $tenantLead)
            ->with('success', 'Tenant lead updated successfully.');
    }

    /**
     * Remove the specified lead
     */
    public function destroy(TenantLead $tenantLead)
    {
        $tenantLead->delete();

        return redirect()->route('admin.tenant-leads.index')
            ->with('success', 'Tenant lead deleted successfully.');
    }

    /**
     * Update lead status
     */
    public function updateStatus(Request $request, TenantLead $tenantLead)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,contacted,qualified,converted,rejected,closed',
            'notes' => 'nullable|string'
        ]);

        $oldStatus = $tenantLead->status;
        $tenantLead->update($validated);

        // Add status change note
        if ($validated['notes']) {
            $statusNote = "Status changed from {$oldStatus} to {$validated['status']}: {$validated['notes']}";
            $tenantLead->addNote($statusNote);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Assign lead to agent
     */
    public function assign(Request $request, TenantLead $tenantLead)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'notes' => 'nullable|string'
        ]);

        $oldAgent = $tenantLead->assignedAgent;
        $tenantLead->assignTo($validated['assigned_to']);

        // Add assignment note
        $agent = User::find($validated['assigned_to']);
        $assignmentNote = "Assigned to {$agent->name}";
        if ($oldAgent) {
            $assignmentNote = "Reassigned from {$oldAgent->name} to {$agent->name}";
        }
        if ($validated['notes']) {
            $assignmentNote .= ": {$validated['notes']}";
        }

        $tenantLead->addNote($assignmentNote);

        return response()->json(['success' => true]);
    }

    /**
     * Add note to lead
     */
    public function addNote(Request $request, TenantLead $tenantLead)
    {
        $validated = $request->validate([
            'note' => 'required|string'
        ]);

        $tenantLead->addNote($validated['note']);

        return response()->json(['success' => true]);
    }

    /**
     * Set follow-up date
     */
    public function setFollowUp(Request $request, TenantLead $tenantLead)
    {
        $validated = $request->validate([
            'follow_up_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $tenantLead->setFollowUpDate($validated['follow_up_date']);

        $note = "Follow-up scheduled for " . $validated['follow_up_date'];
        if ($validated['notes']) {
            $note .= ": {$validated['notes']}";
        }
        $tenantLead->addNote($note);

        return response()->json(['success' => true]);
    }

    /**
     * Get leads statistics
     */
    public function stats()
    {
        $stats = [
            'total' => TenantLead::count(),
            'new' => TenantLead::where('status', 'new')->count(),
            'contacted' => TenantLead::where('status', 'contacted')->count(),
            'qualified' => TenantLead::where('status', 'qualified')->count(),
            'converted' => TenantLead::where('status', 'converted')->count(),
            'conversion_rate' => 0,
            'overdue' => TenantLead::where('follow_up_date', '<', now())->whereIn('status', ['new', 'contacted'])->count(),
            'by_source' => TenantLead::select('source', DB::raw('count(*) as count'))
                ->groupBy('source')
                ->get(),
            'by_priority' => TenantLead::select('priority', DB::raw('count(*) as count'))
                ->groupBy('priority')
                ->get(),
            'recent' => TenantLead::recent(7)->count()
        ];

        if ($stats['total'] > 0) {
            $stats['conversion_rate'] = round(($stats['converted'] / $stats['total']) * 100, 2);
        }

        return response()->json($stats);
    }

    /**
     * Export leads
     */
    public function export(Request $request)
    {
        $query = TenantLead::with(['unit', 'property', 'assignedAgent']);

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $leads = $query->get();

        // Generate CSV
        $filename = 'tenant_leads_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($leads) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'Name',
                'Email',
                'Phone',
                'Property',
                'Unit',
                'Move-in Date',
                'Budget',
                'Group Size',
                'Status',
                'Priority',
                'Source',
                'Assigned To',
                'Created At'
            ]);

            // CSV data
            foreach ($leads as $lead) {
                fputcsv($file, [
                    $lead->name,
                    $lead->email,
                    $lead->phone,
                    $lead->property ? $lead->property->name : '',
                    $lead->unit ? $lead->unit->unit_number : '',
                    $lead->preferred_move_in_date ? $lead->preferred_move_in_date->format('Y-m-d') : '',
                    $lead->budget_range,
                    $lead->group_size,
                    $lead->status,
                    $lead->priority,
                    $lead->source,
                    $lead->assignedAgent ? $lead->assignedAgent->name : '',
                    $lead->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
