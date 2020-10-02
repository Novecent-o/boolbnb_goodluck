<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Import model
use App\Suite;
use App\Message;
use App\Highlight;
use App\Visit;
use Carbon\Carbon;

// Import Mail model
use Illuminate\Support\Facades\Mail;
use App\Mail\SendNewMail;

// Import Auth model
use Illuminate\Support\Facades\Auth;

// Database
use Illuminate\Support\Facades\DB;


class SuiteController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
      $suites = Suite::all();
      $highlights_suites = DB::table('highlights')->join('highlight_suite', function($join)
      {
        $join->on('highlight_suite.highlight_id', '=', 'highlights.id');
      })->join('suites', function($join)
      {
        $join->on('suites.id', '=', 'highlight_suite.suite_id');
      })->get();

      // Se l'appartamento ha almeno un abbonamento
      if (isset($highlights_suites)) {
        $highlights_suites_active = [];

        foreach ($highlights_suites as $highlight_suite) {

          $oggi = date('Y-m-d H:i:s');

          // Se la sponsorizzazione é attiva
          if ( $oggi < $highlight_suite->end) {
            if(count($highlights_suites_active) < 6)
            $highlights_suites_active[] = $highlight_suite;
          }
        }
      }

      return view('guest.suites.index', compact('suites', 'highlights_suites_active'));
  }

  // Create messages
  public function store_message(Request $request, Suite $suite)
  {
    $request->validate([
      'body' => 'required|max:2000',
      'email' => 'required|max:255',
      'name' => 'max:255',
    ]);
    $data_request = $request->all();
    $new_message = new Message();


    if (!empty($data_request['name'])) {
      $new_message->fill($data_request);
    } else {
      $new_message->body = $data_request['body'];
      $new_message->email = $data_request['email'];
    }
    $new_message->suite_id = $suite['id'];
    $new_message->save();
    Mail::to($new_message->suite->user->email)->send(new SendNewMail($new_message));

    return redirect()->route('suites.show', $suite);
  }

  public function search()
  {
      $suites = Suite::all();

      return view('guest.suites.search',compact('suites'));
  }

  // /**
  //  * Show the form for creating a new resource.
  //  *
  //  * @return \Illuminate\Http\Response
  //  */
  // public function create()
  // {
  //
  // }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request, Suite $suite)
  {
      $request->validate([
        'body' => 'required|max:2000',
        'email' => 'required|max:255',
        'name' => 'max:255',
      ]);
      $data_request = $request->all();
      $new_message = new Message();


      if (!empty($data_request['name'])) {
        $new_message->fill($data_request);
      } else {
        $new_message->body = $data_request['body'];
        $new_message->email = $data_request['email'];
      }
      $new_message->suite_id = $suite['id'];
      $new_message->save();
      Mail::to($new_message->suite->user->email)->send(new SendNewMail($new_message));

      return redirect()->route('suites.show', $suite);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(Suite $suite)
  {
    $giorno = Carbon::now();
    $new_visit = new Visit();
    $new_visit->data = $giorno;
    $new_visit->ip = 100;
    $new_visit->suite_id = $suite['id'];
    $new_visit->save();

    $user = Auth::user();
    return view('guest.suites.show', compact('suite', 'user'));
  }

}