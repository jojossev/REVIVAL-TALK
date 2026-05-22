<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class InstallerController extends Controller
{
    public function purchaseCodeIndex()
    {
        // Purchase code verification removed - redirect directly to database
        return redirect()->route('install.database');
    }

    public function checkPurchaseCode(Request $request)
    {
        // Purchase code verification removed - redirect directly to database
        return redirect()->route('install.database');
    }
}
