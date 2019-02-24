import numpy as np
import cv2
import datetime
import socket
import sys
import time

# Initialise the parameters
confThreshold = 0.5  # Confidence threshold
nmsThreshold = 0.4   # Non-maximum suppression threshold
inpSize = [416,416]  # Width and Height of network's input image
outputPeopleCount = 0
windowSize = [896,504]

# TCP Socket Connections
socketHost = '192.168.1.123'
socketPort = 5000

# Modules
mod_ClockOn = 1
mod_RemoteSend = 0

# Remote Send
if mod_RemoteSend == 1:
    # Create a TCP/IP socket
    sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)

    # Connect the socket to the port where the server is listening
    server_address = (socketHost, socketPort)
    print('connecting to {} port {}'.format(*server_address))

    sock.connect(server_address)
else:
    print('Module: Remote send module disabled.')

# Get Camera Footage
cap = cv2.VideoCapture(0)
cap.set(cv2.CAP_PROP_FPS, 60)

# Load names of classes
classesFile = "network/coco.names";
classes = None
with open(classesFile, 'rt') as f:
    classes = f.read().rstrip('\n').split('\n')

# Give the configuration and weight files for the model and load the network using them.
modelConfiguration = "network/yolov3.cfg";
modelWeights = "network/yolov3.weights";

# Configure the network
net = cv2.dnn.readNetFromDarknet(modelConfiguration, modelWeights)
net.setPreferableBackend(cv2.dnn.DNN_BACKEND_OPENCV)
net.setPreferableTarget(cv2.dnn.DNN_TARGET_CPU)

# Get the names of the output layers
def getOutputsNames(net):
    layersNames = net.getLayerNames()
    return [layersNames[i[0] - 1] for i in net.getUnconnectedOutLayers()]

# Draw the predicted bounding box
def drawPred(classId, conf, left, top, right, bottom):
    # Draw a bounding box.
    color = [255,178,50,0]
    cv2.rectangle(frame, (left, top), (right, bottom), (color[0],color[1],color[2]), 2)
    # Looks for people
    if classId == 0:
        # Lower Confidence
        if conf < 0.7:
            # Orange
            color = [51,255,255,0]
            cv2.rectangle(frame, (left, top), (right, bottom), (color[0],color[1],color[2]), 2)
        else:
            # Green
            color = [0,255,0,0]
            cv2.rectangle(frame, (left, top), (right, bottom), (color[0],color[1],color[2]), 2)
    else:
        color = [255,0,0,255]
        cv2.rectangle(frame, (left, top), (right, bottom), (color[0],color[1],color[2]), 2)
    label = '%.2f' % conf

    # Get the label for the class name and its confidence
    if classes:
        assert(classId < len(classes))
        label = '%s:%s' % (classes[classId], label)

    #Display the label at the top of the bounding box
    labelSize, baseLine = cv2.getTextSize(label, cv2.FONT_HERSHEY_SIMPLEX, 0.5, 1)
    top = max(top, labelSize[1])
    cv2.rectangle(frame, (left, top - round(1.5*labelSize[1])), (left + round(1*labelSize[0]), top + baseLine), (color[0],color[1],color[2]), cv2.FILLED)
    cv2.putText(frame, label, (left, top), cv2.FONT_HERSHEY_SIMPLEX, 0.50, (color[3],color[3],color[3]), 1)

def peopleCounter(peopleCount, objectCount):
    # Output People Count combined with total count.
    if peopleCount > 0:
        print("Object Count: ",currentDT.strftime("%X"),peopleCount,"/",objectCount,("#" * peopleCount))
    else:
        print("No People Detected!")

# Remove the bounding boxes with low confidence using non-maxima suppression
def postprocess(frame, outs):
    global outputPeopleCount

    frameHeight = frame.shape[0]
    frameWidth = frame.shape[1]

    # Arrays
    classIds = []
    confidences = []
    boxes = []

    # Scan through all the bounding boxes output from the network and keep only the
    # ones with high confidence scores.
    # Assign the box's class label as the class with the highest score.
    classIds = []
    confidences = []
    boxes = []
    peopleCount = 0
    objectCount = 0

    for out in outs:
        for detection in out:
            scores = detection[5:]
            classId = np.argmax(scores)
            confidence = scores[classId]
            if confidence > confThreshold:
                center_x = int(detection[0] * frameWidth)
                center_y = int(detection[1] * frameHeight)
                width = int(detection[2] * frameWidth)
                height = int(detection[3] * frameHeight)
                left = int(center_x - width / 2)
                top = int(center_y - height / 2)
                classIds.append(classId)
                confidences.append(float(confidence))
                boxes.append([left, top, width, height])

                currentDT = datetime.datetime.now()

                # Look for people
                if classId == 0:
                    peopleCount = peopleCount + 1
                else:
                    objectCount = objectCount + 1

    peopleCounter(peopleCount, objectCount)
    outputPeopleCount = peopleCount

    # Perform non maximum suppression to eliminate redundant overlapping boxes with
    # lower confidences.
    indices = cv2.dnn.NMSBoxes(boxes, confidences, confThreshold, nmsThreshold)
    for i in indices:
        i = i[0]
        box = boxes[i]
        left = box[0]
        top = box[1]
        width = box[2]
        height = box[3]
        drawPred(classIds[i], confidences[i], left, top, left + width, top + height)

while(True):
    currentDT = datetime.datetime.now()

    ret, frame = cap.read()

    # Create a 4D blob from a frame.
    blob = cv2.dnn.blobFromImage(frame, 1/255, (inpSize[0], inpSize[1]), [0,0,0], 1, crop=False)

    # Sets the input to the network
    net.setInput(blob)

    # Runs the forward pass to get output of the output layers
    outs = net.forward(getOutputsNames(net))

    # Remove the bounding boxes with low confidence
    postprocess(frame, outs)

    # Render Window
    winName = 'Object Detection Demo v0.2'

    cv2.namedWindow(winName, cv2.WINDOW_NORMAL)
    cv2.resizeWindow(winName, windowSize[0],windowSize[1])

    if mod_ClockOn == 1:
        cv2.putText(frame, currentDT.strftime("%X"), (0, 50), cv2.FONT_HERSHEY_SIMPLEX, 1.0, (0, 0, 255))

    cv2.putText(frame, str(outputPeopleCount), (200, 50), cv2.FONT_HERSHEY_SIMPLEX, 1.0, (255, 0, 0))

    cv2.imshow(winName,frame)

    # q key to exit.
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

cap.release()
cv2.destroyAllWindows()
