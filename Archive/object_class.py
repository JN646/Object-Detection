# ==============================================================================
# Project:      Object Detection Application
# File name:    object_class.py
# Author:       JGinn DAlexander
# Year:         2019
# ==============================================================================

# ==============================================================================
# Imports
# ==============================================================================
import sys
import datetime
import csv
import random

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
