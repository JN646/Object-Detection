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
import csv                              # Include CSV
import os
from colorama import Fore, Back, Style  # Include terminal colours
from matplotlib import pyplot as plt

# ==============================================================================
# MISSION
# ==============================================================================
# 1. Looks for objects in a specific area of a camera feed.
# 2. Identifies the objects as a vehicle.
# 3. Gives each object an ID.
# 4. Starts a count on how long an object has been there for.
# 5. Takes a picture after a period of time.
# 6. Crops the picture to a specific object.
# 7. Send the picture somewhere.

# ==============================================================================
# Initialise the parameters
# ==============================================================================
confThreshold = 0.5                     # Confidence threshold.
nmsThreshold = 0.4                      # Non-maximum suppression threshold.
inpSize = [416,416]                     # Width and Height of network's input image.
outputTargetCount = 0                   # Initial Target Count.
windowSize = [896,504]                  # Window Size.
winName = 'ODAv02'                      # Application window name.
targetClassId = 0                       # Target object class.
videoCameraInputSource = 0
count = 0
feedName = "Feed1"
ProcessVideo = True

# Network Config
modelName = 'YOLOv3'
modelConfiguration = 'network/yolov3.cfg'
modelWeights = 'network/yolov3.weights'
classesFile = "network/coco.names";

# Output to file
outputToFileName = 'parking.csv'

fgbg = cv2.createBackgroundSubtractorMOG2()

# Modules
mod_ClockOn = 1             # GUI Clock.
mod_OutputWindow = 1        # GUI Window.
mod_OutputFile = 0          # Output to a file.
mod_terminalCount = 0       # Terminal count display.

# ==============================================================================
# Get the time
# ==============================================================================
def currentTime():
    currentDT = datetime.datetime.now()
    return currentDT

# ==============================================================================
# Output Screenshot
# ==============================================================================
def outputScreenshot():
    # Get the current frame.
    print('[OK] Outputting Screengrab.')
    success,image = cap.read()

    # Set filename and path.
    fileName = '{0:%y%m%d-%H%M%S}'.format(datetime.datetime.now()) + "_grab"
    path = "grabs/"

    # If file created successfully.
    if success:
        cv2.imwrite(path + fileName + ".jpg", image)     # save frame as JPEG file
        print('[OK] File created.')
    else:
        print('[DANGER] File not created.')

# ==============================================================================
# Output to file
# ==============================================================================
def outputToFile():
    if mod_OutputFile == 1:
        # Map data to columns.
        row = [str(currentDT.strftime("%Y-%m-%d %H:%M:%S")), str("N/A"), str(feedName), str(outputTargetCount)]

        # Amend CSV.
        with open(outputToFileName, 'a') as file:
            writer = csv.writer(file)
            writer.writerow(row)

    return file

# ==============================================================================
# Get Output Names
# ==============================================================================
def getOutputsNames(net):
    layersNames = net.getLayerNames()
    return [layersNames[i[0] - 1] for i in net.getUnconnectedOutLayers()]

# ==============================================================================
# Main Sequence
# ==============================================================================
try:
    # Clear the Screen
    os.system('clear')
    print('Obejct Detection Application')
    print('Running...')

    # Get Camera Footage
    cap = cv2.VideoCapture(videoCameraInputSource)

    if not cap.isOpened():
        print('[DANGER] Cannot open camera feed.')
        sys.exit()

    # Load names of classes
    classes = None

    with open(classesFile, 'rt') as f:
        classes = f.read().rstrip('\n').split('\n')

    # Configure the network
    net = cv2.dnn.readNetFromDarknet(modelConfiguration, modelWeights)
    net.setPreferableBackend(cv2.dnn.DNN_BACKEND_OPENCV)
    net.setPreferableTarget(cv2.dnn.DNN_TARGET_CPU)

# Catch keyboard interrupts.
except (KeyboardInterrupt, SystemExit):
    print('[DANGER] Program has finished.')
    sys.exit()

