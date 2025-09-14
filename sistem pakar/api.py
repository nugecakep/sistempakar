#!/usr/bin/env python3
"""
API Flask untuk Sistem Pakar Perawatan Kulit
"""

from flask import Flask, request, jsonify
from flask_cors import CORS
from model import SkinCareExpertSystem
import json

app = Flask(__name__)
CORS(app)  # Mengaktifkan CORS untuk semua endpoint
expert_system = SkinCareExpertSystem()

@app.route('/', methods=['GET'])
def index():
    """Endpoint root untuk informasi API"""
    return jsonify({
        "name": "Sistem Pakar Perawatan Kulit API",
        "version": "1.0",
        "endpoints": [
            {"path": "/", "method": "GET", "description": "Informasi API"},
            {"path": "/risk_factors", "method": "GET", "description": "Mendapatkan daftar faktor resiko"},
            {"path": "/symptoms", "method": "GET", "description": "Mendapatkan daftar gejala"},
            {"path": "/diagnose", "method": "POST", "description": "Melakukan diagnosis"}
        ]
    })

@app.route('/diagnose', methods=['POST'])
def diagnose():
    """
    Endpoint untuk diagnosis
    Menerima JSON dengan format:
    {
        "risk_factors": ["FR01", "FR02", ...],
        "symptoms": ["G01", "G02", ...]
    }
    """
    try:
        data = request.get_json()
        
        if not data:
            return jsonify({"error": "No data provided"}), 400
            
        risk_factors = data.get('risk_factors', [])
        symptoms = data.get('symptoms', [])
        
        result = expert_system.diagnose(risk_factors, symptoms)
        return jsonify(result)
    except Exception as e:
        return jsonify({"error": str(e)}), 500

@app.route('/risk_factors', methods=['GET'])
def get_risk_factors():
    """Endpoint untuk mendapatkan daftar faktor resiko"""
    risk_factors = []
    for id, description in expert_system.risk_factors.items():
        risk_factors.append({"id": id, "description": description})
    return jsonify(risk_factors)

@app.route('/symptoms', methods=['GET'])
def get_symptoms():
    """Endpoint untuk mendapatkan daftar gejala"""
    symptoms = []
    for id, description in expert_system.symptoms.items():
        symptoms.append({"id": id, "description": description})
    return jsonify(symptoms)

if __name__ == '__main__':
    app.run(debug=True, port=5000) 