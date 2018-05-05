

import picamera
import time
import requests
from PIL import Image

url = 'http://greeneye.guillermorodriguez.xyz/api/image/process'

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
