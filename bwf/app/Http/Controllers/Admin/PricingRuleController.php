<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\PricingRule;
use Illuminate\Http\Request;

class PricingRuleController extends Controller
{
    public function index()
    {
        $rules = PricingRule::whereNull('product_id')->orderBy('component')->orderBy('priority')->get();
        return view('admin.pricing', compact('rules'));
    }
    public function update(Request $request, PricingRule $rule)
    {
        $data = $request->validate(['value' => 'required|numeric|min:0']);
        AuditLog::create(['user_id' => $request->user()->id, 'action' => 'update', 'entity' => 'pricing_rule',
            'entity_id' => $rule->id, 'old_value' => ['value' => $rule->value], 'new_value' => $data]);
        $rule->update(['value' => $data['value'], 'is_placeholder' => false]);
        return back()->with('ok', $rule->label.' updated.');
    }
}
