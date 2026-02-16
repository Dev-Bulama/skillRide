<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\View\View;

class LandingPageController extends Controller
{
    /**
     * Display the public landing page.
     *
     * Fetches all landing-group settings from the site_settings table
     * and falls back to sensible defaults for a Nigerian keke/tricycle
     * fleet management platform.
     */
    public function index(): View
    {
        $defaults = [
            // Hero section
            'hero_title'            => 'Manage Your Keke Fleet with Confidence',
            'hero_subtitle'         => 'SkillRide is the all-in-one platform for tricycle fleet owners and operators across Nigeria. Track vehicles, manage drivers, and grow your business effortlessly.',
            'hero_cta_text'         => 'Get Started',
            'hero_cta_url'          => '/register',

            // About section
            'about_title'           => 'About SkillRide',
            'about_description'     => 'SkillRide was built for the Nigerian transportation industry. We help keke and tricycle fleet owners streamline daily operations, reduce downtime, and increase profitability — whether you run 5 vehicles or 500.',

            // Features section
            'feature_1_title'       => 'Fleet Tracking',
            'feature_1_description' => 'Monitor every keke in your fleet in real time. Know where your vehicles are, track trip history, and optimise routes across Lagos, Abuja, Port Harcourt, and beyond.',
            'feature_2_title'       => 'Driver Management',
            'feature_2_description' => 'Onboard drivers, assign vehicles, and track daily remittances. SkillRide makes it easy to manage your entire driver workforce from one dashboard.',
            'feature_3_title'       => 'Revenue Analytics',
            'feature_3_description' => 'See exactly how much each vehicle and driver earns. Generate reports, spot trends, and make data-driven decisions to grow your fleet business.',

            // Call-to-action section
            'cta_title'             => 'Ready to Transform Your Fleet Operations?',
            'cta_description'       => 'Join hundreds of keke fleet owners across Nigeria who trust SkillRide to run their businesses. Sign up today and take control of your fleet.',

            // Contact section
            'contact_email'         => 'hello@skillride.ng',
            'contact_phone'         => '+234 800 000 0000',
            'contact_address'       => '12 Ademola Adetokunbo Crescent, Wuse II, Abuja, Nigeria',
        ];

        // Fetch all settings that belong to the "landing" group in a single
        // query, then key the collection by the `key` column for fast lookup.
        $stored = SiteSetting::where('group', 'landing')
            ->pluck('value', 'key');

        // Merge: stored values override defaults.
        $settings = [];

        foreach ($defaults as $key => $default) {
            $settings[$key] = $stored[$key] ?? $default;
        }

        return view('landing', ['settings' => $settings]);
    }
}
