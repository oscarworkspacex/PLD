<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserBrandingController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            "custom_logo" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
            "custom_company_name" => "nullable|string|max:255",
        ]);

        $user = $request->user();

        if ($request->hasFile("custom_logo")) {
            if ($user->custom_logo && Storage::disk("public")->exists($user->custom_logo)) {
                Storage::disk("public")->delete($user->custom_logo);
            }

            $path = $request->file("custom_logo")->store("logos", "public");
            $user->custom_logo = $path;
        }

        if ($request->filled("custom_company_name")) {
            $user->custom_company_name = $request->custom_company_name;
        }

        $user->save();

        return back()->with("success", "Branding actualizado correctamente");
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        if ($user->custom_logo && Storage::disk("public")->exists($user->custom_logo)) {
            Storage::disk("public")->delete($user->custom_logo);
        }

        $user->custom_logo = null;
        $user->custom_company_name = null;
        $user->save();

        return back()->with("success", "Branding restaurado a valores por defecto");
    }
}
