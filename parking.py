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
import os.path                          # Path file tools
import csv                              # Include CSV
import os                               # Operating System
from colorama import Fore, Back, Style  # Include terminal colours

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
frameExtractRate = 50

# Network Config
modelName = 'YOLOv3'
modelConfiguration = 'network/yolov3.cfg'
modelWeights = 'network/yolov3.weights'
classesFile = "network/coco.names";

# TCP Socket Connections
feedName = 'Camera1'
socketHost = '127.0.0.1'
socketPort = 8888

# Output to file
outputToFileName = 'file.csv'

# Modules
mod_ClockOn = 1             # GUI Clock.
mod_TargetCount = 1         # GUI Target count.
mod_RemoteSend = 0          # Send count through sockets.
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
    print(Fore.GREEN + '[OK] Outputting Screengrab.')
    success,image = cap.read()

    # Set filename and path.
    fileName = '{0:%y%m%d-%H%M%S}'.format(datetime.datetime.now()) + "_grab"
    path = "grabs/"

    # If file created successfully.
    if success:
        cv2.imwrite(path + fileName + ".jpg", image)     # save frame as JPEG file
        print(Fore.GREEN + '[OK] File created.')
    else:
        print(Fore.RED + '[DANGER] File not created.')

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
# Send Count to TCP Server
# ==============================================================================
def sendOutputTarget(outputTargetCount):
    if outputTargetCount == '':
        outputTargetCount = 0

    message = str(outputTargetCount)
    sock.sendall(bytes(message, encoding='utf-8'))

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
    labelSize, baseLine = cv2.getTextSize(label, cv2.FONT_HERSHEY_SIMPLEX, 0.3, 1)
    top = max(top, labelSize[1])
    cv2.rectangle(frame, (left, top - round(1.5*labelSize[1])), (left + round(1*labelSize[0]), top + baseLine), (color[0],color[1],color[2]), cv2.FILLED)
    cv2.putText(frame, label, (left, top), cv2.FONT_HERSHEY_SIMPLEX, 0.3, (color[3],color[3],color[3]), 1)

# ==============================================================================
# Target Object Counter
# ==============================================================================
def targetCounter(targetCount, objectCount):
        # Output People Count combined with total count.
    if targetCount > 0:
        if mod_terminalCount == 1:
            currentTime = currentDT.strftime("%X")
            print(Fore.GREEN + "Object Count: ",currentTime,targetCount,"/",objectCount,("#" * targetCount))

        # Send count over TCP if remote send is active.
        if mod_RemoteSend == 1:
            try:
                sendOutputTarget(targetCount)
            except Exception as e:
                print(Fore.RED + '[DANGER] Send to server.')
    else:
        if mod_terminalCount == 1:
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
    uID = []
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

                currentDT = currentTime()

                # What to look for
                if classId == targetClassId:
                    targetCount += 1
                elif classId != targetClassId:
                    objectCount += 1

    # Output Counter
    try:
        targetCounter(targetCount, objectCount)

        # Set output target count.
        if outputTargetCount >= 0:
            outputTargetCount = targetCount
        else:
            outputTargetCount = 0
    except Exception as e:
        print(Fore.RED + '[DANGER] Cannot count target objects.')

    # Perform non maximum suppression to eliminate redundant overlapping boxes with lower confidences.
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
try:
    # Clear the Screen
    os.system('clear')

    # Print Intro Messages
    print(Fore.WHITE + '# ============================= #')
    print(Fore.WHITE + '# Object Detection App          #')
    print(Fore.WHITE + '# v0.2                          #')
    print(Fore.WHITE + '# 2019                          #')
    print(Fore.WHITE + '# ============================= #')

    # Wait for key press
    input(Fore.WHITE + 'Press enter to continue... ')

    # Notification
    # Feed Name
    if feedName:
        if feedName != '':
            print(Fore.GREEN + '[OK] Working on:',feedName)
        else:
            print(Fore.RED + '[DANGER] Working on unknown source.')
            sys.exit()

    if videoCameraInputSource:
        if videoCameraInputSource !='':
            print(Fore.GREEN + '[OK] Working on source:',videoCameraInputSource)
        else:
            print(Fore.RED + '[DANGER] Working on unknown video source.')
            sys.exit()

    # Model Name
    if modelName:
        if modelName != '':
            print(Fore.GREEN + '[OK] Using on:',modelName)
        else:
            print(Fore.RED + '[DANGER] Working on unknown network.')
            sys.exit()

        if modelConfiguration != '':
            if os.path.isfile(modelConfiguration):
                print(Fore.GREEN + '[OK] NETWORK: Configuration Loaded.')
            else:
                print(Fore.RED + '[DANGER] NETWORK: Configuration file not found.')
        else:
            print(Fore.RED + '[DANGER] NETWORK: No configuration specified.')
            sys.exit()

        if modelWeights != '':
            if os.path.isfile(modelWeights):
                print(Fore.GREEN + '[OK] NETWORK: Weights Loaded.')
            else:
                print(Fore.RED + '[DANGER] NETWORK: Weights file not found.')
        else:
            print(Fore.RED + '[DANGER] NETWORK: No weights specified.')
            sys.exit()

    # Processing Time
    if frameExtractRate > 0:
        print(Fore.GREEN + '[OK] Processing wait time is set to',frameExtractRate,'frames.')
    else:
        print(Fore.YELLOW + '[INFO] Processing wait time is disabled.')

    # Target Class ID
    if targetClassId != '':
        print(Fore.GREEN + 'Counting:',targetClassId)
    else:
        print(Fore.RED + '[DANGER] Not counting for anything.')
        sys.exit()

    # MODULES
    # Remote Send.
    if mod_RemoteSend == 1:
        # Create a TCP/IP socket.
        sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        # Connect the socket to the port where the server is listening.
        server_address = (socketHost, socketPort)
        print(Fore.GREEN + 'connecting to {} port {}'.format(*server_address))
        # Connect to server.
        try:
            sock.connect(server_address)
        except Exception as e:
            print(Fore.RED + '[DANGER] Cannot connect to the server.')
            sys.exit()

    else:
        print(Fore.YELLOW + '[INFO] Module: Remote send module disabled.')

    # GUI Window.
    if mod_OutputWindow == 0:
        print(Fore.YELLOW + '[INFO] Module: Output window module disabled.')

    # Output to file.
    if mod_OutputFile == 0:
        print(Fore.YELLOW + '[INFO] Module: Output to file module disabled.')
    else:
        print(Fore.GREEN + '[OK] Module: Output to file module enabled.')

    # Confirm start.
    # Wait for key press
    input(Fore.WHITE + 'Press enter to continue... ')

    # ==============================================================================
    # Get Input
    # ==============================================================================
    # Get Camera Footage
    if videoCameraInputSource:
        if ".mp4" not in videoCameraInputSource:
            print(Fore.GREEN + '[OK] Camera Source: Camera Feed.')
        else:
            print(Fore.GREEN + '[OK] Camera Source: MP4 File.')

    cap = cv2.VideoCapture(videoCameraInputSource)

    if not cap.isOpened():
        print(Fore.RED + '[DANGER] Cannot open camera feed.')
        sys.exit()

    # ==============================================================================
    # Load Network and Label data
    # ==============================================================================
    # Load names of classes
    classes = None
    if os.path.exists(classesFile):
        with open(classesFile, 'rt') as f:
            classes = f.read().rstrip('\n').split('\n')
    else:
        print(Fore.RED + '[DANGER] NETWORK: No label file specified.')
        sys.exit()

    # Configure the network
    net = cv2.dnn.readNetFromDarknet(modelConfiguration, modelWeights)
    net.setPreferableBackend(cv2.dnn.DNN_BACKEND_OPENCV)
    net.setPreferableTarget(cv2.dnn.DNN_TARGET_CPU)

