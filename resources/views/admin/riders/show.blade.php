@extends('layouts.admin')
@section('title', 'Rider Details')
@section('header', 'Rider - ' . $rider->name)

@section('content')
<div class="space-y-6">
    {{-- Breadcrumb & Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <a href="{{ route('admin.riders.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-emerald-600">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Riders
        </a>
        <div class="flex flex-wrap items-center gap-2">
            @if($rider->status === 'pending')
                <form method="POST" action="{{ route('admin.riders.approve', $rider) }}" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-500 text-white text-sm font-medium rounded-lg hover:bg-emerald-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Approve
                    </button>
                </form>
            @endif
            @if($rider->status === 'active')
                <form method="POST" action="{{ route('admin.riders.suspend', $rider) }}" class="inline" onsubmit="return confirm('Suspend this rider?')">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500 text-white text-sm font-medium rounded-lg hover:bg-red-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                        Suspend
                    </button>
                </form>
            @endif
            @if($rider->status === 'suspended' || $rider->status === 'inactive')
                <form method="POST" action="{{ route('admin.riders.activate', $rider) }}" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-500 text-white text-sm font-medium rounded-lg hover:bg-green-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Activate
                    </button>
                </form>
            @endif
            <form method="POST" action="{{ route('admin.impersonate.start', $rider) }}" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-lg hover:bg-blue-600">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Impersonate
                </button>
            </form>
            <a href="{{ route('admin.riders.edit', $rider) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Profile Card --}}
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <div class="text-center mb-6">
                    @if($rider->riderProfile && $rider->riderProfile->profile_photo)
                        <img src="{{ Storage::url($rider->riderProfile->profile_photo) }}" alt="{{ $rider->name }}" class="w-24 h-24 rounded-full object-cover mx-auto border-4 border-emerald-100">
                    @else
                        <div class="w-24 h-24 bg-gradient-to-br from-emerald-400 to-blue-500 rounded-full flex items-center justify-center mx-auto text-white font-bold text-2xl">
                            {{ strtoupper(substr($rider->name, 0, 2)) }}
                        </div>
                    @endif
                    <h3 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">{{ $rider->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $rider->email }}</p>
                </div>

                {{-- Status & Verified Badges --}}
                <div class="flex items-center justify-center gap-2 mb-6">
                    @php
                        $statusColors = [
                            'active' => 'bg-emerald-100 text-emerald-800',
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'suspended' => 'bg-red-100 text-red-800',
                            'inactive' => 'bg-gray-100 text-gray-800',
                        ];
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$rider->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($rider->status) }}
                    </span>
                    @if($rider->is_verified)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Verified
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                            Unverified
                        </span>
                    @endif
                </div>

                {{-- Contact Details --}}
                <div class="space-y-3 border-t border-gray-100 dark:border-gray-700 pt-4">
                    <div class="flex items-center text-sm">
                        <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span class="text-gray-700 dark:text-gray-300">{{ $rider->phone ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center text-sm">
                        <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span class="text-gray-700 dark:text-gray-300">{{ $rider->email }}</span>
                    </div>
                    @if($rider->riderProfile && $rider->riderProfile->address)
                    <div class="flex items-start text-sm">
                        <svg class="w-4 h-4 mr-3 mt-0.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="text-gray-700 dark:text-gray-300">{{ $rider->riderProfile->address }}</span>
                    </div>
                    @endif
                </div>

                {{-- Vehicle Info --}}
                @if($rider->riderProfile && $rider->riderProfile->assignedVehicle)
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Assigned Vehicle</h4>
                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $rider->riderProfile->assignedVehicle->registration_number }}</p>
                        <p class="text-xs text-gray-500">{{ $rider->riderProfile->assignedVehicle->make }} {{ $rider->riderProfile->assignedVehicle->model }}</p>
                    </div>
                </div>
                @endif

                {{-- Documents Link --}}
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <a href="{{ route('admin.riders.documents', $rider) }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-700 font-medium">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        View Documents
                    </a>
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Payment Summary --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment Summary</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg text-center">
                        <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">Total Paid</p>
                        <p class="text-xl font-bold text-emerald-700 dark:text-emerald-300 mt-1">&#8358;{{ number_format($paymentSummary['total_paid'] ?? 0, 2) }}</p>
                    </div>
                    <div class="p-4 bg-red-50 dark:bg-red-900/20 rounded-lg text-center">
                        <p class="text-xs text-red-600 dark:text-red-400 font-medium">Arrears</p>
                        <p class="text-xl font-bold text-red-700 dark:text-red-300 mt-1">&#8358;{{ number_format($paymentSummary['arrears'] ?? 0, 2) }}</p>
                    </div>
                    <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-center">
                        <p class="text-xs text-blue-600 dark:text-blue-400 font-medium">This Month</p>
                        <p class="text-xl font-bold text-blue-700 dark:text-blue-300 mt-1">&#8358;{{ number_format($paymentSummary['this_month'] ?? 0, 2) }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg text-center">
                        <p class="text-xs text-gray-600 dark:text-gray-400 font-medium">Next Due</p>
                        <p class="text-sm font-bold text-gray-700 dark:text-gray-300 mt-1">{{ isset($paymentSummary['next_due']) ? \Carbon\Carbon::parse($paymentSummary['next_due'])->format('M d, Y') : 'N/A' }}</p>
                    </div>
                </div>
            </div>

            {{-- Recent Payments Table --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Payments</h3>
                </div>
                @if(isset($paymentSummary['recent_payments']) && count($paymentSummary['recent_payments']) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($paymentSummary['recent_payments'] as $payment)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $payment->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">&#8358;{{ number_format($payment->amount, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ ucfirst($payment->payment_method ?? 'N/A') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $payment->status === 'paid' ? 'bg-emerald-100 text-emerald-800' : ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-6 text-center text-gray-400">
                        <p class="text-sm">No payment records found.</p>
                    </div>
                @endif
            </div>

            {{-- Activity Timeline --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Account Information</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <p class="text-xs text-gray-500">Joined</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $rider->created_at->format('M d, Y') }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <p class="text-xs text-gray-500">Last Updated</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $rider->updated_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
