from twilio.rest import Client

# Your Account SID from twilio.com/console
account_sid = "AC5b9818960edf481a6bbfcc2416ccb324"
# Your Auth Token from twilio.com/console
auth_token  = "4d1627c8154a9c14c43c38f52575861a"

client = Client(account_sid, auth_token)

message = client.messages.create(
    to="+17204924454", 
    from_="+19389999211 ",
    body="Alert - Intrusion detected at house!")

print(message.sid)