except (KeyboardInterrupt, SystemExit):
    print(Fore.RED + '[DANGER] Program has finished.')
    sys.exit()

# ==============================================================================
# Main Loop
# ==============================================================================
while(True):
    try:
        # Get current date and time.
        currentDT = currentTime()

        # While camera is open.
        while (cap.isOpened()):
            # Read the camera feed.
            ret, frame = cap.read()

            # While there are frames.
            if ret == True:
                if count % frameExtractRate == 0:

                    print('Count/Frame Extract:',count)

                    # Create a 4D blob from a frame.
                    blob = cv2.dnn.blobFromImage(frame, 1/255, (inpSize[0], inpSize[1]), [0,0,0], 1, crop=True)

                    # Sets the input to the network
                    net.setInput(blob)

                    # Runs the forward pass to get output of the output layers
                    try:
                        outs = net.forward(getOutputsNames(net))
                    except Exception as e:
                        print(Fore.RED + '[DANGER] Cannot get output of output layers.')
                        sys.exit()

                    # Remove the bounding boxes with low confidence
                    try:
                        postprocess(frame, outs)
                    except Exception as e:
                        print(Fore.RED + '[DANGER] Unable to process footage.')
                        sys.exit()

                    # Output to file
                    if mod_OutputFile == 1:
                        try:
                            file = outputToFile()
                        except Exception as e:
                            print(Fore.RED + '[DANGER] Cannot Output to file.')

                    # If OutputWindow is Active.
                    if mod_OutputWindow == 1:
                        # Render Window
                        cv2.namedWindow(winName, cv2.WINDOW_NORMAL)
                        cv2.resizeWindow(winName, windowSize[0],windowSize[1])

                        # Display the clock.
                        if mod_ClockOn == 1:
                            cv2.putText(frame, currentDT.strftime("%Y-%m-%d %H:%M:%S"), (0, 25), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (0, 0, 255))

                        # Display the target object count.
                        if mod_TargetCount == 1:
                            cv2.putText(frame, str(outputTargetCount), (200, 25), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (255, 0, 0))

                        # Show the window
                        cv2.imshow(winName,frame)

                        # Not responsive enough.
                        if cv2.waitKey(1) & 0xFF == ord('p'):
                            outputScreenshot() # Output Screenshot.

                        # q key to exit.
                        if cv2.waitKey(1) & 0xFF == ord('q'):
                            print('Exiting...')
                            break
                # Increment Frame Count
                count += 1

    except (KeyboardInterrupt, SystemExit):
        print(Fore.RED + '[DANGER] Program has finished.')
        break

# ==============================================================================
# Closing Remarks
# ==============================================================================
# Release the camera feed.
print(Fore.GREEN + 'Exiting camera feed.')
cap.release()

# Close the write to file if Output mode is on.
if mod_OutputFile == 1:
    file.close()
    print(Fore.GREEN + 'Output file closed.')

# Destroy all windows.
if mod_OutputWindow == 1:
    cv2.destroyAllWindows()
    print(Fore.GREEN + 'GUI window closed.')

if mod_RemoteSend == 1:
    sock.send(b'--quit--')
    print(Fore.GREEN + 'Remote server quit message.')
    print(Fore.GREEN + 'Remote server closed.')
