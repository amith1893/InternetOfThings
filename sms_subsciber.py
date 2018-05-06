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
	account_sid = ""
	auth_token = ""
	
	client = Client(account_sid, auth_token)

	message = client.messages.create(
		to = "+",
		from_ = "+19389999211",
		body = "Alert - Intrusion detected at house!")
	
	print(message.sid)



subs = mqtt.Client("subscriber-3")
print("Connecting to broker")
#subs.connect("127.0.0.1:1883")
subs.connect("127.0.0.1")
print("Subscribing to lights topic")
subs.on_message = message_handler
subs.loop_start()
subs.subscribe("house/light")
time.sleep(1000)
