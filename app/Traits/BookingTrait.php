<?php
namespace App\Traits;

use App\Helper\Helper;
use App\Mail\TicketMailer;
use App\Mail\VehicleTicketMailer;
use App\Repositories\ApiLogReportRepository;
use App\Repositories\BookingRepository;
use App\Repositories\ImepayTransactionRepository;
use App\Repositories\PaymentDetailRepository;
use App\Repositories\PaymentMethodRepository;
use Illuminate\Support\Facades\Mail;
use PDF;
use SimpleXMLElement;
use SoapClient;
use Srmklive\PayPal\Services\ExpressCheckout;

trait BookingTrait
{
    public function performBooking($booking, $paymentMethod = '')
    {
        if (!$booking->is_booking_success) {

            if ($booking->currency == 'USD') {
                $init = $this->InitializeWebserviceUsd($booking->booking_track_id);
            } else {
                $init = $this->InitializeWebserviceNpr($booking->booking_track_id);
            }
            if (empty($booking->return_date) && $booking->trip_type = 'oneway') {
                $flightSegmentXml = new SimpleXMLElement('<Booking></Booking>');
                $flightSegmentHeader = $flightSegmentXml->addChild('Header');
                $flightSegmentHeader->addChild('adult', $booking->adult);
                $flightSegmentHeader->addChild('child', $booking->child);
                $flightSegmentHeader->addChild('infant', 0);
                $flightSegmentSegment = $flightSegmentXml->addChild('FlightSegment');
                $flightSegmentSegment->addChild('flight_id', $booking->flight_id);
                $flightSegmentSegment->addChild('fare_id', $booking->fare_id);
                $flightSegmentSegment->addChild('origin_rcd', $booking->origin_rcd);
                $flightSegmentSegment->addChild('destination_rcd', $booking->destination_rcd);
                $flightSegmentSegment->addChild('transit_flight_id', "");
                $flightSegmentSegment->addChild('transit_airport_rcd', "");
                $flightSegmentXML = $flightSegmentXml->asXML();


            } elseif (!empty($booking->return_date) && $booking->trip_type = 'round') {
                $flightSegmentXml = new SimpleXMLElement('<Booking></Booking>');
                $flightSegmentHeader = $flightSegmentXml->addChild('Header');
                $flightSegmentHeader->addChild('adult', $booking->adult);
                $flightSegmentHeader->addChild('child', $booking->child);
                $flightSegmentHeader->addChild('infant', 0);
                $flightSegmentSegment = $flightSegmentXml->addChild('FlightSegment');
                $flightSegmentSegmentReturn = $flightSegmentXml->addChild('FlightSegment');
                $flightSegmentSegment->addChild('flight_id', $booking->flight_id);
                $flightSegmentSegment->addChild('fare_id', $booking->fare_id);
                $flightSegmentSegment->addChild('origin_rcd', $booking->origin_rcd);
                $flightSegmentSegment->addChild('destination_rcd', $booking->destination_rcd);
                $flightSegmentSegment->addChild('transit_flight_id', "");
                $flightSegmentSegment->addChild('transit_airport_rcd', "");
                $flightSegmentSegmentReturn->addChild('flight_id', $booking->return_flight_id);
                $flightSegmentSegmentReturn->addChild('fare_id', $booking->return_fare_id);
                $flightSegmentSegmentReturn->addChild('origin_rcd', $booking->destination_rcd);
                $flightSegmentSegmentReturn->addChild('destination_rcd', $booking->origin_rcd);
                $flightSegmentSegmentReturn->addChild('transit_flight_id', "");
                $flightSegmentSegmentReturn->addChild('transit_airport_rcd', "");
                $flightSegmentXML = $flightSegmentXml->asXML();
            } else {
                echo 'not worked on multicity';
                exit;
            }
            $addFlightRequestXML = ['strXml' => $flightSegmentXML];
            $flightAddResult = $init->__soapCall('FlightAdd', array($addFlightRequestXML));

            $apiData = ['api_name' => 'FlightAdd',
                            'api_parameters' => json_encode($addFlightRequestXML),
                            'api_response' => null,
                            'uid' => $booking->booking_track_id
                            ];

            $this->apiLogReport->create($apiData);

            $flightAddResponse = $flightAddResult->FlightAddResult;
            $flightAddResponseXml = new SimpleXMLElement($flightAddResponse);
            // if add flight success
            if (trim($flightAddResponseXml->code) === '000') {
                $bookingGetSession = $init->__soapCall("BookingGetSession", array());
                $BookingSessionResult = $bookingGetSession->BookingGetSessionResult;
                $BookingSessionResultXML = simplexml_load_string($BookingSessionResult, "SimpleXMLElement", LIBXML_NOCDATA);
                $json = json_encode($BookingSessionResultXML);
                $BookingSessionResultXML = json_decode($json, TRUE);

                $apiData = ['api_name' => 'BookingGetSession',
                            'api_parameters' => null,
                            'api_response' => json_encode($BookingSessionResultXML),
                            'uid' => $booking->booking_track_id
                            ];
                $this->apiLogReport->create($apiData);

                $passengerData = new SimpleXMLElement('<Booking></Booking>');
                $i = 0;
                foreach ($booking->passengerAdults as $passengerAdult) {
                    $passenger = $passengerData->addChild('Passenger');
                    $passenger->addChild('title_rcd', $passengerAdult->prefix);
                    $passenger->addChild('lastname', $passengerAdult->last_name);
                    $passenger->addChild('firstname', $passengerAdult->first_name);
                    $passenger->addChild('middlename', $passengerAdult->middle_name);
                    $passenger->addChild('document_number', $passengerAdult->document_type . '/' . $passengerAdult->document_number);
                    $passenger->addChild('passenger_type_rcd', 'ADULT');
                    if (count($BookingSessionResultXML['Passenger']) == count($BookingSessionResultXML['Passenger'], COUNT_RECURSIVE)) {
                        $passenger->addChild('passenger_id', $BookingSessionResultXML['Passenger']['passenger_id']);
                        $passenger->addChild('booking_id', $BookingSessionResultXML['Passenger']['booking_id']);
                    } else {
                        $passenger->addChild('passenger_id', $BookingSessionResultXML['Passenger'][$i]['passenger_id']);
                        $passenger->addChild('booking_id', $BookingSessionResultXML['Passenger'][$i]['booking_id']);
                    }
                    $i++;
                }

                if ($booking->child > 0) {
                    foreach ($booking->passengerChilds as $passengerChild) {
                        $passenger = $passengerData->addChild('Passenger');
                        $passenger->addChild('title_rcd', $passengerChild->prefix);
                        $passenger->addChild('lastname', $passengerChild->last_name);
                        $passenger->addChild('firstname', $passengerChild->first_name);
                        $passenger->addChild('middlename', $passengerChild->middle_name);
                        $passenger->addChild('document_number', $passengerChild->document_type . '/' . $passengerChild->document_number);
                        $passenger->addChild('passenger_type_rcd', 'CHILD');
                        $passenger->addChild('passenger_id', $BookingSessionResultXML['Passenger'][$i]['passenger_id']);
                        $passenger->addChild('booking_id', $BookingSessionResultXML['Passenger'][$i]['booking_id']);
                        $i++;
                    }
                }
                $bookingHeader = $passengerData->addChild('BookingHeader');
                $bookingHeader->addChild('currency_rcd', $booking->currency);
                $bookingHeader->addChild('number_of_adults', $booking->adult);
                $bookingHeader->addChild('number_of_children', $booking->child);
                $bookingHeader->addChild('number_of_infants', 0);
                $bookingHeader->addChild('language_rcd', 'EN');
                $bookingHeader->addChild('contact_name', $booking->first_name . ' ' . $booking->middle_name . ' ' . $booking->last_name);
                $bookingHeader->addChild('email', $booking->email);
                $bookingHeader->addChild('phone_mobile', $booking->mobile);
                $bookingHeader->addChild('phone_home', $booking->phone);
                $bookingHeader->addChild('lastname', $booking->last_name);
                $bookingHeader->addChild('firstname', $booking->first_name);
                $bookingHeader->addChild('city', '');
                $bookingHeader->addChild('create_name', 'Website');
                $bookingHeader->addChild('country_rcd', $booking->nationality);

                $bookingHeader->addChild('address_line1', $booking->address);
                
                $payment = $passengerData->addChild('Payment');
                $payment->addChild('form_of_payment_rcd', 'CRAGT');
                //$payment->addChild('form_of_payment_rcd', 'CC');
                //$payment->addChild('form_of_payment_subype_rcd', $paymentMethod->slug);
                $payment->addChild('form_of_payment_subtype_rcd', 'VISA');
                $passengerInfoXml = $passengerData->asXML();
                $bookingSaveData = [
                    'strXml' => $passengerInfoXml
                ];
                $init->__soapCall("BookingSave", array($bookingSaveData));
                $apiData = ['api_name' => 'BookingSave',
                            'api_parameters' => json_encode($bookingSaveData),
                            'api_response' => null,
                            'uid' => $booking->booking_track_id
                            ];
                $this->apiLogReport->create($apiData);
                $getBookingSession = $init->__soapCall("BookingGetSession", array());
                $getBookingSession = $getBookingSession->BookingGetSessionResult;
                $BookingSuccessXml = new SimpleXMLElement($getBookingSession);
                if ((strlen($BookingSuccessXml->BookingHeader->record_locator) > 0) && ($BookingSuccessXml->Remark->complete_flag == 1)) {
                    $booking = $this->booking->update($booking->id,
                        [
                            'is_payment_success' => 1,
                            'is_booking_success' => 1,
                            'pnrno' => $BookingSuccessXml->BookingHeader->record_locator,
                            'booking_response' => json_encode(simplexml_load_string($getBookingSession)),
                        ]
                    );

                    $apiData = ['api_name' => 'BookingGetSession',
                                'api_parameters' => null,
                                'api_response' => json_encode(simplexml_load_string($getBookingSession)),
                                'uid' => $booking->booking_track_id
                                ];
                    $this->apiLogReport->create($apiData);

                    $fileName = 'uploads/ticket/Eticket ' . $booking->full_name. ' ' .$booking->booking_track_id . '.pdf';
                    view()->share(['booking' => $booking]);

                    $ticketTo = $this->siteSetting->findBy('key', 'ticket_to');
                    $ticketCc = $this->siteSetting->findBy('key', 'ticket_cc');
                    $ticketBcc = $this->siteSetting->findBy('key', 'ticket_bcc');

                    PDF::loadView('emails.ticket.ticket')->save($fileName);
                    Mail::send(new TicketMailer($booking, $fileName, $ticketTo, $ticketCc, $ticketBcc));

                    if(substr($booking->mobile,0,4) == '+977' && strlen(substr($booking->mobile, 4)) == '10')
                        Helper::sendSMS(substr($booking->mobile, 4), 'Your ticket has been booked. Your pnr no. '.$booking->pnrno);

                    return true;
                } else {
                    // booking fail payment pass //
                    $this->booking->update($booking->id,
                        [
                            'is_payment_success' => 1,
                            'is_booking_success' => 0,
                            'booking_response' => json_encode(simplexml_load_string($getBookingSession)),
                        ]
                    );
                    return false;
                }

            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public function performVehicle($booking)
    {
        $fileName = 'uploads/vehicle/' . $booking->booking_track_id . '.pdf';
        view()->share(['booking' => $booking]);
        PDF::loadView('emails.ticket.vehicle')->save($fileName);

        $ticketTo = $this->siteSetting->findBy('key', 'vehicle_to');
        $ticketCc = $this->siteSetting->findBy('key', 'vehicle_cc');
        $ticketBcc = $this->siteSetting->findBy('key', 'vehicle_bcc');

        Mail::send(new VehicleTicketMailer($booking, $fileName, $ticketTo, $ticketCc, $ticketBcc));
        return true;
    }

    public function InitializeWebserviceNpr($uid)
    {
        $initData = [
            'strAgencyCode' => env('NPR_AGENCY_CODE'),
            'strUserName' => env('NPR_USERNAME'),
            'strPassword' => env('NPR_PASSWORD'),
            'strLanguageCode' => 'EN'
        ];
        $client = new SoapClient(env('SOAP_API_URL'));
        $client->__soapCall("ServiceInitialize", array($initData));
        $apiData = ['api_name' => 'ServiceInitialize',
                    'api_parameters' => json_encode($initData),
                    'api_response' => null,
                    'uid' => $uid
                    ];

        $this->apiLogReport->create($apiData);
        return $client;
    }

    public function InitializeWebserviceUsd($uid)
    {
        $initData = [
            'strAgencyCode' => env('USD_AGENCY_CODE'),
            'strUserName' => env('USD_USERNAME'),
            'strPassword' => env('USD_PASSWORD'),
            'strLanguageCode' => 'EN'
        ];
        $client = new SoapClient(env('SOAP_API_URL'));
        $client->__soapCall("ServiceInitialize", array($initData));
        $apiData = ['api_name' => 'ServiceInitialize',
                    'api_parameters' => json_encode($initData),
                    'api_response' => null,
                    'uid' => $uid
                    ];
        $this->apiLogReport->create($apiData);
        return $client;
    }
}