#!/usr/bin/env python3
"""
Test script untuk Certainty Factor implementation
"""

from model import SkinCareExpertSystem
import json

def test_certainty_factor():
    """Test implementasi Certainty Factor"""
    
    print("🧪 Testing Certainty Factor Implementation")
    print("=" * 50)
    
    # Inisialisasi sistem
    expert_system = SkinCareExpertSystem()
    
    # Test Case 1: Kulit Kering (FR01-FR04)
    print("\n📋 Test Case 1: Kulit Kering")
    print("-" * 30)
    risk_factors = ['FR01', 'FR02', 'FR03', 'FR04']
    symptoms = ['G01', 'G02']
    
    result = expert_system.diagnose(risk_factors, symptoms)
    
    print(f"Risk Factors: {risk_factors}")
    print(f"Symptoms: {symptoms}")
    print(f"Overall CF: {result.get('overall_certainty_factor', 'N/A')}")
    print(f"Confidence Level: {result.get('overall_confidence_level', 'N/A')}")
    
    print("\nSkin Types:")
    for skin_type in result.get('skin_types', []):
        print(f"  - {skin_type['name']}: CF={skin_type.get('certainty_factor', 'N/A')} ({skin_type.get('confidence_level', 'N/A')})")
    
    print("\nProblems:")
    for problem in result.get('problems', []):
        print(f"  - {problem['name']}: CF={problem.get('certainty_factor', 'N/A')} ({problem.get('confidence_level', 'N/A')})")
    
    # Test Case 2: Kulit Berminyak (FR05-FR08)
    print("\n📋 Test Case 2: Kulit Berminyak")
    print("-" * 30)
    risk_factors = ['FR05', 'FR06', 'FR07', 'FR08']
    symptoms = ['G03', 'G04', 'G05']
    
    result = expert_system.diagnose(risk_factors, symptoms)
    
    print(f"Risk Factors: {risk_factors}")
    print(f"Symptoms: {symptoms}")
    print(f"Overall CF: {result.get('overall_certainty_factor', 'N/A')}")
    print(f"Confidence Level: {result.get('overall_confidence_level', 'N/A')}")
    
    print("\nSkin Types:")
    for skin_type in result.get('skin_types', []):
        print(f"  - {skin_type['name']}: CF={skin_type.get('certainty_factor', 'N/A')} ({skin_type.get('confidence_level', 'N/A')})")
    
    print("\nProblems:")
    for problem in result.get('problems', []):
        print(f"  - {problem['name']}: CF={problem.get('certainty_factor', 'N/A')} ({problem.get('confidence_level', 'N/A')})")
    
    # Test Case 3: Kombinasi Kompleks
    print("\n📋 Test Case 3: Kombinasi Kompleks")
    print("-" * 30)
    risk_factors = ['FR01', 'FR02', 'FR05', 'FR06', 'FR07', 'FR08']
    symptoms = ['G01', 'G03', 'G05']
    
    result = expert_system.diagnose(risk_factors, symptoms)
    
    print(f"Risk Factors: {risk_factors}")
    print(f"Symptoms: {symptoms}")
    print(f"Overall CF: {result.get('overall_certainty_factor', 'N/A')}")
    print(f"Confidence Level: {result.get('overall_confidence_level', 'N/A')}")
    
    print("\nSkin Types:")
    for skin_type in result.get('skin_types', []):
        print(f"  - {skin_type['name']}: CF={skin_type.get('certainty_factor', 'N/A')} ({skin_type.get('confidence_level', 'N/A')})")
    
    print("\nTop 3 Serums:")
    for i, serum in enumerate(result.get('serums', [])[:3]):
        print(f"  {i+1}. {serum['name']}: CF={serum.get('certainty_factor', 'N/A')} ({serum.get('confidence_level', 'N/A')})")
    
    print("\nTop 3 Treatments:")
    for i, treatment in enumerate(result.get('treatments', [])[:3]):
        print(f"  {i+1}. {treatment['name']}: CF={treatment.get('certainty_factor', 'N/A')} ({treatment.get('confidence_level', 'N/A')})")
    
    print("\n" + "=" * 50)
    print("✅ Certainty Factor Testing Completed!")

def test_cf_combination():
    """Test kombinasi CF"""
    print("\n🔬 Testing CF Combination")
    print("-" * 30)
    
    expert_system = SkinCareExpertSystem()
    
    # Test kombinasi CF
    cf1 = 0.7
    cf2 = 0.6
    combined = expert_system.combine_certainty_factors(cf1, cf2)
    
    print(f"CF1: {cf1}")
    print(f"CF2: {cf2}")
    print(f"Combined CF: {combined}")
    print(f"Expected: {cf1 + cf2 * (1 - cf1)}")
    
    # Test confidence level
    levels = [0.9, 0.7, 0.5, 0.3, 0.1]
    print(f"\nConfidence Levels:")
    for cf in levels:
        level = expert_system.get_confidence_level(cf)
        print(f"  CF {cf}: {level}")

if __name__ == "__main__":
    test_certainty_factor()
    test_cf_combination()
