#!/usr/bin/env python3
"""
Sistem Pakar Untuk Menentukan Jenis Perawatan Kulit
Berdasarkan Faktor Resiko, Jenis Kulit, dan Gejala
"""

import json
from datetime import datetime

class SkinCareExpertSystem:
    def __init__(self):
        """Inisialisasi knowledge base sistem pakar"""
        self.setup_knowledge_base()

    def setup_knowledge_base(self):
        """Setup knowledge base berdasarkan tabel yang diberikan"""

        # Tabel 1: Faktor Resiko dan Jenis Kulit
        self.risk_factors = {
            'FR01': 'Bekerja di ruangan AC (air conditioner)',
            'FR02': 'Keriput',
            'FR03': 'Memiliki alergi',
            'FR04': 'Mengikuti program KB (Keluarga Berencana)',
            'FR05': 'Menyukai makanan pedas',
            'FR06': 'Sering menyentuh muka',
            'FR07': 'Sering memakan makanan berprotein tinggi',
            'FR08': 'Memiliki kulit berpori-pori besar'
        }

        # Mapping faktor resiko ke jenis kulit dengan Certainty Factor
        self.risk_to_skin_type = {
            'FR01': [('JK02', 0.7), ('JK04', 0.6)],  # AC -> Kulit Kering (CF=0.7), Kombinasi (CF=0.6)
            'FR02': [('JK02', 0.8), ('JK04', 0.5)],  # Keriput -> Kulit Kering (CF=0.8), Kombinasi (CF=0.5)
            'FR03': [('JK02', 0.6), ('JK04', 0.4)],  # Alergi -> Kulit Kering (CF=0.6), Kombinasi (CF=0.4)
            'FR04': [('JK02', 0.5), ('JK04', 0.3)],  # KB -> Kulit Kering (CF=0.5), Kombinasi (CF=0.3)
            'FR05': [('JK03', 0.8), ('JK04', 0.6)],  # Makanan pedas -> Berminyak (CF=0.8), Kombinasi (CF=0.6)
            'FR06': [('JK03', 0.7), ('JK04', 0.5)],  # Sentuh muka -> Berminyak (CF=0.7), Kombinasi (CF=0.5)
            'FR07': [('JK03', 0.6), ('JK04', 0.4)],  # Protein tinggi -> Berminyak (CF=0.6), Kombinasi (CF=0.4)
            'FR08': [('JK03', 0.9), ('JK04', 0.7)]   # Pori besar -> Berminyak (CF=0.9), Kombinasi (CF=0.7)
        }

        # Tabel 2: Jenis Kulit dan Gejala
        self.skin_types = {
            'JK01': 'Kulit Normal',
            'JK02': 'Kulit Kering',
            'JK03': 'Kulit Berminyak',
            'JK04': 'Kulit Kombinasi'
        }

        # Mapping jenis kulit ke gejala
        self.skin_to_symptoms = {
            'JK01': ['G07'],  # Normal -> Tidak ada gejala
            'JK02': ['G01', 'G02'],  # Kering -> Bintik hitam di wajah, Garis halus
            'JK03': ['G03', 'G04', 'G05', 'G06'],  # Berminyak -> Merah, Bernanah, Hitam hidung/pipi, Putih keras
            'JK04': ['G01', 'G02', 'G03', 'G04', 'G05', 'G06']  # Kombinasi -> Semua gejala
        }

        # Tabel 3: Gejala
        self.symptoms = {
            'G01': 'Bintik-bintik hitam di wajah',
            'G02': 'Garis halus disekitar mata atau mulut',
            'G03': 'Bintik-bintik merah di wajah',
            'G04': 'Bernanah',
            'G05': 'Bintik-bintik hitam disekitar hidung atau pipi',
            'G06': 'Bintik-bintik putih dan keras',
            'G07': 'Tidak ada gejala'
        }

        # Tabel 4: Jenis Kelainan Kulit dan Gejala
        self.skin_problems = {
            'KK01': 'Flek',
            'KK02': 'Keriput',
            'KK03': 'Jerawat Tidak Meradang',
            'KK04': 'Jerawat Meradang',
            'KK05': 'Komedo',
            'KK06': 'Milia',
            'KK07': 'Tidak ada kelainan kulit'
        }

        # Mapping gejala ke kelainan kulit dengan Certainty Factor
        self.symptoms_to_problems = {
            'G01': [('KK01', 0.9)],  # Bintik hitam -> Flek (CF=0.9)
            'G02': [('KK02', 0.8)],  # Garis halus -> Keriput (CF=0.8)
            'G03': [('KK03', 0.7)],  # Bintik merah -> Jerawat tidak meradang (CF=0.7)
            'G04': [('KK04', 0.9)],  # Bernanah -> Jerawat meradang (CF=0.9)
            'G05': [('KK05', 0.8)],  # Hitam hidung/pipi -> Komedo (CF=0.8)
            'G06': [('KK06', 0.7)],  # Putih keras -> Milia (CF=0.7)
            'G07': [('KK07', 1.0)]   # Tidak ada gejala -> Tidak ada kelainan (CF=1.0)
        }

        # Tabel 5: Kelainan Kulit ke Serum
        self.problem_to_serum = {
            'KK01': ['S01', 'S02', 'S03', 'S04', 'S05'],  # Flek
            'KK02': ['S01', 'S02', 'S03', 'S04', 'S06'],  # Keriput
            'KK03': ['S07', 'S08', 'S09'],  # Jerawat tidak meradang
            'KK04': ['S07', 'S08', 'S09'],  # Jerawat meradang
            'KK05': ['S07', 'S08', 'S09'],  # Komedo
            'KK06': ['S10'],  # Milia
            'KK07': ['S01']   # Tidak ada kelainan -> Vitamin C
        }

        # Tabel 6: Serum
        self.serums = {
            'S01': 'Vitamin C Brightening Serum',
            'S02': 'Niacinamide Spot Corrector',
            'S03': 'Alpha Arbutin Serum',
            'S04': 'Hyaluronic Acid Hydration Serum',
            'S05': 'Kojic Acid Pigment Fader',
            'S06': 'Retinol Anti-Wrinkle Serum',
            'S07': 'Salicylic Acid BHA Serum',
            'S08': 'Tea Tree Anti-Acne Serum',
            'S09': 'Charcoal Pore Control Serum',
            'S10': 'Centella Soothing Serum'
        }

        # Tabel 7: Kelainan Kulit ke Treatment
        self.problem_to_treatment = {
            'KK01': ['T01', 'T02', 'T03'],  # Flek
            'KK02': ['T04', 'T05'],  # Keriput
            'KK03': ['T06', 'T07'],  # Jerawat tidak meradang
            'KK04': ['T06', 'T07'],  # Jerawat meradang
            'KK05': ['T05'],  # Komedo -> Collagen Lifting (DIPERBAIKI)
            'KK06': ['T08'],  # Milia
            'KK07': ['T09', 'T10']  # Tidak ada kelainan
        }

        # Tabel 8: Treatment
        self.treatments = {
            'T01': 'Whitening Facial',
            'T02': 'Anti-Spot Treatment',
            'T03': 'Brightening Laser',
            'T04': 'Anti-Aging Facial',
            'T05': 'Collagen Lifting',
            'T06': 'Acne Facial',
            'T07': 'Deep Pore Cleansing',
            'T08': 'Anti-Inflammatory Therapy',
            'T09': 'Blackhead Removal Treatment',
            'T10': 'Skin Rejuvenation & Maintenance'
        }

    def combine_certainty_factors(self, cf1, cf2):
        """
        Kombinasi dua Certainty Factor menggunakan formula standar
        CF(A,B) = CF(A) + CF(B) * (1 - CF(A)) jika keduanya positif
        """
        if cf1 >= 0 and cf2 >= 0:
            return cf1 + cf2 * (1 - cf1)
        elif cf1 < 0 and cf2 < 0:
            return cf1 + cf2 * (1 + cf1)
        else:
            return (cf1 + cf2) / (1 - min(abs(cf1), abs(cf2)))

    def calculate_combined_cf(self, cf_list):
        """
        Menghitung CF gabungan dari multiple evidence
        """
        if not cf_list:
            return 0.0

        combined_cf = cf_list[0]
        for cf in cf_list[1:]:
            combined_cf = self.combine_certainty_factors(combined_cf, cf)

        return combined_cf

    def get_confidence_level(self, cf_value):
        """
        Menentukan level kepercayaan berdasarkan nilai CF
        """
        if cf_value >= 0.8:
            return "Sangat Yakin"
        elif cf_value >= 0.6:
            return "Yakin"
        elif cf_value >= 0.4:
            return "Cukup Yakin"
        elif cf_value >= 0.2:
            return "Kurang Yakin"
        else:
            return "Tidak Yakin"

    def calculate_overall_cf(self, result):
        """
        Menghitung CF keseluruhan berdasarkan hasil diagnosis
        """
        cf_values = []

        # Ambil CF tertinggi dari setiap kategori
        if result['skin_types']:
            cf_values.append(result['skin_types'][0]['certainty_factor'])

        if result['problems']:
            cf_values.append(result['problems'][0]['certainty_factor'])

        if result['serums']:
            cf_values.append(result['serums'][0]['certainty_factor'])

        if result['treatments']:
            cf_values.append(result['treatments'][0]['certainty_factor'])

        if not cf_values:
            return 0.0

        # Hitung rata-rata weighted
        weights = [0.3, 0.4, 0.15, 0.15]  # skin_type, problems, serums, treatments
        weighted_cf = 0.0
        total_weight = 0.0

        for i, cf in enumerate(cf_values):
            if i < len(weights):
                weighted_cf += cf * weights[i]
                total_weight += weights[i]

        return weighted_cf / total_weight if total_weight > 0 else 0.0

    def calculate_accuracy(self, risk_factors_input, symptoms_input, rule_matches, total_rules):
        """
        Menghitung akurasi diagnosis berdasarkan input dan rule matches
        """
        # Bobot untuk setiap komponen
        risk_weight = 0.25
        symptom_weight = 0.35
        rule_match_weight = 0.40
        
        # Hitung akurasi faktor resiko
        risk_accuracy = 0
        if risk_factors_input:
            valid_risks = [r for r in risk_factors_input if r in self.risk_factors]
            risk_accuracy = len(valid_risks) / len(risk_factors_input) if risk_factors_input else 0
        
        # Hitung akurasi gejala
        symptom_accuracy = 0
        if symptoms_input:
            valid_symptoms = [s for s in symptoms_input if s in self.symptoms]
            symptom_accuracy = len(valid_symptoms) / len(symptoms_input) if symptoms_input else 0
        
        # Hitung akurasi rule matching
        rule_match_accuracy = 0
        if total_rules > 0:
            rule_match_accuracy = rule_matches / total_rules
        
        # Hitung akurasi konsistensi
        consistency_accuracy = 0
        if risk_factors_input and symptoms_input:
            # Tentukan jenis kulit dari faktor resiko
            predicted_skin_types = set()
            for risk in risk_factors_input:
                if risk in self.risk_to_skin_type:
                    predicted_skin_types.update(self.risk_to_skin_type[risk])
            
            # Periksa apakah gejala konsisten dengan jenis kulit
            consistent_symptoms = 0
            for skin_type in predicted_skin_types:
                for symptom in symptoms_input:
                    if skin_type in self.skin_types and symptom in self.symptoms:
                        if skin_type == 'JK04' or symptom in self.skin_to_symptoms.get(skin_type, []):
                            consistent_symptoms += 1
            
            consistency_accuracy = consistent_symptoms / len(symptoms_input) if symptoms_input else 0
        
        # Hitung akurasi total dengan bobot
        if risk_factors_input or symptoms_input:
            total_accuracy = (risk_accuracy * risk_weight) + (symptom_accuracy * symptom_weight) + (rule_match_accuracy * rule_match_weight)
            
            # Tambahkan faktor konsistensi jika ada input gejala dan faktor resiko
            if risk_factors_input and symptoms_input:
                total_accuracy = total_accuracy * 0.8 + consistency_accuracy * 0.2
                
            # Jika output sangat sedikit dibanding input, kurangi akurasi
            input_count = len(risk_factors_input) + len(symptoms_input)
            output_count = rule_matches
            if input_count > 0 and output_count / input_count < 0.5:
                total_accuracy *= 0.8
                
            # Jika output sangat banyak dibanding input, tingkatkan akurasi
            if input_count > 0 and output_count / input_count > 1.5:
                total_accuracy *= 1.1
                
            # Pastikan akurasi tidak melebihi 100%
            total_accuracy = min(total_accuracy, 1.0)
        else:
            total_accuracy = 0
        
        # Konversi ke persentase dan pembulatan
        return round(total_accuracy * 100, 1)

    def apply_skin_type_rules_with_cf(self, risk_factors):
        """
        Implementasi aturan IF-THEN untuk menentukan jenis kulit dengan Certainty Factor
        """
        skin_type_cf = {}  # Dictionary untuk menyimpan CF setiap jenis kulit

        # Rule 1: IF (FR01 AND FR02 AND FR03 AND FR04) THEN JK02
        if all(rf in risk_factors for rf in ['FR01', 'FR02', 'FR03', 'FR04']):
            # CF untuk kombinasi rule = minimum CF dari semua evidence
            cf_values = []
            for rf in ['FR01', 'FR02', 'FR03', 'FR04']:
                for skin_type, cf in self.risk_to_skin_type[rf]:
                    if skin_type == 'JK02':
                        cf_values.append(cf)
            if cf_values:
                skin_type_cf['JK02'] = min(cf_values) * 0.9  # Kombinasi rule mendapat bonus

        # Rule 2: IF (FR05 AND FR06 AND FR07 AND FR08) THEN JK03
        if all(rf in risk_factors for rf in ['FR05', 'FR06', 'FR07', 'FR08']):
            cf_values = []
            for rf in ['FR05', 'FR06', 'FR07', 'FR08']:
                for skin_type, cf in self.risk_to_skin_type[rf]:
                    if skin_type == 'JK03':
                        cf_values.append(cf)
            if cf_values:
                skin_type_cf['JK03'] = min(cf_values) * 0.9

        # Rule 3: IF (FR01 AND FR02 AND FR03 AND FR04 AND FR05 AND FR06 AND FR07 AND FR08) THEN JK04
        if all(rf in risk_factors for rf in ['FR01', 'FR02', 'FR03', 'FR04', 'FR05', 'FR06', 'FR07', 'FR08']):
            cf_values = []
            for rf in risk_factors:
                for skin_type, cf in self.risk_to_skin_type[rf]:
                    if skin_type == 'JK04':
                        cf_values.append(cf)
            if cf_values:
                skin_type_cf['JK04'] = min(cf_values) * 0.95  # Kombinasi lengkap mendapat bonus lebih

        # Individual mapping dengan CF
        for risk in risk_factors:
            if risk in self.risk_to_skin_type:
                for skin_type, cf in self.risk_to_skin_type[risk]:
                    if skin_type in skin_type_cf:
                        # Kombinasi CF jika sudah ada
                        skin_type_cf[skin_type] = self.combine_certainty_factors(skin_type_cf[skin_type], cf)
                    else:
                        skin_type_cf[skin_type] = cf

        return skin_type_cf

    def diagnose(self, risk_factors_input, symptoms_input):
        """
        Fungsi utama untuk diagnosis
        Input: list faktor resiko dan gejala yang dialami user
        Output: rekomendasi serum dan treatment
        """
        # Validasi input
        valid_risk_factors = [rf for rf in risk_factors_input if rf in self.risk_factors]
        valid_symptoms = [s for s in symptoms_input if s in self.symptoms]

        # Inisialisasi hasil
        result = {
            'timestamp': datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
            'risk_factors': valid_risk_factors,
            'symptoms': valid_symptoms,
            'skin_types': [],
            'problems': [],
            'serums': [],
            'treatments': [],
            'rule_matches': 0,
            'total_rules': 0
        }

        # Step 1: Determine skin types using CF-based approach
        skin_type_cf = self.apply_skin_type_rules_with_cf(valid_risk_factors)

        # Filter skin types dengan CF > threshold (0.3)
        cf_threshold = 0.3
        for skin_type_id, cf_value in skin_type_cf.items():
            if cf_value >= cf_threshold and skin_type_id in self.skin_types:
                result['skin_types'].append({
                    'id': skin_type_id,
                    'name': self.skin_types[skin_type_id],
                    'certainty_factor': round(cf_value, 3),
                    'confidence_level': self.get_confidence_level(cf_value)
                })

        # Sort berdasarkan CF tertinggi
        result['skin_types'].sort(key=lambda x: x['certainty_factor'], reverse=True)

        result['rule_matches'] += len(result['skin_types'])
        result['total_rules'] += max(1, len(valid_risk_factors))
        
        # Step 2: Determine skin problems based on symptoms with CF
        problem_cf = {}

        for symptom in valid_symptoms:
            if symptom in self.symptoms_to_problems:
                for problem_id, cf_value in self.symptoms_to_problems[symptom]:
                    if problem_id in problem_cf:
                        # Kombinasi CF jika problem sudah ada
                        problem_cf[problem_id] = self.combine_certainty_factors(problem_cf[problem_id], cf_value)
                    else:
                        problem_cf[problem_id] = cf_value

        # Filter problems dengan CF > threshold
        for problem_id, cf_value in problem_cf.items():
            if cf_value >= cf_threshold and problem_id in self.skin_problems:
                result['problems'].append({
                    'id': problem_id,
                    'name': self.skin_problems[problem_id],
                    'certainty_factor': round(cf_value, 3),
                    'confidence_level': self.get_confidence_level(cf_value)
                })

        # Sort berdasarkan CF tertinggi
        result['problems'].sort(key=lambda x: x['certainty_factor'], reverse=True)

        result['rule_matches'] += len(result['problems'])
        result['total_rules'] += max(1, len(valid_symptoms))
        
        # Step 3: Recommend serums based on problems with CF inheritance
        serum_cf = {}

        for problem in result['problems']:
            problem_id = problem['id']
            problem_cf_value = problem['certainty_factor']

            if problem_id in self.problem_to_serum:
                for serum_id in self.problem_to_serum[problem_id]:
                    # CF serum = CF problem * 0.8 (inheritance factor)
                    inherited_cf = problem_cf_value * 0.8

                    if serum_id in serum_cf:
                        serum_cf[serum_id] = self.combine_certainty_factors(serum_cf[serum_id], inherited_cf)
                    else:
                        serum_cf[serum_id] = inherited_cf

        # Filter serums dengan CF > threshold yang lebih rendah (0.2)
        serum_threshold = 0.2
        for serum_id, cf_value in serum_cf.items():
            if cf_value >= serum_threshold and serum_id in self.serums:
                result['serums'].append({
                    'id': serum_id,
                    'name': self.serums[serum_id],
                    'certainty_factor': round(cf_value, 3),
                    'confidence_level': self.get_confidence_level(cf_value)
                })

        # Sort berdasarkan CF tertinggi
        result['serums'].sort(key=lambda x: x['certainty_factor'], reverse=True)

        result['rule_matches'] += len(result['serums'])

        # Step 4: Recommend treatments based on problems with CF inheritance
        treatment_cf = {}

        for problem in result['problems']:
            problem_id = problem['id']
            problem_cf_value = problem['certainty_factor']

            if problem_id in self.problem_to_treatment:
                for treatment_id in self.problem_to_treatment[problem_id]:
                    # CF treatment = CF problem * 0.85 (inheritance factor)
                    inherited_cf = problem_cf_value * 0.85

                    if treatment_id in treatment_cf:
                        treatment_cf[treatment_id] = self.combine_certainty_factors(treatment_cf[treatment_id], inherited_cf)
                    else:
                        treatment_cf[treatment_id] = inherited_cf

        # Filter treatments dengan CF > threshold
        for treatment_id, cf_value in treatment_cf.items():
            if cf_value >= serum_threshold and treatment_id in self.treatments:
                result['treatments'].append({
                    'id': treatment_id,
                    'name': self.treatments[treatment_id],
                    'certainty_factor': round(cf_value, 3),
                    'confidence_level': self.get_confidence_level(cf_value)
                })

        # Sort berdasarkan CF tertinggi
        result['treatments'].sort(key=lambda x: x['certainty_factor'], reverse=True)

        result['rule_matches'] += len(result['treatments'])
        
        # Calculate overall certainty factor
        result['overall_certainty_factor'] = self.calculate_overall_cf(result)
        result['overall_confidence_level'] = self.get_confidence_level(result['overall_certainty_factor'])

        # Calculate accuracy (tetap ada untuk backward compatibility)
        result['accuracy'] = self.calculate_accuracy(
            valid_risk_factors,
            valid_symptoms,
            result['rule_matches'],
            result['total_rules']
        )
        
        return result

# API untuk diakses dari PHP
def api_diagnose(risk_factors, symptoms):
    expert_system = SkinCareExpertSystem()
    result = expert_system.diagnose(risk_factors, symptoms)
    return json.dumps(result)

# Jika dijalankan langsung
if __name__ == "__main__":
    import sys
    
    # Jika ada argumen command line, gunakan sebagai input
    if len(sys.argv) > 2:
        risk_factors = sys.argv[1].split(',') if sys.argv[1] else []
        symptoms = sys.argv[2].split(',') if sys.argv[2] else []
        
        expert_system = SkinCareExpertSystem()
        result = expert_system.diagnose(risk_factors, symptoms)
        print(json.dumps(result))
    else:
        print("Usage: python model.py 'risk_factors' 'symptoms'")
        print("Example: python model.py 'FR01,FR02' 'G01,G03'") 