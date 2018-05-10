import time
import RPi.GPIO as GPIO




def sense():

	GPIO.setmode(GPIO.BCM)

	GPIO.setup(7, GPIO.IN) #PIR SENSOR

	try:
    		time.sleep(2)
    		while True:
        		print(GPIO.input(7))
        		if GPIO.input(7):
            			print("MOTION DETECTED....YAYAYAYAYAYAY")
            			time.sleep(5) #this is to avoid multiple detections
        		else:
            			print("MOTION NOT DETECTED :(")

        		time.sleep(0.1)

	except:
    		GPIO.cleanup()
