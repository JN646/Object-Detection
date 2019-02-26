# ==============================================================================
# Project:      Object Detection Application
# File name:    camera.py
# Author:       JGinn DAlexander
# Year:         2019
# ==============================================================================

# ==============================================================================
# Imports
# ==============================================================================
import numpy as np                      # Include numpy
import cv2                              # Include OpenCV 4
import datetime                         # Include date and time
import socket                           # Include Socket.IO
import sys                              # Include System
import time                             # Include Time package
from colorama import Fore, Back, Style  # Include terminal colours.

# ==============================================================================
# Initialise the parameters
# ==============================================================================
confThreshold = 0.5         # Confidence threshold.
nmsThreshold = 0.4          # Non-maximum suppression threshold.
inpSize = [416,416]         # Width and Height of network's input image.
outputTargetCount = 0
windowSize = [896,504]
processingTime = 0
winName = 'Object Detection App v0.2'
targetClassId = 0

# TCP Socket Connections
feedName = 'Camera1'
socketHost = '192.168.1.123'
socketPort = 5500

# Modules
mod_ClockOn = 1
mod_RemoteSend = 0
mod_OutputWindow = 1

# Print Intro Messages
print('Object Detection App')
if feedName != '':
    print(Fore.GREEN + 'Working on:',feedName)
else:
    print(Fore.RED + 'Working on unknown source.')

if processingTime > 0:
    print(Fore.GREEN + 'Processing wait time is set to',processingTime,'seconds.')
else:
    print(Fore.YELLOW + 'Processing wait time is disabled.')

# Remote Send
if mod_RemoteSend == 1:
    # Create a TCP/IP socket
    sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)

    # Connect the socket to the port where the server is listening
    server_address = (socketHost, socketPort)
    print(Fore.GREEN + 'connecting to {} port {}'.format(*server_address))

    sock.connect(server_address)
else:
    print(Fore.YELLOW + 'Module: Remote send module disabled.')

if mod_OutputWindow == 0:
    print(Fore.YELLOW + 'Module: Output window module disabled.')

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

# ==============================================================================
# Get Output Names
# ==============================================================================
def getOutputsNames(net):
    layersNames = net.getLayerNames()
    return [layersNames[i[0] - 1] for i in net.getUnconnectedOutLayers()]

# ==============================================================================
# Send Count to TCP Server
# ==============================================================================
def sendOutputTarget(outputTargetCount):
    if outputTargetCount >= 0:
        message = str(outputTargetCount)
        sock.sendall(bytes(message, encoding='utf-8'))
    else:
        outputTargetCount = 0

# ==============================================================================
# Draw Predicted Bounding Box
# ==============================================================================
def drawPred(classId, targetClassId, conf, left, top, right, bottom):
    # Draw a bounding box.
    color = [255,178,50,0]
    cv2.rectangle(frame, (left, top), (right, bottom), (color[0],color[1],color[2]), 2)
    # Looks for target object.
    if classId == targetClassId:
        # Lower Confidence
        if conf < 0.7:
            color = [51,255,255,0] # Orange
            cv2.rectangle(frame, (left, top), (right, bottom), (color[0],color[1],color[2]), 2)
        else:
            color = [0,255,0,0] # Green
            cv2.rectangle(frame, (left, top), (right, bottom), (color[0],color[1],color[2]), 2)
    else:
        color = [255,0,0,255] # Blue
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

# ==============================================================================
# Target Object Counter
# ==============================================================================
def targetCounter(targetCount, objectCount):
    # Output People Count combined with total count.
    if targetCount > 0:
        print(Fore.GREEN + "Object Count: ",currentDT.strftime("%X"),targetCount,"/",objectCount,("#" * targetCount))

        # Send count over TCP if remote send is active.
        if mod_RemoteSend == 1:
            sendOutputTarget(targetCount)
    else:
        print(Fore.YELLOW + "No Targets Detected!")

        # Send count over TCP if remote send is active.
        if mod_RemoteSend == 1:
            sendOutputTarget(targetCount)

# ==============================================================================
# Post Process
# ==============================================================================
# Remove the bounding boxes with low confidence using non-maxima suppression
def postprocess(frame, outs):
    # Define variables.
    global outputTargetCount
    frameHeight = frame.shape[0]
    frameWidth = frame.shape[1]

    # Scan through all the bounding boxes output from the network and keep only the ones with high confidence scores.
    # Assign the box's class label as the class with the highest score.
    classIds = []
    confidences = []
    boxes = []
    targetCount = 0
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
                if classId == targetClassId:
                    targetCount = targetCount + 1
                elif classId != targetClassId:
                    objectCount = objectCount + 1

    # Output Counter
    targetCounter(targetCount, objectCount)
    outputTargetCount = targetCount

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
        drawPred(classIds[i], targetClassId, confidences[i], left, top, left + width, top + height)

# ==============================================================================
# Main Sequence
# ==============================================================================
while(True):
    # Get current date and time.
    currentDT = datetime.datetime.now()

    # Read the camera feed.
    ret, frame = cap.read()

    # Create a 4D blob from a frame.
    blob = cv2.dnn.blobFromImage(frame, 1/255, (inpSize[0], inpSize[1]), [0,0,0], 1, crop=False)

    # Sets the input to the network
    net.setInput(blob)

    # Runs the forward pass to get output of the output layers
    outs = net.forward(getOutputsNames(net))

    # Remove the bounding boxes with low confidence
    postprocess(frame, outs)

    # If OutputWindow is Active.
    if mod_OutputWindow == 1:
        # Render Window
        cv2.namedWindow(winName, cv2.WINDOW_NORMAL)
        cv2.resizeWindow(winName, windowSize[0],windowSize[1])

        # Display the clock.
        if mod_ClockOn == 1:
            cv2.putText(frame, currentDT.strftime("%X"), (0, 50), cv2.FONT_HERSHEY_SIMPLEX, 1.0, (0, 0, 255))

        # Display the target object count.
        cv2.putText(frame, str(outputTargetCount), (200, 50), cv2.FONT_HERSHEY_SIMPLEX, 1.0, (255, 0, 0))

        # Show the window
        cv2.imshow(winName,frame)

        # Pause the application if the processing time if more than 0
        if processingTime > 0:
            time.sleep(processingTime)

        # q key to exit.
        if cv2.waitKey(1) & 0xFF == ord('q'):
            break

# ==============================================================================
# Closing Remarks
# ==============================================================================
# Release the camera feed.
cap.release()

# Destroy all windows.
if mod_OutputWindow == 1:
    cv2.destroyAllWindows()
