<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Suite;
use App\Message;
use App\Image;
use App\Visit;
use App\Highlight;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendNewMail;
use Carbon\Carbon;

class SuiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $suites = Suite::all();

        // Loop searching for admin suites
        foreach ($suites as $suite) {
          $id_my_suites = $user->id;
          if ($suite->user_id === $id_my_suites) {
            $my_suites[] = $suite;
          }
        }
        if (!isset($my_suites)) {
          $my_suites = '';
        }

        if(Auth::check()) {
          return view('admin.suites.index', compact('suites', 'user', 'my_suites'));
        } else {
          return view('guest.suites.index', compact('suites'));
        }
    }

    public function show(Suite $suite)
    {
      $user = Auth::user();
      return view('guest.suites.show', compact('suite', 'user'));
    }

    public function mysuites()
    {
        $user = Auth::user();
        $suites = Suite::all();

        // Loop searching for admin suites
        foreach ($suites as $suite) {
          $id_my_suites = $user->id;
          if ($suite->user_id === $id_my_suites) {
            $my_suites[] = $suite;
          }
        }
        if (!isset($my_suites)) {
          $my_suites = '';
        }

          return view('admin.suites.mysuites', compact('user', 'my_suites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.suites.create');
    }

    public function messages()
    {
        $messages = Message::all();
        $user = Auth::user();
        return view('admin.email.messages.index',compact('messages','user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $request->validate([
        'title'=> 'required|max:255',
        'address'=> 'required|max:255',
        'rooms'=> 'required|min:1',
        'beds'=> 'required|min:1',
        'baths'=> 'required|min:1',
        'square_m'=> 'required|min:1',
        // 'latitude'=> 'required|min:-90|max:90',
        // 'longitude'=> 'required|min:-180|max:180',
        'price'=> 'required|min:1|max:9999,99',
        'description'=> 'required',
        'main_image'=> 'required|image',
      ]);

      if (Auth::check()) {
        $request_data = $request->all();
        $path_image = $request->file('main_image')->store('images', 'public');
        // $user = Auth::user();  // da rivedere
        $new_suite = new Suite();
        $new_suite->user_id = Auth::id();
        $new_suite->title = $request_data['title'];
        $new_suite->address = $request_data['address'];
        $new_suite->rooms = $request_data['rooms'];
        $new_suite->beds = $request_data['beds'];
        $new_suite->baths = $request_data['baths'];
        $new_suite->square_m = $request_data['square_m'];
        $new_suite->latitude = 0; //$request_data['latitude'];
        $new_suite->longitude = 0; //$request_data['longitude'];
        $new_suite->price = $request_data['price'];
        $new_suite->description = $request_data['description'];
        $new_suite->main_image = $path_image;
        $new_suite->save();
        // aggiunta di più immagini alla suite
        // $new_image = new Image();
        // $new_image->suite_id = $request_data['suite_id'];
        // $new_image->suite_id = $request_data['suite_id'];
      }

      return redirect()->route('admin.suites.show', $new_suite);

    }

    public function store_payment(Request $request,Suite $suite)
    {
      $start = Carbon::now('Europe/Rome');
      if ($request->type == '24') {
        $suite->highlights()->attach(1,
          [
            'start' => $start,
            'end' => $end = Carbon::now()->addHours(24)
          ]);
      }elseif ($request->type == '72') {
        $suite->highlights()->attach(2,
          [
            'start' => $start,
            'end' => $end = Carbon::now()->addHours(72)
          ]);
      }else {
        $suite->highlights()->attach(3,
          [
            'start' => $start,
            'end' => $end = Carbon::now()->addHours(144)
          ]);
      }

      return redirect()->route('admin.suites.show', $suite);
    }

    public function payment(Suite $suite)
    {
      $user = Auth::user();
      return view('admin.suites.payment', compact('suite', 'user'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Suite $suite)
    {
      return view('admin.suites.edit', compact('suite'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Suite $suite)
    {
      $request->validate([
        'title'=> 'required|max:255',
        'address'=> 'required|max:255',
        'rooms'=> 'required|min:1',
        'beds'=> 'required|min:1',
        'baths'=> 'required|min:1',
        'square_m'=> 'required|min:1',
        // 'latitude'=> 'required|min:-90|max:90',
        // 'longitude'=> 'required|min:-180|max:180',
        'price'=> 'required|min:1|max:9999,99',
        'description'=> 'required',
        'main_image'=> 'required|image',
      ]);
      $data = $request->all();
      $suite->user_id = Auth::id();
      $suite->title = $data['title'];
      $suite->beds = $data['beds'];
      $suite->baths = $data['baths'];
      $suite->rooms = $data['rooms'];
      $suite->square_m = $data['square_m'];
      $suite->address = $data['address'];
      $suite->price = $data['price'];
      $suite->description = $data['description'];
      $path_image = $request->file('main_image')->store('images', 'public');
      $suite->main_image = $path_image;
      $suite->update();

      return redirect()->route('admin.suites.show', $suite);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function static(Suite $suite)
    {
      $user = Auth::user();
      return view('admin.suites.static', compact('suite', 'user'));
      // 'n_mess_totali','n_vis_totali'
    }

}
