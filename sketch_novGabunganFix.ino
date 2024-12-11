#include <WiFi.h>
#include <HTTPClient.h>
#include <HardwareSerial.h>
#include <LiquidCrystal_I2C.h>

// **Wi-Fi Configuration**
const char* ssid = "AndroidAP21ac";
const char* password = "123456799";

// **Server Configuration**
String URL = "http://192.168.43.131/Toollink/save_qr.php";

// **Serial for GM66**
HardwareSerial myserial(1); // Menggunakan Serial1 pada ESP32

// **LCD Configuration**
int lcdColumns = 16;
int lcdRows = 2;
LiquidCrystal_I2C lcd(0x27, lcdColumns, lcdRows);

// **Variables**
String qrCodeData = ""; // Menyimpan data QR code yang diterima

void setup() {
  Serial.begin(9600); // Serial Monitor untuk debugging
  myserial.begin(9600, SERIAL_8N1, 16, 17); // RX=16, TX=17 untuk GM66
  myserial.setTimeout(100); // Timeout untuk membaca data
  
  lcd.init();
  lcd.backlight();
  lcd.setCursor(0, 0);
  lcd.print("ESP32 Siap");
  delay(1000);
  lcd.clear();

  connectWiFi(); // Sambungkan ke Wi-Fi
}

void loop() {
  if (WiFi.status() != WL_CONNECTED) {
    connectWiFi(); // Reconnect jika Wi-Fi terputus
  }

  // Membaca data dari GM66
  while (myserial.available() > 0) {
    char incomingByte = myserial.read();
    qrCodeData += incomingByte;

    // Jika data QR code selesai (newline '\n' ditemukan)
    if (incomingByte == '\n') {
      // Mengirimkan data ke server
      sendDataToServer(qrCodeData);
      
      // Reset qrCodeData untuk pemindaian berikutnya
      qrCodeData = "";
    }
  }
}

// Fungsi untuk mengirim data ke server
void sendDataToServer(String data) {
  HTTPClient http;
  String postData = "data=" + data; // Mengirim semua data dalam satu parameter 'data'

  http.begin(URL);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");

  int httpCode = http.POST(postData);
  String payload = http.getString();

  Serial.print("HTTP Code: ");
  Serial.println(httpCode);
  Serial.print("Response Payload: ");
  Serial.println(payload);

  http.end();
}

// Fungsi untuk menyambungkan ke Wi-Fi
void connectWiFi() {
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);

  Serial.println("Menghubungkan ke WiFi...");
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Menghubungkan...");
  
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
    lcd.setCursor(0, 1);
    lcd.print(".");
  }

  Serial.println("\nWiFi Terhubung!");
  Serial.print("Alamat IP: ");
  Serial.println(WiFi.localIP());
  
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("WiFi Terhubung");
  lcd.setCursor(0, 1);
  lcd.print(WiFi.localIP());
  delay(2000);
  lcd.clear();
}
