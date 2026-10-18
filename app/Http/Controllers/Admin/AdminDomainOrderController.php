<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DomainOrder;
use Illuminate\Http\Request;

class AdminDomainOrderController extends Controller
{
    public function index(Request $request)
    {
        return redirect()->route('admin.orders.index', [
            'tab'     => 'omset',
            'tx_type' => 'domain',
            'q'       => $request->get('q'),
            'period'  => $request->get('period', '30d'),
        ]);
    }

    public function updateStatus(Request $request, DomainOrder $domainOrder)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,cancelled'
        ]);

        $oldStatus = $domainOrder->status;
        $domainOrder->status = $request->status;
        $domainOrder->save();

        // If status changed to paid, sync user custom_domain if empty
        if ($request->status === 'paid' && $oldStatus !== 'paid' && $domainOrder->user) {
            $user = $domainOrder->user;
            if ($user->creatorProfile && empty($user->creatorProfile->custom_domain)) {
                $user->creatorProfile->custom_domain = $domainOrder->domain_name;
                $user->creatorProfile->save();
            }
        }

        return back()->with('success', "Status order domain '{$domainOrder->domain_name}' berhasil diperbarui ke " . strtoupper($request->status) . "!");
    }
}
