from flask import Flask, render_template
import random

app = Flask(__name__)

@app.route('/')
def dice_roll():
    dice_roll = random.randint(1, 6)

    return render_template('main.html', dice_roll=dice_roll)

if __name__ == '__main__':
        app.run(debug=True)
