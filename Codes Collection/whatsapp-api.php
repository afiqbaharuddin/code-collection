<?php

function sendWhatsapp($phoneNumber, $message) {
    // WhatsApp API credentials
    $accessToken   = 'EAAHFPS0Ode8BO7WTOUC0YREk4iWSdlXvbfqV6j1rqms53C9u2vZBZAKwb8yhQtrtKoCyGtJDegEpl1bgfo9YmmQnGeo2E0NAjKS3jdf8dZBpMZAMFq97bIlNAnwC3v0tSR7X4ip9QZADko2qwNJf0qQlH6rSA7EGwiJHzKkKcJ4WdnfPgwIFM44FQckMSqAFSPLZALHnrF';  // Replace with your actual Access Token
    $phoneNumberID = '409069472295999'; // Replace with your WhatsApp Business Phone Number ID

    // WhatsApp API URL
    $url = "https://graph.facebook.com/v17.0/$phoneNumberID/messages";

    // Prepare the message payload
    $data = [
    "messaging_product" => "whatsapp",
    "to"                => $phoneNumber,
    "type"              => "template",
    "template"          => [
        "name"              => "hello_world",
        "language"          => ["code" => "en_US"],
      ]
    ];


    // Send the POST request using cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $accessToken",
        "Content-Type: application/json"
    ]);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    $err = curl_error($ch);

    curl_close($ch);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        echo "Message sent successfully to $phoneNumber. Response: $response";
    }
}

function notifyUser($userId, $message, $phoneNumber) {
  if ($phoneNumber) {
    // Send the WhatsApp message using WhatsApp Business API
    sendWhatsapp($phoneNumber, $message);
  } else {
    echo "Phone number not found for user ID: $userId";
  }
}

  // // Example usage
  // $phoneNumber = "+60175894606";  // Replace with the actual user ID
  // $message     = "Hello! This is a message from the system.";
  // notifyUser($phoneNumber, $message);

 ?>
