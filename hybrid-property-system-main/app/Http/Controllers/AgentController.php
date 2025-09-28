<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Agent;
use App\Models\Lead;
use App\Models\Transaction;
use App\Models\Property;

class AgentController extends Controller
{
    public function agentHomepage()
    {
        $user = Auth::user();

        // Ensure user has Agent role
        if (!$user->roles->contains('name', 'Agent')) {
            abort(403, 'Unauthorized');
        }

        $agent = Agent::where('user_id', $user->id)->first();

        if (!$agent) {
            // Create agent record if it doesn't exist
            $agent = Agent::create([
                'user_id' => $user->id,
                'commission_rate' => 0.00,
                'license_no' => null,
            ]);
        }

        // Agent statistics
        $stats = [
            'total_leads' => Lead::where('assigned_to', $agent->id)->count(),
            'active_leads' => Lead::where('assigned_to', $agent->id)->where('status', 'active')->count(),
            'total_transactions' => Transaction::where('agent_id', $agent->id)->count(),
            'commission_earned' => Transaction::where('agent_id', $agent->id)->sum('commission_amount'),
        ];

        // Recent leads
        $recentLeads = Lead::where('assigned_to', $agent->id)
            ->with('buyer.user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Recent transactions
        $recentTransactions = Transaction::where('agent_id', $agent->id)
            ->with('property')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Properties assigned to agent (through leads or transactions)
        // For now, get properties from leads
        $assignedProperties = Property::whereHas('leads', function($query) use ($agent) {
                $query->where('assigned_to', $agent->id);
            })
            ->withCount('units')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Dummy ads data
        $ads = [
            (object) ['image_url' => '/images/ad1.jpg'],
            (object) ['image_url' => '/images/ad2.jpg'],
            (object) ['image_url' => '/images/ad3.jpg'],
        ];

        // Profile completion calculation
        $profile = $user;
        $profileCompletion = 0;
        if ($user->profile_photo) $profileCompletion += 20;
        if ($user->phone_verified) $profileCompletion += 20;
        if ($user->bio) $profileCompletion += 20;
        if ($user->documents_verified) $profileCompletion += 20;
        if ($user->screening_verified) $profileCompletion += 20;

        return view('agent.homepage', compact(
            'stats',
            'recentLeads',
            'recentTransactions',
            'assignedProperties',
            'ads',
            'profile',
            'profileCompletion'
        ));
    }

    public function agentDashboard()
    {
        // For now, same as homepage
        return $this->agentHomepage();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
