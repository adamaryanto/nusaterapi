<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\WebSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WebManagementController extends Controller
{
    public function index()
    {
        $settings = WebSetting::all()->pluck('value', 'key');
        $services = Service::orderBy('sort_order')->get();
        return view('admin.web_management', compact('settings', 'services'));
    }

    // ---------------------------------------------------
    // Banner
    // ---------------------------------------------------
    public function updateBanner(Request $request)
    {
        $request->validate([
            'banner_headline' => 'required|string|max:255',
            'banner_subheadline' => 'nullable|string|max:255',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        WebSetting::set('banner_headline', $request->banner_headline);
        WebSetting::set('banner_subheadline', $request->banner_subheadline);

        if ($request->hasFile('banner_image')) {
            $old = WebSetting::get('banner_image');
            if ($old && file_exists(public_path($old))) {
                @unlink(public_path($old));
            }
            $file = $request->file('banner_image');
            $filename = 'banner_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/web'), $filename);
            WebSetting::set('banner_image', 'uploads/web/' . $filename);
        }

        return redirect()->route('admin.web_management')->with('success', 'Banner berhasil disimpan.');
    }

    // ---------------------------------------------------
    // About
    // ---------------------------------------------------
    public function updateAbout(Request $request)
    {
        $request->validate([
            'about_title' => 'required|string|max:255',
            'about_description' => 'required|string',
            'about_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        WebSetting::set('about_title', $request->about_title);
        WebSetting::set('about_description', $request->about_description);

        if ($request->hasFile('about_image')) {
            $old = WebSetting::get('about_image');
            if ($old && file_exists(public_path($old))) {
                @unlink(public_path($old));
            }
            $file = $request->file('about_image');
            $filename = 'about_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/web'), $filename);
            WebSetting::set('about_image', 'uploads/web/' . $filename);
        }

        return redirect()->route('admin.web_management')->with('success', 'Bagian Tentang Kami berhasil disimpan.');
    }

    // ---------------------------------------------------
    // Services
    // ---------------------------------------------------
    public function servicesStore(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'default_duration' => 'required|string|max:50',
            'price_clinic'     => 'required|integer|min:0',
            'price_home'       => 'required|integer|min:0',
            'description'      => 'nullable|string|max:500',
            'status'           => 'required|in:Active,Inactive',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'service_' . time() . '_' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/services'), $filename);
            $imagePath = 'uploads/services/' . $filename;
        }

        $maxOrder = Service::max('sort_order') ?? 0;

        Service::create([
            'name'             => $request->name,
            'slug'             => Str::slug($request->name),
            'image_path'       => $imagePath,
            'default_duration' => $request->default_duration,
            'price_clinic'     => $request->price_clinic,
            'price_home'       => $request->price_home,
            'description'      => $request->description,
            'status'           => $request->status,
            'sort_order'       => $maxOrder + 1,
        ]);

        return redirect()->route('admin.web_management')->with('success', 'Layanan baru berhasil ditambahkan.');
    }

    public function servicesUpdate(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $request->validate([
            'name'             => 'required|string|max:255',
            'default_duration' => 'required|string|max:50',
            'price_clinic'     => 'required|integer|min:0',
            'price_home'       => 'required|integer|min:0',
            'description'      => 'nullable|string|max:500',
            'status'           => 'required|in:Active,Inactive',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = $service->image_path;
        if ($request->hasFile('image')) {
            if ($imagePath && file_exists(public_path($imagePath))) {
                @unlink(public_path($imagePath));
            }
            $file = $request->file('image');
            $filename = 'service_' . time() . '_' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/services'), $filename);
            $imagePath = 'uploads/services/' . $filename;
        }

        $service->update([
            'name'             => $request->name,
            'slug'             => Str::slug($request->name),
            'image_path'       => $imagePath,
            'default_duration' => $request->default_duration,
            'price_clinic'     => $request->price_clinic,
            'price_home'       => $request->price_home,
            'description'      => $request->description,
            'status'           => $request->status,
        ]);

        return redirect()->route('admin.web_management')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function servicesDestroy($id)
    {
        $service = Service::findOrFail($id);
        if ($service->image_path && file_exists(public_path($service->image_path))) {
            @unlink(public_path($service->image_path));
        }
        $service->delete();
        return redirect()->route('admin.web_management')->with('success', 'Layanan berhasil dihapus.');
    }

    public function updateMembership(Request $request)
    {
        $request->validate([
            'membership_weekly_limit' => 'required|integer|min:0',
            'membership_discount_amount' => 'required|integer|min:0',
        ]);

        WebSetting::set('membership_weekly_limit', $request->membership_weekly_limit);
        WebSetting::set('membership_discount_amount', $request->membership_discount_amount);

        return redirect()->route('admin.web_management')->with('success', 'Pengaturan membership berhasil disimpan.');
    }
}
