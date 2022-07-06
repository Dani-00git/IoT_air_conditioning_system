#include "DHT.h"
#include <SPI.h>
#include <Ethernet.h>

byte mac[] = { 0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED }; 

#define DHTTYPE DHT11

float hum;
float temp;
int DELAY=900000;

char server[] = "192.168.0.250";
IPAddress ip(192,168,0,177); 
EthernetClient client; 

void setup() {
  Serial.begin(9600);
  if (Ethernet.begin(mac) == 0) {
    Serial.println("Failed to configure Ethernet using DHCP");
    Ethernet.begin(mac, ip);
  }
  delay(1000);
}

void loop(){
  for(int i=22; i<=41; i++){
    DHT dht(i, DHTTYPE);
    dht.begin();
    hum = dht.readHumidity();
    temp = dht.readTemperature(); 
    if(!isnan(temp)){
      send_to_db(i, temp, hum); 
    }
  }
  delay(DELAY); 
}

void send_to_db(int DHTPIN, float temp, float hum){   
  if (client.connect(server, 8888)) {
    Serial.println("connected");
    // Make a HTTP request:
    Serial.print("GET /elaborato_PPM/uploadData.php?humidity=");
    client.print("GET /elaborato_PPM/uploadData.php?humidity=");
    Serial.print(hum);
    client.print(hum);
    client.print("&temperature=");
    Serial.print("&temperature=");
    client.print(temp);
    Serial.println(temp);
    client.print("&ID=");
    Serial.print("&ID=");
    client.print(DHTPIN);
    Serial.println(DHTPIN);
    client.print(" ");      
    client.print("HTTP/1.1");
    client.println();
    client.println("Host: 192.168.0.250");
    client.println("Connection: close");
    client.println();
  } else {
    // if you didn't get a connection to the server:
    Serial.println("connection failed");
  }
}
