<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\AppointmentRepositoryInterface;

date_default_timezone_set('Europe/London');

class AppointmentController extends Controller
{

    private $appointment;
    private $icebergEstatesLatitude;
    private $icebergEstatesLongitude;
    private $googleMapsKey;


    public function __construct(AppointmentRepositoryInterface $appointment)
    {
        $this->appointment = $appointment;
        $this->icebergEstatesLatitude = 51.729157; //Iceberg Estates Latitude (cm27pj)
        $this->icebergEstatesLongitude = 0.478027; //Iceberg Estates Longitude (cm27pj)
        $this->googleMapsKey; //Google Maps Directions API Key
    }


    public function allAppointments()
    {
        return response()->json([
            $this->appointment->all()
        ]);
    }

    public function get_http_response_code($url)
    {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }

    public function createAppointment(Request $request)
    {
        $user = $this->appointment->findUser($request->user_id);

        if ($this->get_http_response_code('https://api.postcodes.io/postcodes/' . $request->postcode) == 404) { //If Postcode API response HTTP 404 Not Found Error
            return response()->json([
                'status' => 404,
                'error' => 'Invalid postcode',
            ], 404);
        } else {

            $postCodeService = json_decode(file_get_contents('https://api.postcodes.io/postcodes/' . $request->postcode), true); //Postcode API

            $googleMapsService = json_decode(file_get_contents( //Google Maps API
                'https://maps.googleapis.com/maps/api/directions/json' .
                    '?origin=' . $this->icebergEstatesLatitude . ',' .  $this->icebergEstatesLongitude .
                    '&destination=' . $postCodeService['result']['latitude'] . ',' . $postCodeService['result']['longitude'] .
                    '&mode=driving' .
                    '&key=' . $this->googleMapsKey
            ), true);

            $arrivalValue = strtotime($request->departure) + $googleMapsService['routes'][0]['legs'][0]['duration']['value'] + 3600; //Arrival = Departure + Distance Duration + 1 hour (type of seconds)
            $arrival = date('Y/m/d H:i', $arrivalValue); //Arrivale (Datetime)


            if ((strtotime($arrival) - strtotime($user->arrival)) / 60 > 0 && (strtotime($request->departure) - strtotime($user->arrival)) / 60 > 0) { //If User is not busy

                $data =  $this->appointment->create([
                    'user_id' => $request->user_id,
                    'contact_id' => $request->contact_id,
                    'postcode' => $request->postcode,
                    'address' => $request->address,
                    'latitude' => $postCodeService['result']['latitude'],
                    'longitude' => $postCodeService['result']['longitude'],
                    'distance' => (float) explode(' ', $googleMapsService['routes'][0]['legs'][0]['distance']['text'])[0],
                    'departure' => date('Y/m/d H:i', strtotime($request->departure)),
                    'arrival' => $arrival
                ]);

                return response()->json([
                    'status' => 201,
                    'meessage' => 'Appointment succesfully created.',
                    'data' => $data,
                ], 201);
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'The user is busy!'
                ], 401);
            }
        }
    }

    public function showAppointment(Request $request)
    {
        if ($this->get_http_response_code('https://api.postcodes.io/postcodes/' . $request->postcode) == 404) {
            return response()->json([
                'status' => 404,
                'error' => 'Invalid postcode',
            ], 404);
        } else if ($request->postcode == 'cm27pj') {
            $postCodeService = json_decode(file_get_contents('https://api.postcodes.io/postcodes/' . $request->postcode), true);

            return response()->json([
                'data' => $postCodeService,
            ], 200);
        } else if (json_decode(file_get_contents('https://api.postcodes.io/postcodes/' . $request->postcode), true)) {

            $data = $this->appointment->findPostCode($request->postcode);
            $postCodeService = json_decode(file_get_contents('https://api.postcodes.io/postcodes/' . $request->postcode), true);

            if (json_decode($data, true)) {
                return response()->json(
                    [
                        'data' => $data,
                        'postCodeService' => $postCodeService
                    ],
                    200
                );
            } else {
                return response()->json(
                    [
                        'message' => 'No such postcode is registered.',
                        'postCodeService' => $postCodeService,
                    ],
                    401
                );
            }
        }
    }


    public function deleteAppointment($id)
    {
        $appointment = $this->appointment->find($id);

        if ($appointment != null) {

            $deleted = $this->appointment->delete($id);

            return response()->json([
                'message' => 'This appointment succesfully deleted.',
                'deletedData' =>  $appointment
            ], 201);
        } else {
            return response()->json(
                [
                    'message' => 'No such appointment was created.',
                ],
                401
            );
        }
    }
}
