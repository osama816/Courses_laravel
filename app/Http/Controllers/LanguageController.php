<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
   public function switch($lang)
{
    session(['locale' => $lang]);
    app()->setLocale($lang);
    return redirect()->back();
}

}
