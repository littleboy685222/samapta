#include <SPI.h>
#include <MFRC522.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>

#define RST_PIN   5   // Define GPIO5 (D1) for RST_PIN
#define SS_PIN    4   // Define GPIO4 (D2) for SS_PIN

const char* ssid     = "KADET MAHASISWA"; // WiFi SSID
const char* password = "";                // WiFi password
const char* serverName = "http://10.3.163.250/samapta-copy/edit.php"; // Server PHP endpoint for editing data

MFRC522 mfrc522(SS_PIN, RST_PIN); // Create MFRC522 instance

void setup() {
  Serial.begin(115200); // Initialize serial communication
  SPI.begin();           // Init SPI bus
  mfrc522.PCD_Init();    // Init MFRC522

  WiFi.begin(ssid, password); // Connect to WiFi

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.println("Connecting to WiFi...");
  }

  Serial.println("Connected to WiFi");
}

void loop() {
  // Look for new cards
  if (!mfrc522.PICC_IsNewCardPresent() || !mfrc522.PICC_ReadCardSerial()) {
    delay(500);
    Serial.println("No card present or card read failed");
    return;
  }

  // Read UID
  String uid = "";
  for (byte i = 0; i < mfrc522.uid.size; i++) {
    uid += String(mfrc522.uid.uidByte[i] < 0x10 ? "0" : "");
    uid += String(mfrc522.uid.uidByte[i], HEX);
  }
  uid.toUpperCase(); // Convert UID to uppercase

  Serial.print("UID tag : ");
  Serial.println(uid);

  sendData(uid); // Send UID to server

  delay(500); // Delay to avoid multiple reads
}

void sendData(String uid) {
  if (WiFi.status() == WL_CONNECTED) {
    WiFiClient client;
    HTTPClient http;

    // Specify the id of the cadet you want to update
    String id = "1"; // Change this to the correct cadet ID

    // Construct URL with UID and ID parameter
    String url = String(serverName) + "?id=" + id + "&uid=" + uid;

    Serial.print("Connecting to server: ");
    Serial.println(url);

    http.begin(client, url); // Begin HTTP connection
    int httpResponseCode = http.GET(); // Send GET request

    if (httpResponseCode > 0) {
      String response = http.getString(); // Get response
      Serial.print("HTTP Response code: ");
      Serial.println(httpResponseCode);
      Serial.println("Response from server: ");
      Serial.println(response);
    } else {
      Serial.print("Error code: ");
      Serial.println(httpResponseCode);
    }

    http.end(); // Close connection
  } else {
    Serial.println("WiFi Disconnected");
  }
}
