<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index() {
        return view('dashboard.index-dashboard');
    }

    public function routes($route)
    {
        // Normalize to dot notation: "dashboard/reports" => "dashboard.reports"
        $key = str_replace('/', '.', trim($route, '/'));

        if ($key === '') {
            return $this->index();
        }

        if (view()->exists($key)) {
            return view($key);
        }
        if (view()->exists($key . '.index')) {
            return view($key . '.index');
        }
        if (view()->exists($key . '.index-dashboard')) {
            return view($key . '.index-dashboard');
        }
        if (view()->exists($key . '.index-feedback')) {
            return view($key . '.index-feedback');
        }
        if (view()->exists($key . '.index-department-management')) {
            return view($key . '.index-department-management');
        }
        if (view()->exists($key . '.index-course-management')) {
            return view($key . '.index-course-management');
        }
        if (view()->exists($key . '.index-schoolyear-semester')) {
            return view($key . '.index-schoolyear-semester');
        }
        if (view()->exists($key . '.index-registration-request')) {
            return view($key . '.index-registration-request');
        }
        if (view()->exists($key . '.index-registration-rejected')) {
            return view($key . '.index-registration-rejected');
        }
        if (view()->exists($key . '.index-registration-approved')) {
            return view($key . '.index-registration-approved');
        }
        if (view()->exists($key . '.index-position')) {
            return view($key . '.index-position');
        }
        if (view()->exists($key . '.index-set-signatory')) {
            return view($key . '.index-set-signatory');
        }
        if (view()->exists($key . '.index-profile-management')) {
            return view($key . '.index-profile-management');
        }
        if (view()->exists($key . '.index-candidacy-management')) {
            return view($key . '.index-candidacy-management');
        }
        if (view()->exists($key . '.index-candidacy-management1')) {
            return view($key . '.index-candidacy-management1');
        }
        if (view()->exists($key . '.index-action-center')) {
            return view($key . '.index-action-center');
        }
        if (view()->exists($key . '.index-room-to-room')) {
            return view($key . '.index-room-to-room');
        }
        if (view()->exists($key . '.index-meeting-abanse')) {
            return view($key . '.index-meeting-abanse');
        }
        if (view()->exists($key . '.index-display-room')) {
            return view($key . '.index-display-room');
        }
        if (view()->exists($key . '.index-display-meeting-abanse')) {
            return view($key . '.index-display-meeting-abanse');
        }
        if (view()->exists($key . '.index-admin-account')) {
            return view($key . '.index-admin-account');
        }
        if (view()->exists($key . '.index-activity-logs')) {
            return view($key . '.index-activity-logs');
        }
        if (view()->exists($key . '.index-calendar')) {
            return view($key . '.index-calendar');
        }
        if (view()->exists($key . '.index-student-account')) {
            return view($key . '.index-student-account');
        }
        if (view()->exists($key . '.index-voting-exclusive')) {
            return view($key . '.index-voting-exclusive');
        }
        if (view()->exists($key . '.index-on-going-election')) {
            return view($key . '.index-on-going-election');
        }
        if (view()->exists($key . '.index-candidates-position')) {
            return view($key . '.index-candidates-position');
        }
        if (view()->exists($key . '.index-candidates-election')) {
            return view($key . '.index-candidates-election');
        }
        if (view()->exists($key . '.index-list-students-account')) {
            return view($key . '.index-list-students-account');
        }
        if (view()->exists($key . '.index-list-admin-account')) {
            return view($key . '.index-list-admin-account');
        }
        if (view()->exists($key . '.index-system-settings')) {
            return view($key . '.index-system-settings');
        }
        if (view()->exists($key . '.index-partylist-management')) {
            return view($key . '.index-partylist-management');
        }
       
        return abort(404);
    }
}