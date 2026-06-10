@extends('layouts.admin')

@section('title', 'Edit Membership Tier - Admin Dashboard')
@section('page_title', 'Membership Tiers')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">
        
        <!-- Back link and Title -->
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.membership') }}" class="text-slate-800 hover:text-slate-650 font-bold transition text-lg">
                &larr;
            </a>
            <h3 class="font-extrabold text-slate-900 text-lg">Edit Membership Tier</h3>
        </div>

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-250 text-rose-800 px-4 py-3 rounded-lg text-sm font-medium shadow-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden p-6 md:p-8">
            <form action="{{ route('admin.membership.update', $tier->id) }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Name -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tier Name</label>
                        <input type="text" name="name" required value="{{ old('name', $tier->name) }}" placeholder="e.g. Silver Member" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition bg-gray-50/30">
                    </div>

                    <!-- Price -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Price (Rp)</label>
                        <input type="number" name="price" required value="{{ old('price', $tier->price) }}" placeholder="e.g. 250000" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition bg-gray-50/30">
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Status</label>
                        <select name="status" required 
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition bg-white">
                            <option value="active" {{ old('status', $tier->status) === 'active' ? 'selected' : '' }}>ACTIVE</option>
                            <option value="inactive" {{ old('status', $tier->status) === 'inactive' ? 'selected' : '' }}>INACTIVE</option>
                        </select>
                    </div>

                    <!-- Discount Weekday -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Discount Weekday (%)</label>
                        <input type="number" name="discount_wd" required min="0" max="100" value="{{ old('discount_wd', $tier->discount_wd) }}" placeholder="e.g. 5" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition bg-gray-50/30">
                    </div>

                    <!-- Discount Weekend -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Discount Weekend (%)</label>
                        <input type="number" name="discount_we" required min="0" max="100" value="{{ old('discount_we', $tier->discount_we) }}" placeholder="e.g. 5" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition bg-gray-50/30">
                    </div>

                    <!-- Limit Weekday -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Limit Weekday (Quota)</label>
                        <input type="number" name="limit_wd" value="{{ old('limit_wd', $tier->limit_wd) }}" placeholder="Unlimited if empty" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition bg-gray-50/30">
                    </div>

                    <!-- Limit Weekend -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Limit Weekend (Quota)</label>
                        <input type="number" name="limit_we" value="{{ old('limit_we', $tier->limit_we) }}" placeholder="Unlimited if empty" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition bg-gray-50/30">
                    </div>

                    <!-- Limit Window -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Limit Reset Window (Days)</label>
                        <input type="number" name="window" required min="1" value="{{ old('window', $tier->window) }}" placeholder="e.g. 7" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition bg-gray-50/30">
                    </div>

                    <!-- Duration -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Duration (Days)</label>
                        <input type="number" name="duration" required min="1" value="{{ old('duration', $tier->duration) }}" placeholder="e.g. 30" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition bg-gray-50/30">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-100">
                    <a href="{{ route('admin.membership') }}" 
                       class="px-6 py-2.5 border border-gray-300 text-sm font-semibold rounded-lg hover:bg-gray-50 transition text-slate-700">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection
