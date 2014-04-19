from sys import argv
import os.path              
import PIL
import Image

dir = os.path.dirname(os.path.abspath(__file__))
filename = os.path.join(dir, argv[1], argv[2])

thumbname = os.path.join(os.path.splitext(filename)[0] + "thumb.jpg")
        
try:
    srcimg = Image.open(filename)
    srcimg.thumbnail((200,200), Image.ANTIALIAS)
    left = (srcimg.size[0] - 100)/2
    right = (srcimg.size[0]-100)/2 + 100
    upper = (srcimg.size[1] - 100)/2
    lower = (srcimg.size[1]-100)/2 + 100
    srcimg = srcimg.crop((left,upper,right,lower))    
    srcimg.save(thumbname, "JPEG")
except IOError:
    error_str = "Could not create thumbnail for", filename
