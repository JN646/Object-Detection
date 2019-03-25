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

# Header
print('Object Class Test.')

# Storing Objects.
class Object:
    # Object Init.
    def __init__(self, objectClass, objectCount, objectConfidence):
        self.objectClass = objectClass
        self.objectTime = datetime.datetime.now()
        self.objectClassLabel = "Blank"
        self.objectConfidence = objectConfidence

    # is it a human.
    def isHuman(self):
        # Get the label from class id.
        self.objectClassLabel = classes[self.objectClass]

    def writeToFile(self):
        # Map data to columns.
        row = [obj.objectTime, obj.objectClassLabel, str(obj.objectConfidence)+"%"]

        # Amend CSV.
        with open("file.csv", 'a') as file:
            writer = csv.writer(file)
            writer.writerow(row)

# Generate Objects
def createObjects(my_objects):
    for i in range(100):
        my_objects.append(Object(i, i*12, 40))

def getLabelContent():
    # Load names of classes
    classes = None
    with open("network/coco.names", 'rt') as f:
        classes = f.read().rstrip('\n').split('\n')

    return classes

# Get LabelClasses
classes = getLabelContent()

# Creating Objects.
p1 = Object(1, 36, 50)
p2 = Object(3, 19, 80)
p3 = Object(2, 51, 20)

# Setting multiple objects into an array.
my_objects = []
my_objects = [p1, p2, p3]
# createObjects(my_objects)

# Printing each object
for obj in my_objects:
    obj.isHuman()
    obj.writeToFile()
    print(obj.objectTime, obj.objectClassLabel, str(obj.objectConfidence)+"%")
