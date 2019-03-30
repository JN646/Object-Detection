# ==============================================================================
# Project:      Object Detection Application
# File name:    camera.py
# Author:       JGinn DAlexander
# Year:         2019
# ==============================================================================

# ==============================================================================
# Imports
# ==============================================================================
# Imports
import cv2 as cv
import argparse
import sys
import numpy as np
import os.path
import datetime
import object_class

# Initialise the parameters
confThreshold = 0.5  # Confidence threshold
nmsThreshold = 0.4   # Non-maximum suppression threshold
inpWidth = 416       # Width of network's input image
inpHeight = 416      # Height of network's input image

# Load names of classes
classesFile = "network/coco.names";
classes = None
with open(classesFile, 'rt') as f:
    classes = f.read().rstrip('\n').split('\n')

# Storing Objects.
class ObjectDetection:
    # Object Init.
    def __init__(self, objectClass, objectConfidence):
        self.objectClass = objectClass
        self.objectTime = datetime.datetime.now()
        self.objectClassLabel = "Blank"
        self.objectConfidence = objectConfidence

    # Get the class label from the class list.
    def getClassLabel(self):
        # Get the label from class id.
        self.objectClassLabel = classes[self.objectClass]

    def writeToFile(self):
        # Map data to columns.
        row = [obj.objectTime, obj.objectClassLabel, str(obj.objectConfidence)+"%"]

        # Amend CSV.
        with open("file.csv", 'a') as file:
            writer = csv.writer(file)
            writer.writerow(row)

# Give the configuration and weight files for the model and load the network using them.
modelConfiguration = 'network/yolov3.cfg'
modelWeights = 'network/yolov3.weights'

# Configure the network
net = cv.dnn.readNetFromDarknet(modelConfiguration, modelWeights)
net.setPreferableBackend(cv.dnn.DNN_BACKEND_OPENCV)
net.setPreferableTarget(cv.dnn.DNN_TARGET_CPU)

# Get the names of the output layers
def getOutputsNames(net):
    # Get the names of all the layers in the network
    layersNames = net.getLayerNames()
    # Get the names of the output layers, i.e. the layers with unconnected outputs
    return [layersNames[i[0] - 1] for i in net.getUnconnectedOutLayers()]

# Draw the predicted bounding box
def drawPred(classId, conf, left, top, right, bottom):
    # Draw a bounding box.
    cv.rectangle(frame, (left, top), (right, bottom), (255, 178, 50), 2)
    # Looks for people
    if classId == 0:
        # Lower Confidence
        if conf < 0.7:
            # Orange
            cv.rectangle(frame, (left, top), (right, bottom), (51, 255, 255), 2)
        else:
            # Green
            cv.rectangle(frame, (left, top), (right, bottom), (0, 255, 0), 2)
    else:
        cv.rectangle(frame, (left, top), (right, bottom), (255, 0, 0), 2)
    label = '%.2f' % conf

    # Get the label for the class name and its confidence
    if classes:
        assert(classId < len(classes))
        label = '%s:%s' % (classes[classId], label)

    #Display the label at the top of the bounding box
    labelSize, baseLine = cv.getTextSize(label, cv.FONT_HERSHEY_SIMPLEX, 0.5, 1)
    top = max(top, labelSize[1])
    cv.rectangle(frame, (left, top - round(1.5*labelSize[1])), (left + round(1*labelSize[0]), top + baseLine), (255, 255, 255), cv.FILLED)
    cv.putText(frame, label, (left, top), cv.FONT_HERSHEY_SIMPLEX, 0.50, (0,0,0), 1)

# Remove the bounding boxes with low confidence using non-maxima suppression
def postprocess(frame, outs):
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

    for out in outs:
        for detection in out:
            scores = detection[5:]
            classId = np.argmax(scores)
            confidence = scores[classId]

            # Create Object
            my_objects = ObjectDetection(classId, confidence)

            # print(my_objects.objectTime, my_objects.objectClassLabel, str(my_objects.objectConfidence)+"%")

            # Define confidence
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

    # Perform non maximum suppression to eliminate redundant overlapping boxes with
    # lower confidences.
    indices = cv.dnn.NMSBoxes(boxes, confidences, confThreshold, nmsThreshold)
    for i in indices:
        i = i[0]
        box = boxes[i]
        left = box[0]
        top = box[1]
        width = box[2]
        height = box[3]
        drawPred(classIds[i], confidences[i], left, top, left + width, top + height)

# Process inputs
# Window Commands
winName = 'Object Detection Demo v0.2'
winDash = 'Dashboard'

cv.namedWindow(winName, cv.WINDOW_NORMAL)
cv.resizeWindow(winName, 800,600)

cap = cv.VideoCapture(0)

while cv.waitKey(1) < 0:

    # get frame from the video
    hasFrame, frame = cap.read()

    # Stop the program if reached end of video
    if not hasFrame:
        cv.waitKey(0)
        # Release device
        cap.release()
        break

    # Create a 4D blob from a frame.
    blob = cv.dnn.blobFromImage(frame, 1/255, (inpWidth, inpHeight), [0,0,0], 1, crop=False)

    # Sets the input to the network
    net.setInput(blob)

    # Runs the forward pass to get output of the output layers
    outs = net.forward(getOutputsNames(net))

    # Remove the bounding boxes with low confidence
    postprocess(frame, outs)

    # Put efficiency information. The function getPerfProfile returns the overall time for inference(t) and the timings for each of the layers(in layersTimes)
    t, _ = net.getPerfProfile()
    label = 'Inference time: %.2f ms' % (t * 1000.0 / cv.getTickFrequency())
    cv.putText(frame, label, (0, 15), cv.FONT_HERSHEY_SIMPLEX, 0.5, (0, 0, 255))

    cv.imshow(winName, frame)
