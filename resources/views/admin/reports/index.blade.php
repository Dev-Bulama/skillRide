@extends('layouts.admin')
@section('title', 'Reports')
@section('header', 'Reports & Analytics')
@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
@foreach([['Revenue Report', 'View revenue analytics and trends', route('admin.reports.revenue'), 'emerald', 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'], ['Rider Performance', 'Rider activity and payment compliance', route('admin.reports.riders'), 'blue', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'], ['Fleet Utilization', 'Vehicle usage and maintenance costs', route('admin.reports.fleet'), 'purple', 'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z'], ['Payment Arrears', 'Overdue payments analysis', route('admin.payments.arrears'), 'red', 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z']] as $r)
<a href="{{ $r[2] }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 hover:shadow-md transition group">
<div class="w-12 h-12 bg-{{ $r[3] }}-100 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition"><svg class="w-6 h-6 text-{{ $r[3] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $r[4] }}"/></svg></div>
<h3 class="font-semibold text-gray-800 dark:text-white">{{ $r[0] }}</h3><p class="text-sm text-gray-500 mt-1">{{ $r[1] }}</p></a>
@endforeach
</div>
<div class="mt-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
<h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Export Reports</h3>
<form method="GET" action="{{ route('admin.reports.export') }}" class="flex flex-wrap gap-3 items-end">
<div><label class="block text-xs text-gray-500 mb-1">Type</label><select name="type" class="rounded-lg border-gray-300 text-sm focus:ring-emerald-500"><option value="revenue">Revenue</option><option value="arrears">Arrears</option></select></div>
<div><label class="block text-xs text-gray-500 mb-1">From</label><input type="date" name="from" value="{{ now()->startOfMonth()->format('Y-m-d') }}" class="rounded-lg border-gray-300 text-sm focus:ring-emerald-500"></div>
<div><label class="block text-xs text-gray-500 mb-1">To</label><input type="date" name="to" value="{{ now()->format('Y-m-d') }}" class="rounded-lg border-gray-300 text-sm focus:ring-emerald-500"></div>
<button type="submit" class="px-4 py-2 bg-emerald-500 text-white rounded-lg text-sm font-medium hover:bg-emerald-600">Export CSV</button>
<a href="{{ route('admin.reports.pdf') }}?from={{ now()->startOfMonth()->format('Y-m-d') }}&to={{ now()->format('Y-m-d') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600">Download PDF</a>
</form></div>
@endsection
