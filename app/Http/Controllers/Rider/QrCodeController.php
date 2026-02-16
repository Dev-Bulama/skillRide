<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\VehicleService;
use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    public function __construct(protected VehicleService $vehicleService) {}

    public function index()
    {
        $rider = auth()->user();
        $vehicle = $rider->riderProfile?->assignedVehicle;
        $qrCode = null;

        if ($vehicle) {
            $verifyUrl = route('verify.qr', $vehicle->registration_number);
            $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)
                ->format('svg')
                ->generate($verifyUrl);
        }

        return view('rider.qr.index', compact('rider', 'vehicle', 'qrCode'));
    }

    public function generate()
    {
        $rider = auth()->user();
        $vehicle = $rider->riderProfile?->assignedVehicle;

        if ($vehicle) {
            $this->vehicleService->generateQrCode($vehicle);
        }

        return back()->with('success', 'QR code generated.');
    }

    public function verify(string $code)
    {
        $vehicle = Vehicle::where('registration_number', $code)->first();

        if (!$vehicle) {
            return view('rider.qr.verify', ['valid' => false]);
        }

        $rider = $vehicle->assignedRider;
        $route = $rider?->riderProfile?->assignedRoute;

        return view('rider.qr.verify', [
            'valid' => true,
            'vehicle' => $vehicle,
            'rider' => $rider,
            'route' => $route,
        ]);
    }
}