# ==============================================================================
# Main Loop
# ==============================================================================
while(True):
    try:
        # Get current date and time.
        currentDT = datetime.datetime.now()

        # Read the camera feed.
        ret, frame = cap.read()

        if ProcessVideo == True:
            # Create a 4D blob from a frame.
            blob = cv2.dnn.blobFromImage(frame, 1/255, (inpSize[0], inpSize[1]), [0,0,0], 1, crop=True)

            # Sets the input to the network
            net.setInput(blob)

            # Runs the forward pass to get output of the output layers
            outs = net.forward(getOutputsNames(net))

            # Remove the bounding boxes with low confidence
            # Define variables.
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

                        # What to look for
                        if classId == targetClassId:
                            targetCount += 1
                        elif classId != targetClassId:
                            objectCount += 1

                # Perform non maximum suppression to eliminate redundant overlapping boxes with lower confidences.
                indices = cv2.dnn.NMSBoxes(boxes, confidences, confThreshold, nmsThreshold)
                for i in indices:
                    i = i[0]
                    box = boxes[i]
                    left = box[0]
                    top = box[1]
                    right = left + width
                    bottom = top + height
                    width = box[2]
                    height = box[3]
                    conf = confidences[i]

                    # Draw a bounding box.
                    color = [255,178,50,0]
                    cv2.rectangle(frame, (left, top), (right, bottom), (color[0],color[1],color[2]), 2)

                    # Lower Confidence
                    if conf < 0.7:
                        color = [51,255,255,0] # Orange
                        cv2.rectangle(frame, (left, top), (right, bottom), (color[0],color[1],color[2]), 2)
                    else:
                        color = [0,255,0,0] # Green
                        cv2.rectangle(frame, (left, top), (right, bottom), (color[0],color[1],color[2]), 2)

                    label = '%.2f' % conf

                    # Get the label for the class name and its confidence
                    if classes:
                        assert(classId < len(classes))
                        label = '%s %s%%' % (classes[classId], label)

                    #Display the label at the top of the bounding box
                    labelSize, baseLine = cv2.getTextSize(label, cv2.FONT_HERSHEY_SIMPLEX, 0.3, 1)
                    top = max(top, labelSize[1])
                    cv2.rectangle(frame, (left, top - round(1.5*labelSize[1])), (left + round(1*labelSize[0]), top + baseLine), (color[0],color[1],color[2]), cv2.FILLED)
                    cv2.putText(frame, label, (left, top), cv2.FONT_HERSHEY_SIMPLEX, 0.3, (color[3],color[3],color[3]), 1)

                    # Output to file
                    if mod_OutputFile == 1:
                        # Map data to columns.
                        row = [str(currentDT),str(classes[classId]), str(conf)]

                        # Amend CSV.
                        with open(outputToFileName, 'a') as file:
                            writer = csv.writer(file)
                            writer.writerow(row)

        # print(currentDT,label)

        # Render Window
        cv2.namedWindow(winName, cv2.WINDOW_NORMAL)
        cv2.resizeWindow(winName, windowSize[0],windowSize[1])

        # Display the clock.
        cv2.putText(frame, currentDT.strftime("%Y-%m-%d %H:%M:%S"), (0, 25), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (0, 0, 255))

        # Show the window
        # cv2.imshow('frame',fgmask)
        cv2.imshow(winName,frame)

        # Not responsive enough.
        if cv2.waitKey(1) & 0xFF == ord('p'):
            outputScreenshot() # Output Screenshot.

        # q key to exit.
        if cv2.waitKey(1) & 0xFF == ord('q'):
            print('Exiting...')
            break

    except (KeyboardInterrupt, SystemExit):
        print('[DANGER] Program has finished.')
        break

# ==============================================================================
# Closing Remarks
# ==============================================================================
# Release the camera feed.
print('Exiting camera feed.')
cap.release()

if mod_OutputWindow == 1:
    cv2.destroyAllWindows()
    print('GUI window closed.')
