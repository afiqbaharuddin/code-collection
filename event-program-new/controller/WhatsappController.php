<?php

function sendWhatsapp($pax_id, $phoneNumber){

  echo "Sending WhatsApp message with pax_id: $pax_id<br>";

  // Construct the URL with the ID
  $url = "http://synergy_enhanced.test/event-program/register.php?id=$pax_id";

  // Print the URL for debugging
  echo "Generated URL: " . $url . "<br>";

  $curl = curl_init();

  curl_setopt_array($curl, [
    CURLOPT_URL            => "https://live-mt-server.wati.io/351580/api/v1/sendTemplateMessage?whatsappNumber=$phoneNumber",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING       => "",
    CURLOPT_MAXREDIRS      => 10,
    CURLOPT_TIMEOUT        => 30,
    CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST  => "POST",

    CURLOPT_POSTFIELDS => json_encode([
      "template_name"  => "event_qrcode",
      "broadcast_name" => "event_qrcode_message",
      "parameters"     => [
          [
            "name"  => "string",
            "value" => "string"
          ],
          [
            "name"  => "url",
            "value" => "http://synergy_enhanced.test/event-program/register.php?id=$pax_id"
          ]
        ]
    ]),

    CURLOPT_HTTPHEADER => [
      "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiIxMDhhZmNmOS1jNmI0LTQyMDYtOTNlZC1kYjFhMGNmMmVjYzAiLCJ1bmlxdWVfbmFtZSI6Im1hcmtldGluZ0BzeW5lcmd5LWdyb3VwLmNvbS5teSIsIm5hbWVpZCI6Im1hcmtldGluZ0BzeW5lcmd5LWdyb3VwLmNvbS5teSIsImVtYWlsIjoibWFya2V0aW5nQHN5bmVyZ3ktZ3JvdXAuY29tLm15IiwiYXV0aF90aW1lIjoiMTAvMjIvMjAyNCAwNDoyMjoyOCIsInRlbmFudF9pZCI6IjM1MTU4MCIsImRiX25hbWUiOiJtdC1wcm9kLVRlbmFudHMiLCJodHRwOi8vc2NoZW1hcy5taWNyb3NvZnQuY29tL3dzLzIwMDgvMDYvaWRlbnRpdHkvY2xhaW1zL3JvbGUiOiJBRE1JTklTVFJBVE9SIiwiZXhwIjoyNTM0MDIzMDA4MDAsImlzcyI6IkNsYXJlX0FJIiwiYXVkIjoiQ2xhcmVfQUkifQ.LW3oY_0iduANy9bKJScYZcE1uuN1FHy-r_F2L36sIIg",
      "content-type: application/json-patch+json"
    ],
  ]);

  $response = curl_exec($curl);
  $err      = curl_error($curl);

  curl_close($curl);

  if ($err) {
    echo "cURL Error #:" . $err;
  } else {
    echo $response;
  }
}

function notifyUser($userId, $phoneNumber) {
  if ($phoneNumber) {
    // Send the WhatsApp message using WhatsApp Business API
    sendWhatsapp($userId, $phoneNumber);  // Pass $userId as the pax_id
  } else {
    echo "Phone number not found for user ID: $userId";
  }
}
