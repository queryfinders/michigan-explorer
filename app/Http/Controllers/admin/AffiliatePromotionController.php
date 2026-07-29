<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AffiliatePromotion;
use App\Models\AffiliateLink;
use App\Http\Requests\Admin\PromotionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AffiliatePromotionController extends Controller
{
    public function index(Request $request)
    {
        $query = AffiliatePromotion::with('affiliateLink');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('badge_text', 'like', "%{$search}%");
            });
        }

        if ($request->filled('placement')) {
            $query->where('placement', $request->input('placement'));
        }

        if ($request->has('status') && $request->input('status') !== '') {
            $query->where('is_active', $request->input('status'));
        }

        $promotions = $query->orderBy('priority', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Fetch click analytics dynamically for listing page
        foreach ($promotions as $promo) {
            $promo->clicks_count = \App\Models\AffiliateClickLog::where('entity_type', 'promotion')
                ->where('entity_id', $promo->id)
                ->count();
            
            $lastClick = \App\Models\AffiliateClickLog::where('entity_type', 'promotion')
                ->where('entity_id', $promo->id)
                ->latest('clicked_at')
                ->first();

            $promo->last_click_at = $lastClick ? $lastClick->clicked_at : null;
        }

        return view('new_content.admin.affiliate_promotions.index', compact('promotions'));
    }

    public function create()
    {
        $affiliateLinks = AffiliateLink::orderBy('name')->get();
        return view('new_content.admin.affiliate_promotions.create', compact('affiliateLinks'));
    }

    public function store(PromotionRequest $request)
    {
        $data = $request->except('_token', '_method', 'desktop_image', 'mobile_image');
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('desktop_image')) {
            $path = $request->file('desktop_image')->store('promotions', 'public');
            $data['desktop_image'] = 'storage/' . $path;
        }

        if ($request->hasFile('mobile_image')) {
            $path = $request->file('mobile_image')->store('promotions', 'public');
            $data['mobile_image'] = 'storage/' . $path;
        }

        AffiliatePromotion::create($data);

        return redirect()->route('affiliate-promotions.index')->with('success', 'Promotion created successfully.');
    }

    public function edit($id)
    {
        $promotion = AffiliatePromotion::findOrFail($id);
        $affiliateLinks = AffiliateLink::orderBy('name')->get();
        return view('new_content.admin.affiliate_promotions.edit', compact('promotion', 'affiliateLinks'));
    }

    public function update(PromotionRequest $request, $id)
    {
        $promotion = AffiliatePromotion::findOrFail($id);
        $data = $request->except('_token', '_method', 'desktop_image', 'mobile_image');
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('desktop_image')) {
            // Delete old desktop image
            if ($promotion->desktop_image) {
                $oldPath = str_replace('storage/', '', $promotion->desktop_image);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('desktop_image')->store('promotions', 'public');
            $data['desktop_image'] = 'storage/' . $path;
        }

        if ($request->hasFile('mobile_image')) {
            // Delete old mobile image
            if ($promotion->mobile_image) {
                $oldPath = str_replace('storage/', '', $promotion->mobile_image);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('mobile_image')->store('promotions', 'public');
            $data['mobile_image'] = 'storage/' . $path;
        }

        $promotion->update($data);

        return redirect()->route('affiliate-promotions.index')->with('success', 'Promotion updated successfully.');
    }

    public function destroy($id)
    {
        $promotion = AffiliatePromotion::findOrFail($id);

        // Delete images
        if ($promotion->desktop_image) {
            $oldPath = str_replace('storage/', '', $promotion->desktop_image);
            Storage::disk('public')->delete($oldPath);
        }
        if ($promotion->mobile_image) {
            $oldPath = str_replace('storage/', '', $promotion->mobile_image);
            Storage::disk('public')->delete($oldPath);
        }

        $promotion->delete();

        return redirect()->route('affiliate-promotions.index')->with('success', 'Promotion deleted successfully.');
    }

    public function changeStatus($id, $status)
    {
        $promotion = AffiliatePromotion::findOrFail($id);
        $promotion->is_active = $status == 1 ? 0 : 1;
        $promotion->save();

        return response()->json([
            'success' => true, 
            'message' => 'Status updated successfully.', 
            'status' => $promotion->is_active ? 1 : 0
        ]);
    }
}
