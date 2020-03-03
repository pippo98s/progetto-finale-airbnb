<?php

namespace App\Http\Controllers;
use App\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Apartment;
use App\Service;
use App\Message;
use Braintree;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class apiController extends Controller
{
  public function getStatistics(Request $request)
  {
    $data = $request -> validate(['param' => 'numeric']);
      $views = DB::table('views')
      -> select(DB::raw('COUNT(creation_date) AS num'), DB::raw("DATE_FORMAT(creation_date, '%Y-%m-%d') AS giorno_visualizzazione"))
      -> where('apartment_id', $data['param'])
      -> groupBy('giorno_visualizzazione')->get();

      $messages = DB::table('messages')
          ->select(DB::raw('COUNT(creation_date) AS num'), DB::raw("DATE_FORMAT(creation_date, '%Y-%m-%d') AS giorno_visualizzazione"))
          ->where('apartment_id', $data['param'])
          ->groupBy('giorno_visualizzazione')->get();

      $newViews = [];
      foreach($views->toArray() as $view){

          $newViews['dates'][] = $view-> giorno_visualizzazione;
          $newViews['numbers'][] = $view-> num;

      }

      $newMessages = [];
      foreach ($messages->toArray() as $message) {

          $newMessages['dates'][] = $message-> giorno_visualizzazione;
          $newMessages['numbers'][] = $message-> num;

      }

      $results = [

          'views' => $newViews,
          'messages' => $newMessages
      ];

      return response()->json($results);
  }

  public function getBraintreeToken(Request $request){

    $gateway = new Braintree\Gateway([
      'environment' => config('services.braintree.environment'),
      'merchantId' => config('services.braintree.merchant_id'),
      'publicKey' => config('services.braintree.publicKey'),
      'privateKey' => config('services.braintree.privateKey')
    ]);

    $clientToken = $gateway -> clientToken() -> generate();

    return response() -> json($clientToken);

  }

  public function apartmentFilter(Request $request){

    $services = Service::all();
    $radius = $request->get('radius');
    $lat = $request->get('address_lat');
    $lon = $request->get('address_lon');
    $rooms_number = $request->get('rooms_number');
    $guests_number = $request->get('guests_number');
    $servicesQuery = $request->validate(["services" => 'nullable|array']);

    $toFilterApartments = Apartment::select('apartments.*')

    ->selectRaw('( 6371 * acos( cos( radians(?) ) *
                          cos( radians( address_lat) )
                          * cos( radians( address_lon ) - radians(?)
                          ) + sin( radians(?) ) *
                          sin( radians( address_lat ) ) )
                        ) AS distance', [$lat, $lon, $lat])
    ->havingRaw("distance < ?", [$radius])
    ->orderBy('distance', 'ASC')->get();

    $apartments = [];
    foreach ($toFilterApartments as $toFilterApartment) {
      if (($toFilterApartment->rooms_number >= $rooms_number) && ($toFilterApartment->guests_number >= $guests_number) && ($toFilterApartment->visibility == 1)) {
        foreach ($toFilterApartment->sponsorships as $sponsorship) {
          $sponsorships[] = $sponsorship-> id;
        }
        if (!$servicesQuery == 0) {
          $servicesQuery = $request->get('services');

          $serviceFilter = [];
          foreach ($toFilterApartment->services as $x) {
            $serviceFilter[] = $x->id;
          }
          if (count(array_intersect($servicesQuery, $serviceFilter)) == count($servicesQuery)) {
            $apartments[] = $toFilterApartment;
          }
        } else {
          $apartments[] = $toFilterApartment;
        }
      }
    }
    return response() -> json(compact('apartments', 'services'));
  }
}

