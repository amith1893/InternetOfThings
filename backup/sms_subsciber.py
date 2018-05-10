from twilio.rest import Client
import paho.mqtt.client as mqtt
import time

def message_handler(client, userdata, message):
	print("Message received ", str(message.payload.decode("utf-8")))
	print("Message topic ", message.topic)
	print("Message QOS ", message.qos)
	print("Message retain flag ", message.retain)
	command = str(message.payload.decode("utf-8"))
	
	if command == "Turn on":
		send_sms()

def send_sms():
	account_sid = "AC5b9818960edf481a6bbfcc2416ccb324"
	auth_token = "4d1627c8154a9c14c43c38f52575861a"
	
	client = Client(account_sid, auth_token)

	message = client.messages.create(
		to = "+17204924454",
		from_ = "+19389999211",
		body = "Alert - Intrusion detected at house!")
	
	print(message.sid)



subs = mqtt.Client("sms-subscriber")
print("Connecting to broker")
#subs.connect("127.0.0.1:1883")
subs.connect("127.0.0.1")
print("Subscribing to SMS topic")
subs.on_message = message_handler
subs.loop_start()
subs.subscribe("house/sms")
time.sleep(1000)
