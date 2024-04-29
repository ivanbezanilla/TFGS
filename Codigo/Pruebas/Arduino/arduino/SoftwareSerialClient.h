#include <Arduino.h>
#include <SoftwareSerial.h>
#include <Stream.h>

class SoftwareSerialClient : public Stream {
public:
    SoftwareSerialClient(SoftwareSerial& serial) : serial(serial) {}

    int available() override {
        return serial.available();
    }

    int read() override {
        return serial.read();
    }

    int peek() override {
        return serial.peek();
    }

    size_t write(uint8_t byte) override {
        return serial.write(byte);
    }

    void flush() override {
        serial.flush();
    }

    size_t write(const uint8_t* buffer, size_t size) override {
        return serial.write(buffer, size);
    }

private:
    SoftwareSerial& serial;
};
