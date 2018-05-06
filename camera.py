import picamera
import time
import requests
from PIL import Image
import pygame
from threading import Thread
import publisher

url = 'http://greeneye.guillermorodriguez.xyz/api/image/process'
pollUrl = 'http://greeneye.guillermorodriguez.xyz/polling'

class CaptureAndSendImage:
    def __init__(self, url):
        self.camera = picamera.PiCamera()
        self.camera.vflip = True
        self.URL = url
        self.org_image = 'capture.jpg'
        self.compressed_image = 'compressed.jpg'

    def capture_image(self):
        print("Capturing image")
        self.camera.capture(self.org_image)
        print("Compressing image")
        self.compress_image()

    def compress_image(self):
        comprs_image = Image.open(self.org_image)
        comprs_image.save(self.compressed_image, optimize=True, quality=90)

    def send_image(self):
        print("Sending image")
        files = {'process_image':open(self.compressed_image, 'rb')}
        response = requests.post(url, files=files)
        if response.status_code == 200:
            print("Image sent successfully")
        else:
            print("Image sending failed")



if __name__=='__main__':
    csi = CaptureAndSendImage(url)
    csi.capture_image()
    csi.send_image()
    psi = Sound(pollUrl)
    soundThread = Thread(target=psi.playSound(), args=())
    soundThread.start()
    time.sleep(5)
    

'''    while(1):
       response = requests.get(pollUrl)
       data = response.json()
       #print(data)
       light = data['light'] 
       sound = data['music']
       if not sound:
          publisher.publish_music("Play")
       if not light:
          publisher.publish_light("Turn on")
       time.sleep(3)
         
'''
