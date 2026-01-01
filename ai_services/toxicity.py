# F13 - Toxicity Detection Service
# Part of accessible Community Forum with AI Moderation (F13)

import sys
import json
from transformers import pipeline

try:
    classifier = pipeline("text-classification", model="unitary/toxic-bert", top_k=None)
except Exception as e:
    print(json.dumps({"error": str(e), "safe": True, "reason": "Model load error"}))
    sys.exit(1)

def check_toxicity(text):
    results = classifier(text)
    
    labels_scores = {item['label']: item['score'] for item in results[0]}
    
    threshold = 0.7
    
    is_toxic = False
    reasons = []
    
    toxic_labels = ['toxic', 'severe_toxic', 'obscene', 'threat', 'insult', 'identity_hate']
    
    for label in toxic_labels:
        if labels_scores.get(label, 0) > threshold:
            is_toxic = True
            reasons.append(label)

    return {
        "safe": not is_toxic,
        "toxicity_score": labels_scores.get('toxic', 0),
        "reason": ", ".join(reasons) if is_toxic else None,
        "details": labels_scores
    }

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print(json.dumps({"error": "No text provided"}))
        sys.exit(1)
        
    text_input = sys.argv[1]
    result = check_toxicity(text_input)
    print(json.dumps(result))
