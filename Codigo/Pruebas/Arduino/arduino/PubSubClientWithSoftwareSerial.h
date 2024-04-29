#include "PubSubClient.h"
#include <SoftwareSerial.h>
#include "SoftwareSerialClient.h" // Incluimos nuestra clase adaptadora

class PubSubClientWithSoftwareSerial : public PubSubClient {
public:
    PubSubClientWithSoftwareSerial(SoftwareSerial& serial) : client(serial) {}

    using PubSubClient::setServer;
    using PubSubClient::setCallback;
    using PubSubClient::loop;
    using PubSubClient::connected;
    using PubSubClient::subscribe;
    using PubSubClient::publish;

private:
    SoftwareSerialClient client;
};
