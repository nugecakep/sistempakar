# Implementasi Certainty Factor pada Sistem Pakar Perawatan Kulit

## 🎯 Overview

Sistem pakar sekarang menggunakan **Certainty Factor (CF)** untuk menangani ketidakpastian dalam diagnosis perawatan kulit. CF memberikan tingkat kepercayaan yang lebih akurat dibandingkan simple accuracy score.

## 📊 Konsep Certainty Factor

### Definisi
Certainty Factor adalah metode untuk menangani ketidakpastian dalam sistem pakar dengan nilai antara **-1.0 hingga +1.0**:
- **+1.0**: Sangat yakin (definitely true)
- **+0.8**: Sangat yakin 
- **+0.6**: Yakin
- **+0.4**: Cukup yakin
- **+0.2**: Kurang yakin
- **0.0**: Tidak tahu (unknown)
- **-0.2 hingga -1.0**: Tingkat ketidakyakinan

### Formula Kombinasi CF
```
CF(A,B) = CF(A) + CF(B) * (1 - CF(A))  // jika keduanya positif
CF(A,B) = CF(A) + CF(B) * (1 + CF(A))  // jika keduanya negatif
CF(A,B) = (CF(A) + CF(B)) / (1 - min(|CF(A)|, |CF(B)|))  // jika berbeda tanda
```

## 🔧 Implementasi dalam Sistem

### 1. CF untuk Faktor Resiko → Jenis Kulit
```python
self.risk_to_skin_type = {
    'FR01': [('JK02', 0.7), ('JK04', 0.6)],  # AC → Kulit Kering (CF=0.7)
    'FR02': [('JK02', 0.8), ('JK04', 0.5)],  # Keriput → Kulit Kering (CF=0.8)
    'FR05': [('JK03', 0.8), ('JK04', 0.6)],  # Makanan pedas → Berminyak (CF=0.8)
    'FR08': [('JK03', 0.9), ('JK04', 0.7)]   # Pori besar → Berminyak (CF=0.9)
}
```

### 2. CF untuk Gejala → Kelainan Kulit
```python
self.symptoms_to_problems = {
    'G01': [('KK01', 0.9)],  # Bintik hitam → Flek (CF=0.9)
    'G02': [('KK02', 0.8)],  # Garis halus → Keriput (CF=0.8)
    'G04': [('KK04', 0.9)],  # Bernanah → Jerawat meradang (CF=0.9)
    'G07': [('KK07', 1.0)]   # Tidak ada gejala → Normal (CF=1.0)
}
```

### 3. CF Inheritance untuk Rekomendasi
```python
# CF Serum = CF Problem * 0.8 (inheritance factor)
inherited_cf = problem_cf_value * 0.8

# CF Treatment = CF Problem * 0.85 (inheritance factor)
inherited_cf = problem_cf_value * 0.85
```

## 🎨 Tampilan CF di Interface

### Metrics Dashboard
```
Confidence Score: 85%
Certainty Factor: 0.742
Confidence Level: Yakin
```

### Item Results
```
JK02: Kulit Kering          CF: 0.756 (Yakin)
KK01: Flek                  CF: 0.900 (Sangat Yakin)
S01: Vitamin C Serum        CF: 0.720 (Yakin)
T01: Whitening Facial       CF: 0.765 (Yakin)
```

## 🔍 Algoritma CF dalam Sistem

### 1. Rule-Based Skin Type Detection
```python
def apply_skin_type_rules_with_cf(self, risk_factors):
    # Rule 1: IF (FR01 AND FR02 AND FR03 AND FR04) THEN JK02
    if all(rf in risk_factors for rf in ['FR01', 'FR02', 'FR03', 'FR04']):
        cf_values = [get_cf_for_skin_type(rf, 'JK02') for rf in risk_factors]
        skin_type_cf['JK02'] = min(cf_values) * 0.9  # Kombinasi bonus
```

### 2. CF Combination
```python
def combine_certainty_factors(self, cf1, cf2):
    if cf1 >= 0 and cf2 >= 0:
        return cf1 + cf2 * (1 - cf1)
    elif cf1 < 0 and cf2 < 0:
        return cf1 + cf2 * (1 + cf1)
    else:
        return (cf1 + cf2) / (1 - min(abs(cf1), abs(cf2)))
```

### 3. Overall CF Calculation
```python
def calculate_overall_cf(self, result):
    weights = [0.3, 0.4, 0.15, 0.15]  # skin_type, problems, serums, treatments
    weighted_cf = sum(cf * weight for cf, weight in zip(cf_values, weights))
    return weighted_cf / sum(weights)
```

## 📈 Keunggulan CF Implementation

### ✅ Advantages
1. **Uncertainty Handling**: Menangani ketidakpastian dengan matematis
2. **Expert Knowledge**: Setiap rule memiliki tingkat kepercayaan
3. **Combinatorial Logic**: CF dapat dikombinasikan secara akurat
4. **Threshold-based Decision**: Keputusan berdasarkan ambang batas CF
5. **Granular Confidence**: Tingkat kepercayaan yang lebih detail

### 📊 CF Thresholds
- **Skin Types**: CF ≥ 0.3 (30%)
- **Problems**: CF ≥ 0.3 (30%)
- **Serums**: CF ≥ 0.2 (20%)
- **Treatments**: CF ≥ 0.2 (20%)

## 🎯 Confidence Levels

| CF Range | Level | Interpretasi |
|----------|-------|--------------|
| 0.8 - 1.0 | Sangat Yakin | Diagnosis sangat akurat |
| 0.6 - 0.79 | Yakin | Diagnosis dapat diandalkan |
| 0.4 - 0.59 | Cukup Yakin | Diagnosis cukup akurat |
| 0.2 - 0.39 | Kurang Yakin | Diagnosis perlu verifikasi |
| 0.0 - 0.19 | Tidak Yakin | Diagnosis tidak reliable |

## 🚀 Testing CF Implementation

### Test Case 1: Kulit Kering
```
Input: FR01, FR02, FR03, FR04
Expected CF: ~0.7-0.8 (Yakin)
```

### Test Case 2: Jerawat Meradang
```
Input: G04 (Bernanah)
Expected CF: 0.9 (Sangat Yakin)
```

### Test Case 3: Kombinasi Kompleks
```
Input: FR01, FR02, FR05, G01, G03
Expected: Multiple skin types dengan CF berbeda
```

## 📝 Kesimpulan

Implementasi Certainty Factor berhasil meningkatkan:
- ✅ **Akurasi diagnosis** dengan handling uncertainty
- ✅ **Transparansi sistem** dengan confidence levels
- ✅ **User trust** dengan CF scores yang jelas
- ✅ **Expert system quality** dengan mathematical foundation

Sistem sekarang memberikan diagnosis yang lebih akurat dan dapat dipercaya dengan tingkat kepercayaan yang terukur.
