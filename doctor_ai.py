from flask import Flask, request, jsonify

app = Flask(__name__)

@app.route('/predict', methods=['POST'])
def predict():
    data = request.get_json()
    symptom = data.get('symptom', '').lower()

    # Simple AI logic for now
    if 'chest' in symptom or 'heart' in symptom:
        doctor = 'Cardiologist'
    elif 'tooth' in symptom or 'gum' in symptom:
        doctor = 'Dentist'
    elif 'skin' in symptom or 'rash' in symptom:
        doctor = 'Dermatologist'
    elif 'eye' in symptom or 'vision' in symptom:
        doctor = 'Ophthalmologist'
    elif 'fever' in symptom or 'cough' in symptom:
        doctor = 'General Physician'
    else:
        doctor = 'General Doctor'

    return jsonify({'specialist': doctor})

if __name__ == '__main__':
    app.run(port=5000)
