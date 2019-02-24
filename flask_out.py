from flask import Flask, jsonify, render_template, request

app = Flask(__name__)

@app.route('/_add_numbers')
def add_numbers():
    start = time.clock()
    count = 0
    while time.clock() - start < 1:
        count = count + 1
        people_count = count
        print(people_count)

        return jsonify(people_count=people_count)

@app.route('/')
def index():
    return render_template('main.html')
