#include <SPI.h>
#include <MFRC522.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>

#define SS_PIN 4 // D2 -> GPIO4
#define RST_PIN 5 // D1 -> GPIO5
#define PULLUP_SENSOR_PIN 0 // D3 -> GPIO0

const char* ssid = "asemelehhhhhh";
const char* password = "........";

MFRC522 mfrc522(SS_PIN, RST_PIN);
volatile bool sensorTriggered = false;
volatile int pullUpCount = 0;

void IRAM_ATTR countPullUp() {
  sensorTriggered = true;
}

void setup() {
  Serial.begin(115200);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Connecting to WiFi...");
  }
  Serial.println("Connected to WiFi");

  SPI.begin();
  mfrc522.PCD_Init();
  pinMode(PULLUP_SENSOR_PIN, INPUT);
  attachInterrupt(digitalPinToInterrupt(PULLUP_SENSOR_PIN), countPullUp, FALLING);

  Serial.println("Place your RFID card on the reader...");
}

void loop() {
  if (sensorTriggered) {
    pullUpCount++;
    sensorTriggered = false;

    // Check if a new card is present
    if (mfrc522.PICC_IsNewCardPresent() && mfrc522.PICC_ReadCardSerial()) {
      String uidStr = "";
      for (byte i = 0; i < mfrc522.uid.size; i++) {
        uidStr += String(mfrc522.uid.uidByte[i], HEX);
      }

      // Output RFID read message to Serial Monitor
      Serial.print("RFID terbaca: ");
      Serial.println(uidStr);

      // Send data to server
      if (WiFi.status() == WL_CONNECTED) {
        WiFiClient client;
        HTTPClient http;
        http.begin(client, "http://172.20.10.4/samapta/pullup.php");
        http.addHeader("Content-Type", "application/x-www-form-urlencoded");

        String postData = "uid=" + uidStr + "&pullup_count=" + String(pullUpCount);
        int httpResponseCode = http.POST(postData);

        if (httpResponseCode > 0) {
          String response = http.getString();
          Serial.println(httpResponseCode);
          Serial.println(response);
        } else {
          Serial.print("Error on sending POST: ");
          Serial.println(httpResponseCode);
        }
        http.end();
      } else {
        Serial.println("WiFi not connected");
      }
      
      delay(1000); // Debounce delay
    }
  }
}
