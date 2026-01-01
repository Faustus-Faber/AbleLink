# F7 - RAG Query Service
# Part of AI Navigation & Assistance Chatbot (F7)

import os
import sys
import json
from langchain_huggingface import HuggingFaceEmbeddings
from langchain_community.vectorstores import FAISS

def query_vector_store(query_text, k=3):
    vector_store_path = os.path.join(os.path.dirname(__file__), 'vector_store')
    
    if not os.path.exists(vector_store_path):
        return {"error": "Vector store not found. Please run rag_ingest.py first."}

    embeddings = HuggingFaceEmbeddings(model_name="all-MiniLM-L6-v2")
    
    new_db = FAISS.load_local(vector_store_path, embeddings, allow_dangerous_deserialization=True)
    
    docs = new_db.similarity_search(query_text, k=k)
    
    results = []
    combined_context = ""
    
    for doc in docs:
        results.append({
            "content": doc.page_content,
            "source": doc.metadata.get("source", "unknown")
        })
        combined_context += doc.page_content + "\n\n"
        
    return {
        "query": query_text,
        "context": combined_context.strip(),
        "documents": results
    }

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print(json.dumps({"error": "No query provided"}))
        sys.exit(1)
        
    user_query = sys.argv[1]
    response = query_vector_store(user_query)
    print(json.dumps(response))
