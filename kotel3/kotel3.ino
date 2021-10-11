bool releMod = LOW;
byte tmin = 91;
byte tmax = 92;



// для работы с термометром 
#include <OneWire.h>
#include <DallasTemperature.h>
#define ONE_WIRE_BUS 5
OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensor(&oneWire);


// пины реле 
int relay1 = 13;
int relay4 = 27;

// debuger
bool debug = true;
String DebugData="";

// вайфай
#include <WiFi.h>
#include <WiFiClient.h>
#include "HTTPClient.h"
const char* ssid = "arduino";
const char* password = "123qweasdzxc";
String serverName =  "http://37.230.115.8/";

// таймклиент для получения точного времени 
#include <NTPClient.h>
#include <WiFiUdp.h>
WiFiUDP ntpUDP;
NTPClient timeClient(ntpUDP);
String getTime(NTPClient timeC){
  return String(timeC.getDay()) + " - " + String(timeC.getHours()) + " : " + String(timeC.getMinutes()) + " : "  + String(timeC.getSeconds()) + " : " ;
}
// json liba 
#include <ArduinoJson.h>
void setup(){
  Serial.begin(115200); 
  
  WiFi.mode(WIFI_STA);  
  WiFi.begin(ssid, password);         
  
  timeClient.begin();

  
  pinMode(relay1, OUTPUT);   
  pinMode(relay4, OUTPUT);    
  
 
  sensor.begin();
  // устанавливаем разрешение датчика от 9 до 12 бит
  sensor.setResolution(12);
  
}
// для того чтоб не выводить сообщение о подключении постоянно и чтоб не блочить работу если нет вайфая 
bool wifiConected = false;
String str = "";

void loop(){
  // только вывод о подключении вайфая 
  if (WiFi.status() == WL_CONNECTED and !wifiConected) {
    wifiConected = true;
    Serial.print("Connected to "); 
    Serial.println(ssid);  
    Serial.print("IP address: "); 
    Serial.println(WiFi.localIP());
  }
   
  // переменная для хранения температуры
  float temperature;
  // отправляем запрос на измерение температуры
  sensor.requestTemperatures();
  // считываем данные из регистра датчика
  temperature = sensor.getTempCByIndex(0);

  if (temperature  > tmax) {
    releMod = LOW ;              
  }
   if (temperature < tmin) {  
    releMod = HIGH;        
  }
  
  digitalWrite(relay1, releMod); 
  digitalWrite(relay4, releMod); 

  Serial.print("Temp C: ");
  Serial.println(temperature);
  timeClient.setTimeOffset(3600); 
  timeClient.update();   
  str += "{\"t\": {\"t1\":" + String(temperature) + "},\"r\": {\"n\": "+String(releMod)+", \"p\": false},\"time\":\"" + timeClient.getFormattedTime() + "\"},";   

  if(WiFi.status()== WL_CONNECTED){
     // Serial.println("отправка на сервер"); 
     // Serial.println(serverName);   
      HTTPClient http;           
      
      http.begin(serverName+"input.php");      
      http.addHeader("Content-Type", "application/json");            
      int httpResponseCode = http.POST("[" + str.substring(0, str.length()-1) + "]");                
      http.end();
            
      delay(100);
      
      DebugData+= "tmax tmin debug " + String(tmin) + " "+ String(tmax) + " "+ String(debug) + " "+ '\n';
      
      http.begin(serverName+"termComand.php");
      int httpCode = 0;
      if (debug == false) {
        httpCode = http.POST("");
      }
      else {
        httpCode = http.POST(DebugData);
      }
      Serial.println("tmax tmin debug " + String(tmin) + " "+ String(tmax) + " "+ String(debug) + " "); 
      
      if (httpCode > 0) {
        if (httpCode == HTTP_CODE_OK) {
          String payload = http.getString();
          Serial.println("payload " + payload); 
          StaticJsonDocument<200> doc;
          deserializeJson(doc, payload);
          
          debug = doc["debug"];
          tmax = doc["tmax"];
          tmin = doc["tmin"];    
          Serial.println("payloadJson " + String(debug) + " " + tmax + " " + tmin ); 
          DebugData ="";
        }
      }
      
      http.end();            
      str = "";      
    }
    else {
      Serial.println("WiFi Disconnected");
      DebugData+="WiFi Disconnected" + '\n';
    }
  delay(10000);
}
