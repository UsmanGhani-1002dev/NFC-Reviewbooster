<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ManageBusiness;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class CommandPaletteController extends Controller
{
    /**
     * Live search for the admin command palette (Ctrl/⌘ + K): matches real
     * records — users, orders, businesses and products — and returns a small,
     * grouped set for instant jump-to navigation.
     */
    public function search(Request $request)
    {
        $term = trim((string) $request->query('q', ''));

        if ($term === '' || mb_strlen($term) < 2) {
            return response()->json(['results' => []]);
        }

        $like = '%' . $term . '%';
        $results = [];

        // ---- Users --------------------------------------------------------
        foreach (
            User::where(fn ($q) => $q
                ->where('name', 'like', $like)
                ->orWhere('email', 'like', $like)
                ->orWhere('company_name', 'like', $like))
                ->limit(6)->get() as $u
        ) {
            $results[] = [
                'group' => 'Users',
                'icon' => 'user',
                'label' => $u->name ?: $u->email,
                'detail' => trim($u->email . ($u->role ? ' · ' . ucfirst(str_replace('_', ' ', $u->role)) : '')),
                'url' => route('admin.users.edit', $u->id),
            ];
        }

        // ---- Orders -------------------------------------------------------
        foreach (
            Order::where(fn ($q) => $q
                ->where('order_number', 'like', $like)
                ->orWhere('customer_name', 'like', $like)
                ->orWhere('customer_email', 'like', $like))
                ->latest()->limit(6)->get() as $o
        ) {
            $results[] = [
                'group' => 'Orders',
                'icon' => 'order',
                'label' => $o->order_number . ($o->customer_name ? ' · ' . $o->customer_name : ''),
                'detail' => '£' . number_format((float) $o->total, 2) . ' · ' . ucfirst($o->status),
                'url' => route('admin.orders.edit', $o->id),
            ];
        }

        // ---- Businesses ---------------------------------------------------
        foreach (
            ManageBusiness::where(fn ($q) => $q
                ->where('business_name', 'like', $like)
                ->orWhere('legal_business_name', 'like', $like)
                ->orWhere('website', 'like', $like))
                ->limit(6)->get() as $b
        ) {
            $results[] = [
                'group' => 'Businesses',
                'icon' => 'business',
                'label' => $b->business_name ?: $b->legal_business_name ?: 'Business #' . $b->id,
                'detail' => $b->legal_business_name ?: ($b->website ?: ($b->status ? ucfirst($b->status) : '')),
                'url' => route('admin.manage_business.view', $b->id),
            ];
        }

        // ---- Products -----------------------------------------------------
        foreach (
            Product::where('name', 'like', $like)
                ->orWhere('slug', 'like', $like)
                ->limit(5)->get() as $p
        ) {
            $results[] = [
                'group' => 'Products',
                'icon' => 'product',
                'label' => $p->name,
                'detail' => 'Product',
                'url' => route('admin.products.edit', $p->id),
            ];
        }

        return response()->json(['results' => $results]);
    }
}
