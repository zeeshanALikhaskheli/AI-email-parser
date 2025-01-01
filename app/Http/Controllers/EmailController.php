<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Webklex\IMAP\Facades\Client;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use GeminiAPI\Laravel\Facades\Gemini;


class EmailController extends Controller
{
    public function fetchEmails()
    {
        try {
            // Connect to the IMAP server
            $client = Client::account('default');
            $client->connect();
    
            // Access the inbox folder
            $folder = $client->getFolder('INBOX');
    
            // Fetch messages sorted by date (desc) and limit to 5
            $messages = $folder->query()
                ->since(now()->subDays(4)) // Fetch emails from the last 4 days
                ->unseen()
                ->limit(5)
                ->setFetchOrder("desc")
                ->get();
    
            $emails = [];
            foreach ($messages as $message) {
                $uid = $message->getUid(); // Fetch the IMAP UID
    
                // Check if the email already exists in the database
                $existingEmail = \App\Models\Email::where('uid', $uid)->first();
                if ($existingEmail) {
                    continue; // Skip saving if the email UID already exists
                }
    
                $body = $message->getTextBody();
    
                // Process the email body with Gemini AI NLP
                $logisticsData = $this->getNLPData($body);
    
                // Save the email to the database
                \App\Models\Email::create([
                    'uid'            => $uid,
                    'subject'        => $message->getSubject() ?? 'No Subject',
                    'from'           => $message->getFrom()[0]->mail ?? 'Unknown Sender',
                    'body'           => $body,
                    'logistics_data' => json_encode($logisticsData, JSON_UNESCAPED_SLASHES),
                    'date'           => $message->getDate(),
                ]);
            }
    
            // Retrieve stored emails from the database in descending order (latest first)
            $storedEmails = \App\Models\Email::orderBy('created_at', 'desc')->get();
            
            // Count the total number of emails in the database
            $totalEmails = \App\Models\Email::count();
    
            // Return the sorted emails and the total email count to the view
            return view('emails.index', [
                'emails' => $storedEmails,
                'totalEmails' => $totalEmails
            ]);
    
        } catch (\Exception $e) {
            Log::error('Error fetching emails: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch emails: ' . $e->getMessage()], 500);
        }
    }
    
    // Function to process email body through Gemini API for logistics data extraction
    private function getNLPData($text)
    {
        try {
            // Use the Gemini API to process the email body for logistics data
            $response = Gemini::generateText("
            You are a data extraction assistant. Please extract the logistics-related data from the provided email body. 
            If a specific field is missing, write 'This field is missing or not found.' 
            Follow these rules:
            1. Only extract logistics-related information.
            2.dont include any tag return response as a josn formet
            2. Format the extracted data in a structured manner, as follows:
               - Request Type: [request_type]
               - Transport Mode: [transport_mode]
               - Container Type: [container_type]
               - Cargo Weight (kg): [cargo_weight_kg]
               - Cargo Type: [cargo_type]
               - Origin: [origin]
               - Destination: [destination]
               - Additional Requirements: [additional_requirements]
               - Sender Name: [Sender Name]
               - Receiver Name: [Receiver Name]
               - Address: [Address]
               - Shipment ID: [Shipment ID]
               - Tracking Number: [Tracking Number]
               - Date: [Date]
               - Time: [Time]
               - Additional Notes: [Additional Notes]
            
            Here is the email content:
            $text
            ");
    
            // Log the raw response to inspect its structure
            Log::info('Gemini API Raw Response: ' . print_r($response, true));
    
            // Check if the response is an array or object
            if (is_array($response) || is_object($response)) {
                // Access logistics data only if it exists
                if (isset($response->data->logistics)) {
                    // Return logistics data as an associative array
                    return [
                        'request_type' => $response->data->logistics->request_type ?? 'This field is missing or not found.',
                        'transport_mode' => $response->data->logistics->transport_mode ?? 'This field is missing or not found.',
                        'container_type' => $response->data->logistics->container_type ?? 'This field is missing or not found.',
                        'cargo_weight_kg' => $response->data->logistics->cargo_weight_kg ?? 'This field is missing or not found.',
                        'cargo_type' => $response->data->logistics->cargo_type ?? 'This field is missing or not found.',
                        'origin' => $response->data->logistics->origin ?? 'This field is missing or not found.',
                        'destination' => $response->data->logistics->destination ?? 'This field is missing or not found.',
                        'additional_requirements' => $response->data->logistics->additional_requirements ?? 'This field is missing or not found.',
                        'sender_name' => $response->data->logistics->sender_name ?? 'This field is missing or not found.',
                        'receiver_name' => $response->data->logistics->receiver_name ?? 'This field is missing or not found.',
                        'address' => $response->data->logistics->address ?? 'This field is missing or not found.',
                        'shipment_id' => $response->data->logistics->shipment_id ?? 'This field is missing or not found.',
                        'tracking_number' => $response->data->logistics->tracking_number ?? 'This field is missing or not found.',
                        'date' => $response->data->logistics->date ?? 'This field is missing or not found.',
                        'time' => $response->data->logistics->time ?? 'This field is missing or not found.',
                        'additional_notes' => $response->data->logistics->additional_notes ?? 'This field is missing or not found.',
                    ];
                }
                return 'No logistics data found.';
            }
    
            // If response is a string, log it and return as error
            if (is_string($response)) {
                Log::error('Gemini API Error Response: ' . $response);
                return  $response;
            }
    
            // Handle any other unexpected response type
            return 'Error: Invalid response format from Gemini API.';
    
        } catch (\Exception $e) {
            // Log any exception encountered while interacting with the Gemini API
            Log::error('Error processing email with Gemini API: ' . $e->getMessage());
            return 'Error processing email with Gemini API: ' . $e->getMessage();
        }
    }
    

}