bool releMod = LOW;
byte tmin = 91;
byte tmax = 92;

// софт перезагрука
void(* resetFunc) (void) = 0;

// display 
#define CLK 22
#define DIO 21
#include <TM1637Display.h>
TM1637Display display(CLK, DIO);

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
#define LED 2

// вайфай
#include <WiFi.h>
#include <WiFiClient.h>
#include "HTTPClient.h"
const char* ssid = "arduino";
const char* password = "123qweasdzxc";
unsigned long previousMillis = 0;
unsigned long interval = 30000;
// const char* ssid = "linux";
// const char* password = "123qweasdzxc123";

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
  pinMode(LED,OUTPUT);
  initWiFi();
  Serial.print("RSSI: ");
  Serial.println(WiFi.RSSI());

  timeClient.begin();

  pinMode(relay1, OUTPUT);   
  pinMode(relay4, OUTPUT);    
   
  sensor.begin();
    
  display.setBrightness(0x0f);
}

String str = "";

void loop(){
 
  uint8_t data[] = { 0xff, 0xff, 0xff, 0xff };
  uint8_t blank[] = { 0x00, 0x00, 0x00, 0x00 };
  display.setSegments(data);

  unsigned long currentMillis = millis();
    // if WiFi is down, try reconnecting
  if ((WiFi.status() != WL_CONNECTED) && (currentMillis - previousMillis >=interval)) {
    Serial.print(millis());
    Serial.println("Reconnecting to WiFi...");
    WiFi.disconnect();
    WiFi.reconnect();
    previousMillis = currentMillis;
    errorLed(3);     
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
  digitalWrite(relay4, not releMod); 

  Serial.print("Temp C: ");
  Serial.println(temperature);
//  int integer_temperature1  =  temperature * 100 ;  
 
 
  display.showNumberDec(ceil(temperature), false);
  

  timeClient.setTimeOffset(10800);// 60*60*3 
  timeClient.update();   
  str += "{\"t\": {\"t1\":" + String(temperature) + "},\"r\": {\"n\": "+String(releMod)+", \"p\": false},\"time\":\"" + timeClient.getDay() +"-"+ timeClient.getFormattedTime() + "\"},";   

  if(WiFi.status()== WL_CONNECTED){     
      HTTPClient http;           
      

      http.begin(serverName+"input.php");      
      http.addHeader("Content-Type", "application/json");            
      int httpResponseCode = http.POST("[" + str.substring(0, str.length()-1) + "]");       
      if (httpResponseCode != HTTP_CODE_OK) {   
          errorLed(6);          
      }      
      http.end();            
      delay(100);
      



    // установка температуры из тг 
      DebugData+= "tmax tmin debug " + String(tmin) + " "+ String(tmax) +  " температура "  + String(temperature)+" "+  '\n';
      
      http.begin(serverName+"termComand.php");
      int httpCode = 0;
      if (debug == false) {
        httpCode = http.POST("");
      }
      else {
        httpCode = http.POST(DebugData);
      }
      Serial.println("tmax tmin debug " + String(tmin) + " "+ String(tmax) + " "+ String(debug) + " "); 
            
      if (httpCode == HTTP_CODE_OK) {
        String payload = http.getString();
        Serial.println("payload " + payload); 
        StaticJsonDocument<200> doc;
        deserializeJson(doc, payload);
        
        debug = doc["debug"];
        if (debug){
          tmax = doc["tmax"];
          tmin = doc["tmin"];    
        }
        
        Serial.println("payloadJson " + String(debug) + " " + tmax + " " + tmin ); 
        DebugData ="";
      }
       
      Serial.println(httpCode);
      http.end();            
      str = "";      
    }
    else {
      Serial.println("WiFi Disconnected");
      DebugData+="WiFi Disconnected" + '\n';
    }

  delay(10000);
  display.clear();
}

void initWiFi() {
  int counter = 0;
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);
  Serial.print("Connecting to WiFi ..");
  while (WiFi.status() != WL_CONNECTED) {
    Serial.print('.');
    errorLed(3);
    counter += 1;
    if (counter > 5) {
      Serial.println("resetting");
      resetFunc();      
    }
  }
  Serial.println(WiFi.localIP());
}

void errorLed(int type) {
  int row[1024];  
  if (type == 1) {
    row[0] = 500;
    row[1] = 500;
    row[2] = 1000;
    row[3] = 0;
    signalError(row);
  }
  if (type == 2) {
    row[0] = 250;
    row[1] = 250;
    row[2] = 250;
    row[3] = 250;
    row[4] = 1000;
    row[5] = 0;
      signalError(row);
  } 
  if (type == 3) {
    row[0] = 500;
    row[1] = 500;
    row[2] = 500;
    row[3] = 500;
    row[4] = 500;
    row[5] = 500;
    row[6] = 2000;
    row[7] = 0;
      signalError(row);
  } 

  if (type == 4) {
    row[0] = 100;
    row[1] = 100;
    row[2] = 500;
    row[3] = 0;
      signalError(row);
  }
  if (type == 5) {
    row[0] = 100;
    row[1] = 100;
    row[2] = 100;
    row[3] = 100;
    row[4] = 500;
    row[5] = 0;
      signalError(row);
  }
  if (type == 6) {
    for (int i = 0; i <= 124; i++) {
      row[i] = 100;           
    } 
    row[122] = 500;
    row[123] = 1000;
    signalError(row);
  }
}

void signalError( int row[] ) {
  // последовательность как моргать светодиодом   
  bool boof = false;
   for (int i = 0; i <= 124; i++) {
    if (row[i] == 0) {    
       break;
    }    
    boof = !boof;
    digitalWrite(LED, boof);    
    delay(row[i]);        
  } 
   digitalWrite(LED, LOW); 
}
