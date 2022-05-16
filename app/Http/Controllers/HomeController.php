<?php

namespace App\Http\Controllers;

use App\Models\Pictures;
use Doctrine\DBAL\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use PHPHtmlParser\Dom;

/**
 * Отвечает за переходы по страницам.
 */

class HomeController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index() {
        Auth::logout();
        return view('index')->with('data', ['page' => 'index']);

        $table = (new TimetableController);

        $outSearch = $table->searchTimetable("Уланов", "lecturers");
        echo $outSearch;
        $outGet = $table->getTimetable("\/timetable\/lecturers\/19\/103\/2122095731\/", "lecturers", 585);
        echo $outGet;
        return;
    }
}
