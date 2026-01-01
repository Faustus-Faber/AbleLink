# F7 - RAG Ingestion Service
# Part of AI Navigation & Assistance Chatbot (F7)

import os
import sys
from langchain_community.document_loaders import DirectoryLoader, TextLoader, UnstructuredMarkdownLoader
from langchain_text_splitters import RecursiveCharacterTextSplitter
from langchain_huggingface import HuggingFaceEmbeddings
from langchain_community.vectorstores import FAISS

def ingest_knowledge_base():
    base_dir = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
    docs_dir = os.path.join(base_dir, 'docs')
    knowledge_dir = os.path.join(base_dir, 'storage', 'app', 'public', 'knowledge')

    vector_store_path = os.path.join(os.path.dirname(__file__), 'vector_store')
    if not os.path.exists(vector_store_path):
        os.makedirs(vector_store_path)

    documents = []

    if os.path.exists(docs_dir):
        print(f"Loading docs from {docs_dir}...")
        loader = DirectoryLoader(docs_dir, glob="**/*.md", loader_cls=UnstructuredMarkdownLoader)
        documents.extend(loader.load())

    if os.path.exists(knowledge_dir):
         print(f"Loading knowledge from {knowledge_dir}...")
         loader_md = DirectoryLoader(knowledge_dir, glob="**/*.md", loader_cls=UnstructuredMarkdownLoader)
         documents.extend(loader_md.load())
         loader_txt = DirectoryLoader(knowledge_dir, glob="**/*.txt", loader_cls=TextLoader)
         documents.extend(loader_txt.load())
    
    if not documents:
        print("No documents found to ingest.")
        return

    text_splitter = RecursiveCharacterTextSplitter(
        chunk_size=1000,
        chunk_overlap=200,
        add_start_index=True,
    )
    all_splits = text_splitter.split_documents(documents)
    print(f"Split into {len(all_splits)} chunks.")

    print("Generating embeddings (all-MiniLM-L6-v2)...")
    embeddings = HuggingFaceEmbeddings(model_name="all-MiniLM-L6-v2")
    
    vector_store = FAISS.from_documents(all_splits, embeddings)
    vector_store.save_local(vector_store_path)
    print(f"Vector store saved to {vector_store_path}")

if __name__ == "__main__":
    ingest_knowledge_base()
