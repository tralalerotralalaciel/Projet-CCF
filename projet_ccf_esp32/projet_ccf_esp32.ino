#include <WiFi.h>
#include <HTTPClient.h>

const char ssid = ""
const char password = ""

// const char serverName = ""

void setup() {
  Serial.begin(115200);
  
  WiFi.begin(ssid, password);
  Serial.println("Connecting");

  // Pendant la connexion WiFi
  while(WiFi.status() != WL_CONNECTED){
    delay(500);
    Serial.print(".");
  }

  Serial.print("\nConnected to WiFi network with IP Address : " + WiFi.localIP());
}

void loop() {

}
